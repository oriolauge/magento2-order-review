<?php
/**
 * Class that execute all cronjobs for ORder Review Module
 */
namespace OAG\OrderReview\Model;
class Cron
{
    /**
     * Holds Order Collection Factory object class
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * Holds module helper object class
     * @var \OAG\OrderReview\Helper
     */
    protected $helper;

    /**
     * Holds inline translation object class
     * @var \Magento\Framework\Translate\Inline\StateInterface 
     */
    protected $inlineTranslation;

    /**
     * Holds transport buldier object class
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * Holds datetime object class
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * Holds Email Cronjob object class
     * @var OAG\OrderReview\Model\EmailCronjob
     */
    protected $modelEmailCronjob;

    /**
     * construct function class
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \OAG\OrderReview\Helper                                    $helper
     * @param \Magento\Framework\Translate\Inline\StateInterface         $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder          $transportBuilder
     * @param \Magento\Framework\Encryption\EncryptorInterface           $encryptor
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \OAG\OrderReview\Helper\Data $helper,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \OAG\OrderReview\Model\EmailCronjob $modelEmailCronjob
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->helper = $helper;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->dateTime = $dateTime;
        $this->modelEmailCronjob = $modelEmailCronjob;
    }

    /**
     * Function that send an email to order user to give the link to adds his reviews.
     * Also, we will remove the order ID from our email cronjob table to avoid to resend
     * the review email again
     * @return OAG\OrderReview\Model\Cron
     */
    public function sendOrderReviewEmails()
    {
        foreach ($this->getOrdersToSendEmail() as $order) {
            if (!$this->helper->getEmailConfig('enable_cronjob', $order->getStoreId())) {
                continue;
            }
            $this->inlineTranslation->suspend();

            $template = $this->helper->getEmailConfig('email_template', $order->getStoreId());
            $sender = $this->helper->getEmailConfig('sender', $order->getStoreId());
            $token = $this->helper->getToken($order->getIncrementId());

            $vars = array(
                'name' => $order->getCustomerFirstname(),
                'add_review_link' => $this->helper->getUrl('reviews/order/addreview', array(
                    'increment_id' => $order->getIncrementId(),
                    'token' => $token
                ))
            );

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($template)
                ->setTemplateOptions(array(
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $order->getStoreId()
                ))->setTemplateVars($vars)
                ->setFromByScope($sender)
                ->addTo($order->getCustomerEmail())
                ->getTransport();
            try {
                $transport->sendMessage();
                $this->modelEmailCronjob->loadByOrderId($order->getId());
                //Only to be sure that we load the object correctly
                if($this->modelEmailCronjob->getId()) {
                    $this->modelEmailCronjob->delete();
                }
            } catch (\Exception $exception) {
                $this->helper->getLogger()->critical($exception->getMessage());
            }


            $this->inlineTranslation->resume();
        }
        return $this;

    }

    /**
     * Get all orders that we need to send an email with the review link page
     * @return  Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected function getOrdersToSendEmail()
    {
        $orderCollection = $this->orderCollectionFactory
            ->create()
            ->addAttributeToSelect('entity_id')
            ->addAttributeToSelect('customer_email')
            ->addAttributeToSelect('customer_firstname')
            ->addAttributeToSelect('increment_id')
            ->addAttributeToSelect('store_id')
            ->setPageSize($this->helper->getMaxEmailsToSend());

        $daysToWait = (int) $this->helper->getEmailConfig('order_waiting_days', null, \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        if ($daysToWait > 0) {
            $dateTo = $this->dateTime->date(null, '-' . $daysToWait . ' days');
            $orderCollection->addAttributeToFilter('created_at', array('to'=>$dateTo));
        }

        $orderCollection->getSelect()->join(
            array('oag_order_review_email_cronjob'),
            'main_table.entity_id = oag_order_review_email_cronjob.order_id',
            array()
        );
        return $orderCollection;

    }
}

