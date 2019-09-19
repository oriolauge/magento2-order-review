<?php

namespace OAG\OrderReview\Observer;
class SalesModelServiceQuoteSubmitSuccess implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * Holds module helper object class
     * @var \OAG\OrderReview\Helper
     */
    protected $helper;

    /**
     * Holds Email Cronjob object class
     * @var OAG\OrderReview\Model\EmailCronjob
     */
    protected $modelEmailCronjob;

    /**
     * Construct function
     * @param \OAG\OrderReview\Helper\Data $helper [description]
     */
    public function __construct(
        \OAG\OrderReview\Helper\Data $helper,
        \OAG\OrderReview\Model\EmailCronjob $modelEmailCronjob
    ) { 
        $this->helper = $helper;
        $this->modelEmailCronjob = $modelEmailCronjob;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $this->modelEmailCronjob->setOrderId($order->getId());
        $this->modelEmailCronjob->save();
        return $this;
    }
}