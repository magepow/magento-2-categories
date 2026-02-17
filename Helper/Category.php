<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magepow\Categories\Helper;

/**
 * Categories category helper
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Category extends \Magento\Catalog\Helper\Category
{

    public function getCategory($categoryId)
    {
        return $this->categoryRepository->get($categoryId);
    }
  
    public function getCategories($id)
    {
        return $this->_categoryFactory->create()->getCategories($id);
    }
    
}
