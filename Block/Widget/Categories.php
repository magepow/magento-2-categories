<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-11-29 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-12-04 11:04:26
 */

namespace Magepow\Categories\Block\Widget;

class Categories extends \Magepow\Categories\Block\Categories implements \Magento\Widget\Block\BlockInterface
{

    public function getLayout() 
    {
        return 'grid';
    }

    public function getHeading() 
    {
        return $this->getData('title');
    }    

    public function isShowDescription() 
    {
        return $this->getData('description');
    }    

    public function getSortAttribute() 
    {
        return $this->getData('sort_attribute');
    } 

    public function getCategories()
    {

        $categoryIds = $this->getData('categories');
        if(!$categoryIds) return;
        $sortAttribute = $this->getSortAttribute();
        $model = $this->categoryFactory->create();      
        $categories = $model->getCollection()
        ->addAttributeToSelect(['name', 'url_key', 'url_path', 'image', 'description'])
        ->addAttributeToSort($sortAttribute)
        ->addIdFilter($categoryIds)
        ->addIsActiveFilter();

        return $categories;
    }

}
