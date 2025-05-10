<?php

/**
 * @Author: Alex Dong
 * @Date:   2020-07-14 19:36:33
 * @Last Modified by:   Alex Dong
 * @Last Modified time: 2020-11-20 10:28:02
 */

namespace Magepow\Categories\Model\Config\Source;

class Responsive
{
    public static function getBreakpoints()
    {
        return array(
        	1921	=>'visible', 
        	1920	=>'widescreen', 
        	1480	=>'desktop', 
        	1200	=>'laptop', 
        	992		=>'notebook', 
        	768		=>'tablet', 
        	576		=>'landscape', 
        	481		=>'portrait', 
        	361		=>'mobile', 
        	1		=>'mobile'
        );
    }

}
