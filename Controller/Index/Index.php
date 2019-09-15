<?php
namespace OAG\OrderReview\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Holds page factory object class
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        echo "Hello World Index";
        exit;
    }
}

