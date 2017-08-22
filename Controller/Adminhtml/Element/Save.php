<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use OuterEdge\Layout\Controller\Adminhtml\Element;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\ElementFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Api\ImageProcessorInterface;
use Magento\Framework\Api\Data\ImageContentInterfaceFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image as ElementImage;

use Exception;

class Save extends Element
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @var UploaderFactory
     */
    protected $uploader;

    /**
     * @var Image
     */
    protected $imageModel;

    /**
     * @var ImageProcessorInterface
     */
    protected $imageProcessor;

    /**
     * @var ImageContentInterfaceFactory
     */
    protected $imageContentFactory;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param ElementFactory $elementFactory
     * @param DateTime $dateTime
     * @param ImageProcessorInterface $imageProcessor
     * @param ImageContentInterfaceFactory $imageContentFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ElementFactory $elementFactory,
        DateTime $dateTime,
        ImageProcessorInterface $imageProcessor,
        ImageContentInterfaceFactory $imageContentFactory,
        Filesystem $filesystem
    ) {
        $this->dateTime = $dateTime;
        $this->imageProcessor = $imageProcessor;
        $this->imageContentFactory = $imageContentFactory;
        $this->tmpDirectory = $filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $elementFactory
        );
    }

    protected function getBase64EncodedData($fileName)
    {
        $fileContent = $this->tmpDirectory->readFile($this->tmpDirectory->getRelativePath($fileName));
        $encodedContent = base64_encode($fileContent);
        return $encodedContent;
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $data['updated_at'] = $this->dateTime->date();

            $model = $this->elementFactory->create();

            $elementId = $this->getRequest()->getParam('element_id');
            if ($elementId) {
                $model->load($elementId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This element no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            } else {
                $data['created_at'] = $this->dateTime->date();
            }

            if (!empty($_FILES)) {
                foreach ($_FILES as $imageName => $imageData) {
                    if (isset($data[$imageName]['delete']) && $data[$imageName]['delete']) {
                        $data[$imageName] = null;
                    } elseif ($imageData['name'] && $imageData['type'] && $imageData['tmp_name'] && $imageData['size'] > 0) {
                        $imageContentDataObject = $this->imageContentFactory->create()
                            ->setName($imageData['name'])
                            ->setBase64EncodedData($this->getBase64EncodedData($imageData['tmp_name']))
                            ->setType($imageData['type']);
                        $data[$imageName] = $this->imageProcessor->processImageContent(ElementImage::LAYOUT_IMAGE_DIR, $imageContentDataObject);
                    } else {
                        unset($data[$imageName]);
                    }
                }
            }

            $model->addData($data);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The element has been saved.'));

                $this->_session->setElementData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['element_id' => $model->getId(), '_current' => true], ['error' => false]);
                }
                return $resultRedirect->setPath('*/*/', [], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setElementData($data);
                return $resultRedirect->setPath('*/*/edit', ['element_id' => $model->getId(), '_current' => true], ['error' => true]);
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }

    /**
     * uploadFileAndGetName
     * @param $input
     * @param $destinationFolder
     * @param $data
     * @return string
     */
//    public function uploadFileAndGetName($input, $destinationFolder, $data)
//    {
//        try {
//            if (isset($data[$input]['delete'])) {
//                return '';
//            } else {
//                $uploader = $this->uploader->create(['fileId' => $input]);
//                $uploader->setAllowRenameFiles(true);
//                $uploader->setFilesDispersion(true);
//                $uploader->setAllowCreateFolders(true);
//                $result = $uploader->save($destinationFolder);
//                return $result['file'];
//            }
//        } catch (Exception $e) {
//            if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
//                $this->messageManager->addError($e->getMessage());
//            } else {
//                if (isset($data[$input]['value'])) {
//                    return $data[$input]['value'];
//                }
//            }
//        }
//        return '';
//    }
}
