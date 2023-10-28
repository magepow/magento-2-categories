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

class Categories extends \Magento\Framework\View\Element\Template implements \Magento\Framework\DataObject\IdentityInterface
{
    const DEFAULT_CACHE_TAG = 'MAGEPOW_CATEGORIES';

    const XML_PATH = 'category_page';

    const MEDIA_PATH = 'catalog/category';
    
    public $helper;

    public $helperImage;

    public $storeManager;

    public $viewAssetRepo;
    
    public $coreRegistry;

    public $categoryFactory;

    public $catalogHelperOutput;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $_imageFactory;

    protected $_filesystem;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Image $helperImage,
        \Magento\Catalog\Helper\Output $catalogHelperOutput,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magepow\Categories\Helper\Data $helper,
        array $data = []
    ) {    
        $this->storeManager        = $storeManager;
        $this->coreRegistry        = $coreRegistry;
        $this->categoryFactory     = $categoryFactory;
        $this->catalogHelperOutput = $catalogHelperOutput;
        $this->helperImage         = $helperImage;
        $this->_imageFactory       = $imageFactory;
        $this->_filesystem         = $context->getFilesystem();
        $this->_directoryPub       = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::PUB);
        $this->helper              = $helper;

        parent::__construct($context, $data);
    }

    protected function getCacheLifetime()
    {
        return parent::getCacheLifetime() ?: 86400;
    }

    public function getCacheKeyInfo()
    {
        $keyInfo     =  parent::getCacheKeyInfo();
        $categoryId  =  $this->getCurrentCategory() ? $this->getCurrentCategory()->getId() : 0;
        $keyInfo[]   =  $categoryId;
        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        $categoryId  = $this->getCurrentCategory() ? $this->getCurrentCategory()->getId() : 0;
        return [self::DEFAULT_CACHE_TAG, self::DEFAULT_CACHE_TAG . '_' . $categoryId];
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

    public function getExcludeCategory()
    {
        return $this->helper->getConfig(self::XML_PATH . '/exclude_category');
    }

    public function getCurrentCategory()
    {
        return $this->coreRegistry->registry('current_category');
    }

    public function getCategories()
    {
        $category = $this->getCurrentCategory();
        if(!$category) return;

        $categoryId = $category->getId();

        if ($this->isExcluded($categoryId)) return;

        $sortAttribute = $this->getSortAttribute();
        $attributesSelect = ['name', 'url_key', 'url_path', 'image','description'];
        if($this->isShowThumbnail()) $attributesSelect[] = 'magepow_thumbnail';
        $categories = $this->categoryFactory->create()->getCollection()
                            ->addAttributeToSelect($attributesSelect)
                            ->addAttributeToFilter('parent_id', $categoryId)
                            ->addIsActiveFilter();

        if($sortAttribute == "position") {
            $categories->addAttributeToSort('level');
        }

        $categories->addAttributeToSort($sortAttribute);

        return $categories;
    }

    public function getDescription($category)
    {
        $description = $category->getDescription();
        if ($description) {
            $categoryDescription = $this->catalogHelperOutput->categoryAttribute($category, $description, 'description');
        } else {
            $categoryDescription = '';
        }
        return trim($categoryDescription);
    }

    public function getImage($category)
    {
        if($this->isShowThumbnail()){
            $image = ($category->getData('magepow_thumbnail')) ? $category->getData('magepow_thumbnail') : '';
            return $this->getImageUrl($image);
        }

        return $this->getImageUrl($category);
    }

    public function getImageInfo($image)
    {
        if(is_object($image)){
            $img = $this->isShowThumbnail() ? $image->getData('magepow_thumbnail'): $image->getImage();
            if(!$img) return $image;
        } else {
            $img = $image;
        }
        $_image  = $this->_imageFactory->create();
        $mediaPath = $this->_directoryPub->getAbsolutePath();
        $mediaPath = explode(DIRECTORY_SEPARATOR, $mediaPath);
        $img = explode(DIRECTORY_SEPARATOR, $img);
        $imagePath = array_unique(array_merge($mediaPath, $img));
        $imagePath = implode(DIRECTORY_SEPARATOR, $imagePath);
        if(file_exists($imagePath) ){
            $_image->open($imagePath);
            return $_image;
        }
        return $image;
    }

    public function getImageUrl($image)
    {
        if(is_object($image)){
            $image = $image->getImage();
        }
        if(strpos($image  ?? '', "media/"))$image = strstr($image,'/media');
        elseif($image!=NULL){
            $image = 'catalog/category/'.$image;
        }
        $image = str_replace('media/', '',  $image ?? '');

        if($image) {
            $url = $this->storeManager->getStore()->getBaseUrl( \Magento\Framework\UrlInterface::URL_TYPE_MEDIA ) . $image;
        } else {
            $url = $this->helperImage->getDefaultPlaceholderUrl('small_image');
        }

        return $url;
    }
    
    public function isExcluded($id)
    {
        $excluded = explode(',', $this->getExcludeCategory() ?? '');
        if (!$excluded) return;
        return in_array($id, $excluded);
    }

    public function getResponsiveBreakpoints()
    {
        return array(1921 => 'visible', 1920 => 'widescreen', 1479 => 'desktop', 1200 => 'laptop', 992 => 'notebook', 768 => 'tablet', 576 => 'landscape', 480 => 'portrait', 361 => 'mobile', 1 => 'mobile');
    }

}
