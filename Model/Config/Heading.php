<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-11-29 17:19:50
 * @LastEditors: ProxiBlue
 * @LastEditTime: 2024-01-23
 */

namespace Magepow\Categories\Model\Config;

use Magento\Framework\Exception\LocalizedException;

class Heading extends \Magento\Framework\App\Config\Value
{
    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $validator = new \Laminas\Validator\Regex(
            ['pattern' => '/^[\p{L}\p{N}_,;:!&#\+\*\$\?\|\'\.\-\ ]*$/iu']
        );
        
        if (!$validator->isValid($value)) {
            $message = __(
                'Please correct categories heading: "%1".',
                $value
            );
            throw new LocalizedException($message);
        }
        
        return $this;
    }
}
