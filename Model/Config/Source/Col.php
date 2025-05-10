<?php

/**
 * @Author: Alex Dong
 * @Date:   2020-11-20 10:11:22
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-11-20 10:26:28
 */

namespace Magepow\Categories\Model\Config\Source;

class Col implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * base on 12 col bootstrap.
     *
     * @return []
     */

    public function toOptionArray()
    {
        return array(
            array('value' => 1,   'label'=>__('1 item(s) /row')),
            array('value' => 2,   'label'=>__('2 item(s) /row')),
            array('value' => 3,   'label'=>__('3 item(s) /row')),
            array('value' => 4,   'label'=>__('4 item(s) /row')),
            array('value' => 5,   'label'=>__('5 item(s) /row')),
            array('value' => 6,   'label'=>__('6 item(s) /row')),
            array('value' => 7,   'label'=>__('7 item(s) /row')),
            array('value' => 8,   'label'=>__('8 item(s) /row')),
            array('value' => 9,   'label'=>__('9 item(s) /row')),
            array('value' => 10,  'label'=>__('10 item(s) /row')),
            array('value' => 11,  'label'=>__('11 item(s) /row')),
            array('value' => 12,  'label'=>__('12 item(s) /row')),
        );
    }
}
