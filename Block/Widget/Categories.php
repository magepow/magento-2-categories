<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Magepow\Categories\Block\Widget;

/**
 * Blog sidebar categories block
 */
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
