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
     * Holds secret salt string
     */
    const SECRET_SALT = 'XWMR-oK.k5hU';

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
    public function getEmailConfig($configName, $storeId)
    {
        return $this->scopeConfig->getValue(
            self::EMAIL_CONFIG_PATH . $configName,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
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
        return $this->encryptor->getHash(gzcompress($string . self::SECRET_SALT));
    }
}
