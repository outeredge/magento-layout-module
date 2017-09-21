<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use OuterEdge\Layout\Controller\Adminhtml\Element;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\ElementFactory;
use OuterEdge\Layout\Model\GroupFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Api\ImageProcessorInterface;
use Magento\Framework\Api\Data\ImageContentInterfaceFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image;
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
    private $uploaderFactory;

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
     * @param GroupFactory $groupFactory
     * @param DateTime $dateTime
     * @param UploaderFactory $uploaderFactory
     * @param ImageProcessorInterface $imageProcessor
     * @param ImageContentInterfaceFactory $imageContentFactory
     * @param Filesystem $filesystem
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ElementFactory $elementFactory,
        GroupFactory $groupFactory,
        DateTime $dateTime,
        UploaderFactory $uploaderFactory,
        ImageProcessorInterface $imageProcessor,
        ImageContentInterfaceFactory $imageContentFactory,
        Filesystem $filesystem
    ) {
        $this->dateTime = $dateTime;
        $this->uploaderFactory = $uploaderFactory;
        $this->imageProcessor = $imageProcessor;
        $this->imageContentFactory = $imageContentFactory;
        $this->tmpDirectory = $filesystem->getDirectoryRead(DirectoryList::SYS_TMP);
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $elementFactory,
            $groupFactory
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

            if (isset($data['image']['delete']) && $data['image']['delete']) {
                $data['image'] = null;
            } else {
                try {
                    $uploader = $this->uploaderFactory->create(['fileId' => 'image']);
                    $imageData = $uploader->validateFile();
                    if ($imageData['name'] && $imageData['type'] && $imageData['tmp_name'] && $imageData['size'] > 0) {
                        $imageContentDataObject = $this->imageContentFactory->create()
                            ->setName($imageData['name'])
                            ->setBase64EncodedData($this->getBase64EncodedData($imageData['tmp_name']))
                            ->setType($imageData['type']);
                        $data['image'] = $this->imageProcessor->processImageContent(Image::LAYOUT_IMAGE_DIR, $imageContentDataObject);
                    } else {
                        unset($data['image']);
                    }
                } catch (Exception $e) {
                    // The file was probably not uploaded - skip and continue with model saving
                    unset($data['image']);
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
                return $resultRedirect->setPath('*/group/edit', [
                    'entity_id' => $model->getGroupId(),
                    'active_tab' => 'elements'
                ], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setElementData($data);
                return $resultRedirect->setPath('*/*/edit', ['element_id' => $model->getId(), '_current' => true], ['error' => true]);
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}
