<?php

namespace OAG\OrderReview\Model;
use Magento\Framework\Model\AbstractModel;

class EmailCronjob extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('OAG\OrderReview\Model\ResourceModel\EmailCronjob');
    }

    /**
     * Load order review by order id
     *
     * @param string $orderId
     * @return \OAG\OrderReview\Model\OrderReview
     */
    public function loadByOrderId($orderId)
    {
        $this->load($orderId, 'order_id');
        return $this;
    }
}