<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Elements;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Uploader;
use OuterEdge\Layout\Model\Elements\Image;
use RuntimeException;
use Exception;

class Save extends Action
{
    /**
     * @var DateTime
     */
    protected $datetime;

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
     * @param DateTime $datetime
     * @param UploaderFactory $uploader
     * @param Image $imageModel
     */
    public function __construct(
        Context $context,
        DateTime $datetime,
        UploaderFactory $uploader,
        Image $imageModel
    ) {
        $this->datetime = $datetime;
        $this->uploader = $uploader;
        $this->imageModel = $imageModel;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_objectManager->create('OuterEdge\Layout\Model\Elements');

            $id = $this->getRequest()->getParam('element_id');
            if ($id) {
                $model->load($id);
            } else {
                $data['created_at'] = $this->datetime->date();
            }

            $imageName = $this->uploadFileAndGetName('image', $this->imageModel->getBaseDir(), $data);
            $data['image'] = $imageName;

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('The data has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('layout/groups/edit', ['group_id' => $model->getGroupId(), '_current' => true]);
                }
                return $resultRedirect->setPath('layout/groups/edit', ['group_id' => $model->getGroupId()]);
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the data.'));
            }
            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('layout/groups/edit', ['group_id' => $model->getGroupId()]);
        }

        return $resultRedirect->setPath('*/*/');
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
