<?php
/**
 * Copyright © 2017 MGT-Commerce GmbH. All rights reserved.
 *
 * @category    Mgt
 * @package     Mgt_Varnish
 * @copyright   Copyright (c) 2017 (https://www.mgt-commerce.com)
 */

namespace Mgt\Varnish\Model;

class License
{
    const MODULE_NAME = 'Mgt_Varnish';

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $dirReader;

    /**
     * @var array
     */
    protected $domains = [];

    public function __construct()
    {
        $objectManager = $this->getObjectManager();
        $this->dirReader = $objectManager->get('\Magento\Framework\Module\Dir\Reader');
    }

    public function hasLicense($currentHost)
    {
        return true;
        $hasLicense = false;
        $licenseFile = $this->dirReader->getModuleDir('', self::MODULE_NAME).'/license.mgt';
        if (true === file_exists($licenseFile)) {
            eval(gzinflate(base64_decode(file_get_contents($licenseFile))));
            $currentHost = str_replace('www.','',$currentHost);
            $today = new \DateTime();
            if (isset($license['expireDate']) && $license['expireDate']) {
                $expireDate = new \Zend_Date();
                $expireDate->setTimestamp($license['expireDate']);
            }
            $isHostValid = $this->_isHostValid($currentHost, $license['domains']);
            if (is_array($license) && $license['module'] == self::MODULE_NAME && $isHostValid && (!$license['expireDate'] || $license['expireDate'] && $expireDate->isLater(Zend_Date::now()))) {
                $hasLicense = true;
            }
        }
        return $hasLicense;
    }

    protected function _isHostValid($currentHost, array $domains)
    {
        $isHostValid = false;
        foreach ($domains as $domain) {
            if (strstr($currentHost, $domain)) {
                $isHostValid = true;
                break;
            }
        }
        return $isHostValid;
    }

    public function getDomains()
    {
        $domains = [];
        $licenseFile = $this->dirReader->getModuleDir('', self::MODULE_NAME).'/license.mgt';
        if (true === file_exists($licenseFile)) {
            eval(gzinflate(base64_decode(file_get_contents($licenseFile))));
            if (isset($license['domains']) && $license['domains']) {
                $domains = $license['domains'];
            }
        }
        return $domains;
    }

    protected function getObjectManager()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager;
    }
}