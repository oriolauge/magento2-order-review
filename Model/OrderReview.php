<?php

namespace OAG\OrderReview\Model;
use Magento\Framework\Model\AbstractModel;

class OrderReview extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('OAG\OrderReview\Model\ResourceModel\OrderReview');
    }
}