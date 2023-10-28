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
    protected function _construct()
    {
        $breakpoints = $this->getResponsiveBreakpoints();
        $total = count($breakpoints);
        $responsive = '[';
        foreach ($breakpoints as $size => $screen) {
            if ($this->getData($screen)){
                $responsive .= '{"breakpoint": '.$size.', "settings": {"slidesToShow": '.$this->getData($screen).'}}';
            }
            if($total-- > 1) $responsive .= ', ';
        }
        $responsive .= ']';
        $data['responsive'] = $responsive;
        $data['autoplay-Speed'] = $this->getData('autoplaySpeed');
        $data['slides-To-Show'] = $this->getData('visible');
        // $data['swipe-To-Slide'] = 'true';
        $data['vertical-Swiping'] = $this->getData('vertical');
        $data['slide'] = 1;
        //$data['lazy-Load'] = 'progressive';
        $this->addData($data);
        parent::_construct();
    }
    
    public function getCacheKeyInfo()
    {
        $keyInfo     =  parent::getCacheKeyInfo();
        $uniqueId    =  $this->getUniqueId();
        $keyInfo[]   =  $uniqueId;
        return $keyInfo;
    }

    public function getUniqueId()
    {
        $categories = $this->getData('categories');
        $categories = str_replace(" ", "", $categories);
        $categories = str_replace(",", "_", $categories);
        return $categories;
    }

    public function isShowThumbnail(){
        return $this->getData('thumbnail');
    }
    
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

    public function getItemAmount(){
        return $this->getData('item_amount');
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

        $attributesSelect = ['name', 'url_key', 'url_path', 'image','description'];
        if($this->isShowThumbnail()) $attributesSelect[] = 'magepow_thumbnail';
        $categories = $this->categoryFactory->create();    
        $categories = $categories->getCollection()
                            ->addAttributeToSelect($attributesSelect)
                            ->addIdFilter($categoryIds);

        if($sortAttribute == "position") {
            $categories->addAttributeToSort('level');
        }elseif($sortAttribute=='custom'){
            $custom_sort= $this->getData('custom_sort');
            if(preg_match('/^(\d+,)+\d+$/', (string) $custom_sort)){
                $custom_sort_arr = [];
                $categoryIds = explode(',', (string) $categoryIds);
                $custom_sort_arr = explode(',',(string) $custom_sort);
                $custom_sort_arr2 = array_merge(array_diff($custom_sort_arr, $categoryIds), array_diff($categoryIds,$custom_sort_arr));
                $custom_sort_arr = array_merge($custom_sort_arr,$custom_sort_arr2);
                $categories->getSelect()->order(new \Zend_Db_Expr('FIELD(entity_id,' . implode(',', $custom_sort_arr).')'));
            }else{
                $categories->addAttributeToSort('level');
            }
        }else{
            $categories->addAttributeToSort($sortAttribute);
        }

        return $categories;
    }

    public function getSlideOptions()
    {
        return array('autoplay', 'arrows', 'autoplay-Speed', 'dots', 'infinite', 'padding', 'vertical', 'vertical-Swiping', 'responsive', 'rows', 'slides-To-Show');
    }

    public function getFrontendCfg()
    { 
        if($this->getSlide()) return $this->getSlideOptions();

        $this->addData(array('responsive' =>json_encode($this->getGridOptions())));
        return array('padding', 'responsive');

    }

    public function getGridOptions()
    {
        $options = array();
        $breakpoints = $this->getResponsiveBreakpoints(); ksort($breakpoints);
        foreach ($breakpoints as $size => $screen) {
            $options[]= array($size-1 => $this->getData($screen));
        }
        return $options;
    }
    
}
