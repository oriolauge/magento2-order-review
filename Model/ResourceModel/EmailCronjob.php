<?php

namespace OAG\OrderReview\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class EmailCronjob extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('oag_order_review_email_cronjob', 'id');
    }
}