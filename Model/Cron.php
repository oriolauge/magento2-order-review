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
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->helper = $helper;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * Function that send an email to order user to give the link to adds his reviews
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
            } catch (\Exception $exception) {
                $this->helper->getLogger()->critical($exception->getMessage());
            }


            $this->inlineTranslation->resume();
        }
        return $this;

    }

    /**
     * Get all orders that we need to send an email with the review link page
     * @todo : get orders that we not send the email
     * @return  Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected function getOrdersToSendEmail()
    {
        return $this->orderCollectionFactory
            ->create()
            ->addAttributeToSelect('customer_email')
            ->addAttributeToSelect('customer_firstname')
            ->addAttributeToSelect('increment_id')
            ->addAttributeToSelect('store_id');
    }
}

