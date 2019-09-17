<?php

namespace OAG\OrderReview\Controller\Adminhtml\Orderreview;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Ui\Component\MassAction\Filter;
use OAG\OrderReview\Model\ResourceModel\OrderReview\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

class MassVerified extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    /**
     * MassActions filter
     *
     * @var Filter
     */
    protected $filter;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Construct function
     * @param \Magento\Backend\App\Action\Context $context
     * @param Filter                              $filter
     * @param CollectionFactory                   $collectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    )
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Mass Verified change value function
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        if ($collection->getSize() > 0) {
            $verified = $this->getRequest()->getParam('verified') ? 1 : 0;
            try {
                foreach ($collection as $orderReview) {
                    $orderReview->setVerified($verified);
                    $orderReview->save();
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been updated.', $collection->getSize())
                );
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while updating the order review(s) verified value.')
                );
            }
        } else {
            $this->messageManager->addNoticeMessage(__('Nothing updated.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }


}