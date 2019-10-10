<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\GroupFactory;
use OuterEdge\Layout\Model\ElementFactory;
use OuterEdge\Layout\Model\GroupStoreFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Exception;
use Magento\PageCache\Model\Config;
use Magento\Framework\App\Cache\TypeListInterface;

class Save extends Group
{
    /**
     * @var DateTime
     */
    protected $dateTime;
    
    /**
     * @var Config
     */
    protected $config;
    
    /**
     * @var TypeListInterface
     */
    protected $typeList;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param GroupFactory $groupFactory
     * @param ElementFactory $elementFactory
     * @param GroupStoreFactory $groupStoreFactory
     * @param DateTime $dateTime
     * @param Config $config
     * @param TypeListInterface $typeList
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        GroupFactory $groupFactory,
        ElementFactory $elementFactory,
        GroupStoreFactory $groupStoreFactory,
        DateTime $dateTime,
        Config $config,
        TypeListInterface $typeList
    ) {
        $this->dateTime = $dateTime;
        $this->config = $config;
        $this->typeList = $typeList;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $groupFactory,
            $elementFactory,
            $groupStoreFactory
        );
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue('group');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $data['updated_at'] = $this->dateTime->date();

            $model = $this->groupFactory->create();

            $groupId = $this->getRequest()->getParam('entity_id');
           
            if ($groupId) {
                $model->load($groupId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This group no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            } else {
                $data['created_at'] = $this->dateTime->date();
            }

            $model->addData($data);

            try {
                $result = $model->save();
                
                //Save store view
                if (isset($data['store_ids'])) {
                    $modelStore = $this->groupStoreFactory->create();

                    foreach ($data['store_ids'] as $store) {

                        /**
                         * ToDo save is not working, dont know the reason
                         * 
                         */
                        
                        $modelStore->setData([
                            'group_id' => $result->getId(), 
                            'store_id' => $store
                        ]);   
                        $modelStore->save();
                    }
                }

                $this->messageManager->addSuccess(__('The group has been saved.'));

                $this->_session->setGroupData(false);
                
                if ($this->config->isEnabled()) {
                    $this->typeList->invalidate('BLOCK_HTML');
                }

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['entity_id' => $model->getId(), '_current' => true],
                        ['error' => false]
                    );
                }
                return $resultRedirect->setPath('*/*/', [], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setGroupData($data);
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['entity_id' => $model->getId(),
                    '_current' => true],
                    ['error' => true]
                );
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}
