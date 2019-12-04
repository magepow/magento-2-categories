<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-11-29 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-12-04 11:05:37
 */

namespace Magepow\Categories\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $scopeConfig;

    const XML_PATH_MAGEPOW = 'magepow_categories/';
    
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {   
        $this->scopeConfig         = $context->getScopeConfig();  
        parent::__construct($context);
    }

    public function getConfig($path)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAGEPOW . $path, 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}