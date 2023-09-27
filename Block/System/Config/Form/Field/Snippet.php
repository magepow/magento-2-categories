<?php

/**
 * @Author: Alex Dong
 * @Date:   2023-09-27 14:42:40
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2023-09-27 14:42:56
 */

namespace Magepow\Categories\Block\System\Config\Form\Field;

class Snippet extends \Magento\Config\Block\System\Config\Form\Field\Heading
{

    /**
     * Render element html
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_getElementHtml($element);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        // $html = $element->getElementHtml();
        $html  = '';
        $value = $element->getData('value');
        $shortcodeWidget = '{{block class="Magepow\Categories\Block\Widget\Categories" title="Magepow Categories Widget" subtitle="Magepow Categories Widget" template="categories_widget.phtml" sort_attribute="name" description="1" thumbnail="1" item_amount="1" categories="25,11,22,33" slide="1" vertical="false" infinite="true" autoplay="true" arrows="true" dots="false" speed="300" autoplaySpeed="3000" padding="15" rows="1" mobile="1" portrait="1" landscape="2" tablet="3" notebook="3" laptop="4" desktop="4" widescreen="4" visible="5"}}';

        $html = '<ul class="categories-snippet" style="list-style: none;"><li>';
        $html .= '<p>' . __('Add Widget name "Magiccart Magicslider widget" and set categorie for it.') . '</p>';
        $html .= '<p>' . __('Example: categories="1,5,10,12"') . '</p>';
        $html .= '</li><li>';
        $html .= '<span>' . __('Copy Short code add to CMS Page/Static Block.') . '</span>';
        $html .= '<code style="display: block;background: #f5f5f5;padding: 15px; margin-top:15px">' . $shortcodeWidget . '</code>';
        $html .= '</li></ul>';

        return $html;
    }
}
