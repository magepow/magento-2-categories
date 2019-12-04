<?php
/*
 * @category: Magepow
 * @copyright: Copyright (c) 2014 Magepow (http://www.magepow.com/)
 * @licence: http://www.magepow.com/license-agreement
 * @author: MichaelHa
 * @create date: 2019-11-29 17:19:50
 * @LastEditors: MichaelHa
 * @LastEditTime: 2019-12-04 11:04:52
 */

namespace Magepow\Categories\Block;

class Cmspage extends Categories
{
    const XML_PATH = 'home_page';

    public function getLayout() 
    {
        return $this->helper->getConfig(self::XML_PATH . '/layout');
    }

    public function getHeading() 
    {
        return $this->helper->getConfig(self::XML_PATH . '/heading');
    }    

    public function isShowDescription() 
    {
        return $this->helper->getConfig(self::XML_PATH . '/description');
    }    

    public function getSortAttribute() 
    {
        return $this->helper->getConfig(self::XML_PATH . '/sort_attribute');
    } 

    public function getCategorySelect()
    {
        return $this->helper->getConfig(self::XML_PATH . '/category_select');
    } 

    public function getCategories()
    {
        $categoryIds = $this->getCategorySelect();
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