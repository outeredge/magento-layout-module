<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Group;

use OuterEdge\Layout\Controller\Adminhtml\Group;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\GroupFactory;
use OuterEdge\Layout\Model\ElementFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Exception;
use Magento\PageCache\Model\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Psr\Log\LoggerInterface;

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
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param GroupFactory $groupFactory
     * @param ElementFactory $elementFactory
     * @param DateTime $dateTime
     * @param Config $config
     * @param TypeListInterface $typeList
     * @param ResourceConnection $resourceConnection
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        GroupFactory $groupFactory,
        ElementFactory $elementFactory,
        DateTime $dateTime,
        Config $config,
        TypeListInterface $typeList,
        ResourceConnection $resourceConnection,
        LoggerInterface $logger
    ) {
        $this->dateTime = $dateTime;
        $this->config = $config;
        $this->typeList = $typeList;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $groupFactory,
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

                    $groupId = (int)$result->getId();
                    $connection = $this->resourceConnection->getConnection();
                    $table = $this->resourceConnection->getTableName('layout_group_store');

                    $connection->delete($table, ['group_id = ?' => $groupId]);

                    foreach ((array)$data['store_ids'] as $store) {
                        if (!is_numeric($store)) {
                            continue;
                        }

                        $connection->insertOnDuplicate(
                            $table,
                            ['group_id' => $groupId, 'store_id' => (int)$store],
                            ['group_id', 'store_id']
                        );
                    }
                }

                //Save show in grid data
                $showInGrid = $this->getRequest()->getPostValue('show_in_grid');
                if (!is_null($showInGrid)) {
                    $groupId = (int)$result->getId();
                    $connection = $this->resourceConnection->getConnection();
                    $table = $this->resourceConnection->getTableName('layout_element_entity');
                    $connection->update(
                        $table,
                        ['show_in_grid' => (int)$showInGrid],
                        ['group_id = ?' => $groupId]
                    );
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
                $this->logger->critical($e);
                $this->messageManager->addError(
                    __('Something went wrong while saving the group. Please review the log for details.')
                );
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
