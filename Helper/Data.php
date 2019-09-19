<?php

namespace OAG\OrderReview\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Holds email config path
     */
    const EMAIL_CONFIG_PATH = 'oag_orderreview/email_config/';

    /**
     * Holds how many emails will send in every cronjob execution
     */
    const MAX_EMAILS_TO_SEND = 50;

    /**
     * Holds encriptor object class
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptor;

    /**
     * construct function
     * @param \Magento\Framework\App\Helper\Context            $context   [description]
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor [description]
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    ) 
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    /**
     * Return email config value
     * @param  string $configName
     * @param  int    $storeId
     * @return mixted
     */
    public function getEmailConfig($configName, $storeId = null, $scopeType = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_CONFIG_PATH . $configName,
            $scopeType,
            $storeId
        );
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * Retrieve url
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route, $params = [])
    {
        return $this->_getUrl($route, $params);
    }

    /**
     * Generate token value
     * @param  string $string
     * @return string
     */
    public function getToken($string)
    {
        return $this->encryptor->getHash($string);
    }

    /**
     * Validate if the string and hash are valids
     * @param  string  $string
     * @param  string  $hash
     * @return boolean
     */
    public function isValidHash($string, $hash)
    {
        return $this->encryptor->isValidHash($string, $hash);
    }

    /**
     * Get max emails to send number
     * @return int
     */
    public function getMaxEmailsToSend()
    {
        return self::MAX_EMAILS_TO_SEND;
    }
}
