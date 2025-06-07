<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (https://www.magepow.com/)
 * @licence: https://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-11-29 17:19:50
 * @LastEditors: Alex
 * @LastEditTime: 2025-6-07 11:05:42
 */

namespace Magepow\Categories\Model\Config;

class Category implements \Magento\Framework\Option\ArrayInterface
{

    const PREFIX_ROOT = '&nbsp;&nbsp;';    
    const REPEATER = '&nbsp;&nbsp;';
    const PREFIX_END = '';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Block\Adminhtml\Category\Tree
     */
    protected $categoryTree;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    protected $options = [];

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Block\Adminhtml\Category\Tree $categoryTree
    )
    {
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->categoryTree = $categoryTree;
    }
 
    public function toOptionArray()
    {
        if(!$this->options){
            $store = $this->request->getParam('store');
            $categories = $this->categoryTree->getTree();
            $options = [['label' => __('All'), 'value' => '0']];
            foreach ($categories as $category) {
                $this->options = [];
                if(isset($category['cls']) && str_contains($category['cls'] , 'no-active')) continue;
                if($category['id']) {
                    $this->options[] = ['label' => __('All in "%1"', $category['text']), 'value' => $category['id']];
                    $_categories = isset($category['children']) ? $category['children'] : '' ;
                    if($_categories){
                        // $rootOption = ['label' => $category['label']];
                        foreach ($_categories as $cat) {
                            $this->options[] = [
                                'label' => self::PREFIX_ROOT .$cat['text'],
                                'value' => $cat['id']
                            ];
                            if(isset($cat['cls']) && str_contains($cat['cls'] , 'no-active')) continue;
                            if (isset($cat['children'])) $this->_getChildOptions($cat['children']);
                        }
                        // $rootOption['value'] = $this->options;
                        // $options[] = $rootOption;
                        if($this->options){
                            $options[] = [
                                'label' => $category['text'],
                                'value' => $this->options
                            ];
                        }
                    }
                }
            }
            $this->options = $options;
        }
        return $this->options;
    }
 
    protected function _getChildOptions($categories)
    {
        foreach ($categories as $category) {
            $prefix = str_repeat(self::REPEATER, count(explode("/", $category['path'])) * 1) . self::PREFIX_END;
            $this->options[] = [
                'label' => $prefix . $category['text'],
                'value' => $category['id']
            ];
            if(isset($category['cls']) && str_contains($category['cls'] , 'no-active')) continue;
            if (isset($category['children'])) $this->_getChildOptions($category['children']);
        }
    }

}