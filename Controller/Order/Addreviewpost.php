<?php
namespace OAG\OrderReview\Controller\Order;

use OAG\OrderReview\Model\OrderReview;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;

class Addreviewpost extends \Magento\Contact\Controller\Index implements HttpPostActionInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * Holds Form key validator object class
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * Holds module helper object class
     * @var \OAG\OrderReview\Helper
     */
    protected $helper;

    /**
     * Holds Order Review model object class
     * @var OAG\OrderReview\Model\OrderReview
     */
    protected $modelOrderReview;

    /**
     * Holds Order model object class
     */
    protected $modelOrder;

    /**
     * @param Context $context
     * @param ConfigInterface $contactsConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \OAG\OrderReview\Helper\Data $helper,
        OrderReview $modelOrderReview,
        \Magento\Sales\Model\Order $order

    ) {
        parent::__construct($context, $contactsConfig);
        $this->context = $context;
        $this->formKeyValidator = $formKeyValidator;
        $this->helper = $helper;
        $this->modelOrderReview = $modelOrderReview;
        $this->modelOrder = $order;
    }

    /**
     * Post order review
     *
     * @return Redirect
     */
    public function execute()
    {
        $token = $this->getRequest()->getParam('token');
        $incrementId = $this->getRequest()->getParam('increment_id');

        //Validate token and increment id
        if ($token != $this->helper->getToken($incrementId)) {
            $norouteUrl = $this->helper->getUrl('noroute');
            $this->getResponse()->setRedirect($norouteUrl);
            return;
        }

        if (!$this->getRequest()->isPost()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/addreview', array(
                    'increment_id' => $incrementId,
                    'token' => $token
                )
            );
        }

        try {
            $params = $this->validatedParams();
            $this->modelOrder->loadByIncrementId($incrementId);
            if (!$this->modelOrder->getId()) {
                throw new \Exception(__("Order does not exists"), 1);   
            }
            $this->modelOrderReview->loadByOrderId($this->modelOrder->getId());
            if ($this->modelOrderReview->getId()) {
                throw new LocalizedException(__('Sorry, you have already sent us a review for this order.'));
            }

            $this->modelOrderReview->setOrderId($this->modelOrder->getId());
            $this->modelOrderReview->setShipping((int) $params['shipping']);
            $this->modelOrderReview->setProduct((int) $params['product']);
            $this->modelOrderReview->setCustomerSupport((int) $params['customsupport']);
            if ($params['comment']) {
                $comment = htmlentities($params['comment'], ENT_QUOTES);
                $this->modelOrderReview->setComment($comment);
            }
            $this->modelOrderReview->save();
            $this->messageManager->addSuccessMessage(
                __('Thanks for give us your opinion! :)')
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addError(__('An unspecified error occurred. Please contact us for assistance.'));
        }

        return $this->resultRedirectFactory->create()->setPath('reviews/order/addreview', array(
                'increment_id' => $incrementId,
                'token' => $token
            )
        );
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function validatedParams()
    {
        $request = $this->getRequest();

        if(!$this->formKeyValidator->validate($request)) {
            throw new \Exception(__('Invalid form key.'), 1);
        }
        if (trim($request->getParam('shipping')) === '') {
            throw new LocalizedException(__('Shipping value is required. Please enter the value and try again.'));
        } else if (!is_numeric($request->getParam('shipping')) || $request->getParam('shipping') < 1 || $request->getParam('shipping') > 5) {
            throw new LocalizedException(__('Shipping value has a wrong value. Please enter a numeric valid value.'));
        }
        if (trim($request->getParam('product')) === '') {
            throw new LocalizedException(__('Product value is required. Please enter the value and try again.'));
        } else if (!is_numeric($request->getParam('product')) || $request->getParam('product') < 1 || $request->getParam('product') > 5) {
            throw new LocalizedException(__('Product value has a wrong value. Please enter a numeric valid value.'));
        }
        if (trim($request->getParam('customsupport')) === '') {
            throw new LocalizedException(__('Customer support value is required. Please enter the value and try again.'));
        } else if (!is_numeric($request->getParam('customsupport')) || $request->getParam('customsupport') < 1 || $request->getParam('customsupport') > 5) {
            throw new LocalizedException(__('Customer support value has a wrong value. Please enter a numeric valid value.'));
        }

        return $request->getParams();
    }
}
