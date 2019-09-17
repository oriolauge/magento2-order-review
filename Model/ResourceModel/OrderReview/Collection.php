<?php

namespace OAG\OrderReview\Model\ResourceModel\OrderReview;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Necessary for filters in massActions backoffice
     * @see  OAG\OrderReview\Controller\Adminhtml\Orderreview
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'OAG\OrderReview\Model\OrderReview',
            'OAG\OrderReview\Model\ResourceModel\OrderReview'
        );
    }
}