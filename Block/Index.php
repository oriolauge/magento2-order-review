<?php
namespace OAG\OrderReview\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * Holds order review factory object class
     * @var \OAG\OrderReview\Model\OrderReviewFactory
     */
    protected $orderReviewFactory;

    /**
     * construct function
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \OAG\OrderReview\Helper\Data                     $helper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \OAG\OrderReview\Model\OrderReviewFactory $orderReviewFactory
    )
    {        
        $this->orderReviewFactory = $orderReviewFactory;
        parent::__construct($context);
    }

    /**
     * Get all orders reviews to show (verified = 1)
     * @return \OAG\OrderReview\Model\ResourceModel\OrderReview\Collection;
     */
    public function getOrderReviews()
    {
        return $this->orderReviewFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('verified', 1);
    }
}
