<?php
namespace OAG\OrderReview\Block;
class Order extends \Magento\Framework\View\Element\Template
{

    /**
     * Holds module helper object class
     * @var \OAG\OrderReview\Helper
     */
    protected $helper;

    /**
     * construct function
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \OAG\OrderReview\Helper\Data                     $helper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OAG\OrderReview\Helper\Data $helper
    )
    {        
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Get post url
     * @return string
     */
    public function getAddReviewFormAction()
    {
        return $this->helper->getUrl('reviews/order/addreviewpost');
    }
}
