<?php
/*
Plugin Name: Beautiful Social Web Link
Plugin URI: http://howitworkz.com/wordpress/beautiful-social-web-link/
Author URI: http://howitworkz.com/wordpress/beautiful-social-web-link/
Version: 4.2
Author:Dennys
Description: Plugin to display social bookmarks on sidebar or content.
*/
/*  Copyright 2010

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Path for the images */
/**********************/
define('ICON_URL', get_option('siteurl').'/wp-content/plugins/beautiful-social-web-link/images/');
//echo ICON_URL;

/* Function to get the icons */
/****************************/
function fetch_icons_for_options() {
    $social_icons = array();
    //echo getcwd();
    if ($handle = opendir('../wp-content/plugins/beautiful-social-web-link/images/16x16/')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && (preg_match('/.png/',$file) == 1 || preg_match('/.jpg/',$file) == 1 ||preg_match('/.gif/',$file) == 1)) {
                array_push($social_icons,$file);
            }
        }
    }
    return $social_icons;
}

function fetch_icons_for_site() {
    $dimension = get_option('social_icon_size').'x'.get_option('social_icon_size');
    $social_icons = array();
    //echo getcwd();
    $siteurl =  get_bloginfo('url');
    $wpinstallation = get_bloginfo('wpurl');
    if($siteurl != $wpinstallation) {
        $installdir = str_replace($siteurl."/","",$wpinstallation);
        $installdir = $installdir."/";
    }
    if ($handle = opendir($installdir.'wp-content/plugins/beautiful-social-web-link/images/'.$dimension.'/')) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && (preg_match('/.png/',$file) == 1 || preg_match('/.jpg/',$file) == 1 ||preg_match('/.gif/',$file) == 1)) {
                array_push($social_icons,$file);
            }
        }
    }
    return $social_icons;
}

/* Function to display the icons */
/*********************************/
function display_icons($args) {
    extract($args);
    $dimension = get_option('social_icon_size').'x'.get_option('social_icon_size');
    if(get_option('social_display_style') == 'double') { $classname = "social-icon-double-vertical"; }
    elseif(get_option('social_display_style') == 'single') { $classname = "social-icon-single-vertical"; }
    else { $classname = "social-icon-horizontal"; }
    $class = ' class="'.$classname.'"';
    $effectclass = ' class="';
    if(get_option('social_hover_effect')) {
        $effectclass .= 'social-icon-img-effect';
    }
    $effectclass .= '"';
    $social_icons = fetch_icons_for_site();
    //$social_link .= '<li id="beautiful-social-web-link">';
    foreach($social_icons as $icon) {
        $nameurl = "";
        $icon_info = explode(".",$icon);
        $icon_url = $icon_info[0]."_url";
        if(trim(get_option($icon_url)) != "") {
            if(get_option('social_name_display')) { $nameurl = '&nbsp;&nbsp;'.ucwords($icon_info[0]); }
            $social_link .= '<div'.$class.'><a href="'.get_option($icon_url).'" target="_blank" title="'.$icon_info[0].'"><img style="vertical-align: middle;"'.$effectclass.' src="'.ICON_URL.$dimension.'/'.$icon.'" />'.$nameurl.'</a></div>';
        }
    }
    $social_link .= '<div style="clear: both; height: 0px;"></div>';
    
    echo $before_widget;
    echo $before_title;  
    echo get_option('social_web_link_title');  
    echo $after_title;
    echo $social_link;
    echo $after_widget;
}

/* Function to display the options page */
/****************************************/
function display_options() {
    include('beautiful-social-web-link-sidebar-options.php');
}

// ** Adding the Options page to the plugin options **//
//****************************************************//
function add_social_web_links_options()
{
  add_options_page('Social Web Link Options', 'Beautiful Social Web Link', 8, __FILE__, 'display_options');
}

/* Adding header files */
/***********************/
function add_social_web_links_css() {
    echo '<link rel="stylesheet" href="'.get_bloginfo('wpurl').'/wp-content/plugins/beautiful-social-web-link/beautiful-social-web-link-sidebar-style.css" type="text/css" media="screen" />'."\n";
}

/* Registering the widget */
/* updates in the plugins goes here */
/*************************/
function social_links_widget() {
    register_sidebar_widget('Beautiful Social Web Link','display_icons');
}

add_action('wp_head','add_social_web_links_css');
add_action('admin_menu', 'add_social_web_links_options');
add_action('plugins_loaded', 'social_links_widget');
?>
