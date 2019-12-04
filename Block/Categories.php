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

namespace Magepow\Categories\Block;

class Categories extends \Magento\Framework\View\Element\Template
{
    const XML_PATH = 'category_page'; 
    
    public $helper;

    public $storeManager;

    public $viewAssetRepo;
    
    public $coreRegistry;

    public $categoryFactory;

    public $catalogHelperOutput;
    
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $viewAssetRepo,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Output $catalogHelperOutput,
        \Magepow\Categories\Helper\Data $helper,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {    
        $this->storeManager        = $storeManager;
        $this->viewAssetRepo       = $viewAssetRepo;
        $this->coreRegistry        = $coreRegistry;
        $this->categoryFactory     = $categoryFactory;
        $this->catalogHelperOutput = $catalogHelperOutput;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

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

    public function getExcludeCategory()
    {
        return $this->helper->getConfig(self::XML_PATH . '/exclude_category');
    }

    public function getCategories()
    {

        $category = $this->coreRegistry->registry('current_category');
        if(!$category) return;

        $categoryId = $category->getId();

        if ($this->isExcluded($categoryId)) return;

        $sortAttribute = $this->getSortAttribute();  
        $model = $this->categoryFactory->create();
        $categories = $model->getCollection()
        ->addAttributeToSelect(['name', 'url_key', 'url_path', 'image','description'])
        // ->addAttributeToFilter('include_in_menu', 1)
        ->addAttributeToFilter('parent_id', $categoryId)
        ->addAttributeToSort($sortAttribute)
        ->addIsActiveFilter();

        return $categories;
    }

    public function getDescription($category)
    {
        $description = $category->getDescription();
        if ($description) {
            $categoryDescription = $this->catalogHelperOutput
            ->categoryAttribute($category, $description, 'description');
        } else {
            $categoryDescription = '';
        }
        return trim($categoryDescription);
    }

    public function getImage($category)
    {
        $placeholderImageUrl = $this->viewAssetRepo->getUrl(
            'Magento_Catalog::images/product/placeholder/small_image.jpg'
        );
        $image = $category->getImage();
        if ($image != null) {
            $url = $this->getImageUrl($image);
        } else {
            $url = $placeholderImageUrl;
        }  
        return $url;
    }
    
    public function getImageUrl($image)
    {
        $url = false;  
        if ($image) {
            if (substr($image, 0, 1) === '/') {
                $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_WEB
                ) . ltrim($image, '/');
            } else {
                $url = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/category/' . $image;
            }
        } 
        return $url;
    }
    
    public function isExcluded($id)
    {
        $excluded = explode(',', $this->getExcludeCategory());
        if (!$excluded) return;
        return in_array($id, $excluded);
    }
}