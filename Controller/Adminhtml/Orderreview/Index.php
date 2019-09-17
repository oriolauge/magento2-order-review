<?php

namespace OAG\OrderReview\Controller\Adminhtml\Orderreview;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    /**
     * Contruct function
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Listing all order reviews in backoffice
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Order Reviews')));
        return $resultPage;
    }


}