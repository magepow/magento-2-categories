<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-11-29 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-12-04 11:06:31
 */

namespace Magepow\Categories\Model\Config;

use Magento\Framework\Option\ArrayInterface;

class SortAttribute implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'name', 'label' => __('Name')],
            ['value' => 'meta_title', 'label' => __('Page Title')],
            ['value' => 'position', 'label' => __('Position')],
            ['value' => 'created_at', 'label' => __('Created Date')]
        ];
    }
}
