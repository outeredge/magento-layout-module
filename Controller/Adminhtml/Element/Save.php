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
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use OuterEdge\Layout\Block\Adminhtml\Element\Helper\Image;
use Exception;
use Magento\PageCache\Model\Config;
use Magento\Framework\App\Cache\TypeListInterface;

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
     * @var Config
     */
    protected $config;
    
    /**
     * @var TypeListInterface
     */
    protected $typeList;
    
    /**
     * @var array
     */
    protected $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png', 'svg'];

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param ElementFactory $elementFactory
     * @param GroupFactory $groupFactory
     * @param DateTime $dateTime
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param Config $config
     * @param TypeListInterface $typeList
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        ElementFactory $elementFactory,
        GroupFactory $groupFactory,
        DateTime $dateTime,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        Config $config,
        TypeListInterface $typeList
    ) {
        $this->dateTime = $dateTime;
        $this->uploaderFactory = $uploaderFactory;
        $this->config = $config;
        $this->typeList = $typeList;
        $this->destinationPath = $filesystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath(Image::LAYOUT_IMAGE_DIR . '/');
           
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $elementFactory,
            $groupFactory
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
            
            if (isset($data['image_identifier'])) {
                foreach ($data['image_identifier'] as $imageIdentifier) {
                    if (isset($data[$imageIdentifier]['delete']) && $data[$imageIdentifier]['delete']) {
                        $data[$imageIdentifier] = null;
                    } else {
                        try {
                            $uploader = $this->uploaderFactory->create(['fileId' => $imageIdentifier]);
                            $imageData = $uploader->validateFile();
                            if ($imageData['name'] && $imageData['type'] && $imageData['tmp_name'] && $imageData['size'] > 0) {
                              
                                $uploader->setAllowCreateFolders(true)
                                    ->setAllowCreateFolders(true)
                                    ->setAllowRenameFiles(true)
                                    ->setFilesDispersion(true)
                                    ->setAllowedExtensions($this->allowedExtensions);
                                    
                                $result = $uploader->save($this->destinationPath);
            
                                $data[$imageIdentifier] = $result['file'];
                            } else {
                                unset($data[$imageIdentifier]);
                            }
                        } catch (Exception $e) {
                            // The file was probably not uploaded - skip and continue with model saving
                            unset($data[$imageIdentifier]);
                        }
                    }
                }
            }

            $model->addData($data);
            try {
                $model->save();

                $this->messageManager->addSuccess(__('The element has been saved.'));

                $this->_session->setElementData(false);
                
                if ($this->config->isEnabled()) {
                    $this->typeList->invalidate('BLOCK_HTML');
                }
                
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['element_id' => $model->getId(),
                        '_current' => true],
                        ['error' => false]
                    );
                }
                return $resultRedirect->setPath('*/group/edit', [
                    'entity_id' => $model->getGroupId(),
                    'active_tab' => 'elements'
                ], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setElementData($data);
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['element_id' => $model->getId(),
                    '_current' => true],
                    ['error' => true]
                );
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}
