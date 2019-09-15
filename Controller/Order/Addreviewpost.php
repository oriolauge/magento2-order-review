<?php
namespace OAG\OrderReview\Controller\Order;

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
     * @param Context $context
     * @param ConfigInterface $contactsConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \OAG\OrderReview\Helper\Data $helper

    ) {
        parent::__construct($context, $contactsConfig);
        $this->context = $context;
        $this->formKeyValidator = $formKeyValidator;
        $this->helper = $helper;
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
            $this->validatedParams();
            $this->messageManager->addSuccessMessage(
                __('Thanks for give us your opinion! :)')
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (Exception $e) {
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
            throw new Exception(__('Invalid form key.'), 1);
        }
        if (trim($request->getParam('shipping')) === '') {
            throw new LocalizedException(__('Shipping value is required. Please enter the value and try again.'));
        }
        if (trim($request->getParam('product')) === '') {
            throw new LocalizedException(__('Product value is required. Please enter the value and try again.'));
        }
        if (trim($request->getParam('customsupport')) === '') {
            throw new LocalizedException(__('Customer support value is required. Please enter the value and try again.'));
        }

        return $request->getParams();
    }
}
