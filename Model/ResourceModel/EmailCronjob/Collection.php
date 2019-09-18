<?php

namespace OAG\OrderReview\Model\ResourceModel\EmailCronjob;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'OAG\OrderReview\Model\EmailCronjob',
            'OAG\OrderReview\Model\ResourceModel\EmailCronjob'
        );
    }
}