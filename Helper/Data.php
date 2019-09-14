<?php

namespace OAG\OrderReview\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Holds email config path
     */
    const EMAIL_CONFIG_PATH = 'oag_orderreview/email/';

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
}
