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

    const DEFAULT_CACHE_TAG = 'MAGEPOW_CATEGORIES_CMS';

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

    public function isShowThumbnail() 
    {
        return $this->helper->getConfig(self::XML_PATH . '/thumbnail');
    } 

    public function getItemAmount() 
    {
        return $this->helper->getConfig(self::XML_PATH . '/item_amount');
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
        $categories = $this->categoryFactory->create()->getCollection()
                            ->addAttributeToSelect(['name', 'url_key', 'url_path', 'image', 'description'])
                            ->addIdFilter($categoryIds)
                            ->addIsActiveFilter();

        if($sortAttribute == "position") {
            $categories->addAttributeToSort('level');
        }

        $categories->addAttributeToSort($sortAttribute);

        return $categories;
    }
}