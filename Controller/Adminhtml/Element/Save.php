<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Element;

use OuterEdge\Layout\Controller\Adminhtml\Element;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\ElementFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\File\Uploader;
use OuterEdge\Layout\Model\Element\Image;
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
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param ElementFactory $elementFactory
     * @param DateTime $dateTime
     * @param UploaderFactory $uploader
     * @param Image $imageModel
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ElementFactory $elementFactory,
        DateTime $dateTime,
        UploaderFactory $uploader,
        Image $imageModel
    ) {
        $this->dateTime = $dateTime;
        $this->uploader = $uploader;
        $this->imageModel = $imageModel;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $elementFactory
        );
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
            $elementId = $this->getRequest()->getParam('element_id');

            $data['updated_at'] = $this->dateTime->date();

            $model = $this->elementFactory->create();

            if ($elementId) {
                $model->load($elementId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This element no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            } else {
                $data['created_at'] = $this->dateTime->date();
            }

            $imageName = $this->uploadFileAndGetName('image', $this->imageModel->getBaseDir(), $data);
            $data['image'] = $imageName;

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
    public function uploadFileAndGetName($input, $destinationFolder, $data)
    {
        try {
            if (isset($data[$input]['delete'])) {
                return '';
            } else {
                $uploader = $this->uploader->create(['fileId' => $input]);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);
                $result = $uploader->save($destinationFolder);
                return $result['file'];
            }
        } catch (Exception $e) {
            if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                $this->messageManager->addError($e->getMessage());
            } else {
                if (isset($data[$input]['value'])) {
                    return $data[$input]['value'];
                }
            }
        }
        return '';
    }
}
