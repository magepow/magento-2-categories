<?php

/**
 * @Author: Alex Dong
 * @Date:   2020-11-20 10:10:23
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-11-20 10:11:05
 */

namespace Magepow\Categories\Model\Config\Source;

class Row implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '1'=>   __('1 row(s) /slider'),
            '2'=>   __('2 row(s) /slider'),
            '3'=>   __('3 row(s) /slider'),
            '4'=>   __('4 row(s) /slider'),
            '5'=>   __('5 row(s) /slider'),
            '6'=>   __('6 row(s) /slider'),
        ];
    }
}
