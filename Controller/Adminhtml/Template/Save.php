<?php

namespace OuterEdge\Layout\Controller\Adminhtml\Template;

use OuterEdge\Layout\Controller\Adminhtml\Template;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use OuterEdge\Layout\Model\TemplateFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Exception;

class Save extends Template
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param TemplateFactory $templateFactory
     * @param DateTime $dateTime
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        TemplateFactory $templateFactory,
        DateTime $dateTime
    ) {
        $this->dateTime = $dateTime;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultPageFactory,
            $templateFactory
        );
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue('template');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $data['updated_at'] = $this->dateTime->date();

            $model = $this->templateFactory->create();

            $templateId = $this->getRequest()->getParam('entity_id');
           
            if ($templateId) {
                $model->load($templateId);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This Template no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            } else {
                $data['created_at'] = $this->dateTime->date();
            }

            $model->addData($data);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The Template has been saved.'));

                $this->_session->setTemplateData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['template_id' => $model->getId(),
                        '_current' => true],
                        ['error' => false]
                    );
                }
                return $resultRedirect->setPath('*/*/', [], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setTemplateData($data);
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['template_id' => $model->getId(),
                    '_current' => true],
                    ['error' => true]
                );
            }
        }
        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}
