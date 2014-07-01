<?php
/**
 * @package Slide Tweener
 * @version 1.0
 */
/*
Plugin Name: Side Tweener
Plugin URI: http://websitedesignby.com/wp/slide-tweener/
Description: Create dynamic transitions between images and text. Define a background-image, text and title for each transition.
Author: Ross Sabes
Version: 1.6
Author URI: http://rsabes.com/
License: GPL2

    Copyright 2011  Ross Sabes  (email : ross@websitedesignby.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function slide_tweener_setup(){
    // add_action('wp_head', array('SlideTweener', 'do_test'));
    add_action('wp_head', array('SlideTweener', 'get_header'));

}

// setup admin options
// add_action('admin_menu', array('SlideTweener', 'plugin_menu'));
// add_action( 'admin_menu', array ('SlideTweener', 'add_submenu') );
// setup menu

// add_menu_page('Page title', 'Top-level menu title', 'manage_options', 'my-top-level-handle', 'my_magic_function');
// add_action('wp_head', array('SlideTweener', 'get_header'));

class SlideTweener {

    var $tweener_id = 0;
    var $slides     = array();
    var $tweener    = array();

    function __construct($tweener_id=0){
        if($tweener_id > 0){
            $this->tweener_id = $tweener_id;
            $this->getTweener($tweener_id);
        }
    }
	

    function plugin_menu() {
		add_options_page('Slide Tweener Options', 'Slide Tweener', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
    }

    function add_submenu(){
        add_submenu_page("themes.php", "Slide Tweener", "Slide Tweener", "Administrator", "slide-tweener");
    }

	
	
    function do_test(){
        echo "<script language=\"javascript\">alert('test');</script>";
    }
    function test(){
        echo "<h1>TEST FROM SLIDE TWEENER</h1>";
    }

    function getTweener($tweener_id = 0){
        if($tweener_id > 0){
            $this->tweener_id = $tweener_id;
            global $wpdb;
            $sql = "SELECT * FROM ".$wpdb->prefix."tweener WHERE tweener_id = $this->tweener_id";
            $myrows = $wpdb->get_row($sql);
            $this->tweener = $myrows;
            $this->tweenerGetSlides($this->tweener_id);
            return $myrows;
        }
    }

    function tweenerGetSlides($tweener_id = 0){
        if($tweener_id > 0){
            global $wpdb;
            $sql = "SELECT ts.*, p.*, ts.post_id as post_id, t.fadetime1 as fadetime1_tweener, t.fadetime2 as fadetime2_tweener, t.slide_delay as slide_delay_tweener, g.path, p.alttext as picture_title, p.description as picture_description FROM ". $wpdb->prefix."tweener_slides ts INNER JOIN ".$wpdb->prefix."ngg_pictures p ON ts.pid = p.pid INNER JOIN ".$wpdb->prefix."tweener t ON t.tweener_id = ts.tweener_id INNER JOIN ".$wpdb->prefix."ngg_gallery g ON g.gid=t.gallery_id WHERE ts.tweener_id=$tweener_id ORDER By ts.sort_order";
            // echo $sql;
            $myrows = $wpdb->get_results($sql);
            // rs_print_array($myrows);
            $this->slides = $myrows;
            return $myrows;
        }
    }

    function getTweeners(){
        global $wpdb;
        $sql = "SELECT ngg.title, tw.tweener_id FROM ".$wpdb->prefix."tweener tw INNER JOIN ".$wpdb->prefix."ngg_gallery ngg ON tw.gallery_id=ngg.gid ORDER BY ngg.title";
        $wpdb->hide_errors();
        $myrows = $wpdb->get_results($sql);
        if(mysql_errno() == 1146){
            tweener_install_db();
        }
        return $myrows;
    }
	

    function get_header(){
        // $this->required_css();
    }

    function displayGallery($gallery_id=0, $type="fader"){
        if($gallery_id > 0){
            
        }

    }

    function displayTweener($tweener_id=0){
        if($tweener_id > 0){
            $this->tweener_id = $tweener_id;
        }
        if($this->tweener_id > 0){
            $this->getTweener($this->tweener_id);
            $this->required_css();
            $this->required_xhtml();
            $this->required_js();
        }
    }

    function required_css(){
        echo "\n<style type=\"text/css\">
            #tweener{
		/* width: ".$this->tweener->width."px; */ /* hide for responsive */
                height: ".$this->tweener->height."px;
		max-width: ".$this->tweener->width."px;
		height: ".$this->tweener->height."px;
		overflow:hidden;
            }
            #tweener .slide{
                width:".$this->tweener->width."px; /* hide for responsive */
                height: ".$this->tweener->height."px;
                background-repeat: no-repeat;
                position: absolute; /* hide for reponsive */
		overflow:hidden;
            }
            #tweener a.slide-link{
                display:block;
                width:".$this->tweener->width."px;
                height:".$this->tweener->height."px;
                z-index: 1000;
                position: absolute;
            }";
            $i=1;
            foreach($this->slides as $slide){
                $background_image = get_bloginfo('wpurl')."/".$slide->path."/".$slide->filename;

            echo "
            #slide". $i ."{
                display:none;
                z-index: ". $i .";
                background-image:url(".$background_image.");
            }";
               $i++;
            }

            echo "
            .slide{
                position: relative;
                color:#ffffff;
            }
            .slide-content{
                position:absolute;
            }
            .slide-title{
                position:absolute;
            }
            .slide-text{
                position:absolute;
            }
            .slide-text p{
                font-size: 16px;
                line-height: 32px;
            }

            .slide .learn-more a{
               display:none;
               position:absolute;
            }
            .slide .learn-more a:hover{
                text-decoration: underline;
            }
            </style>";
        }

        function required_xhtml(){
            // rs_print_array($this->slides);
             echo "<div id=\"tweener\">";
                $i = 1;
                foreach($this->slides as $slide){
                    echo "<div class=\"slide\" id=\"slide". $i ."\">
                            <div class=\"slide-text\">
                            <div class=\"slide-title\"><h1>".
                                   stripslashes($slide->title) ."
                                </h1></div>
                                <div class=\"slide-content\">".
                            stripslashes($slide->excerpt)."</div>
                                <div class=\"learn-more\"><a href=\"".$slide->url."\" class=\"button\" id=\"learn-more-click".$i."\">";
		    if( ! empty( $slide->button_text) )
			echo $slide->button_text;
		    else 
			echo "Learn more";
		    
		    echo "</a></div>

                            </div>
                            <a href=\"".$slide->url."\" class=\"slide-link\"></a>
                        </div>";
                    $i++;
                }
                if($this->tweener->navigation){
                    echo "<div id=\"tweenNav\">";
                    $i=1;
                   foreach($this->slides as $slide){
                       echo "<div class=\"slide-nav\" id=\"slidenav".$i."\"><a href=\"javascript:;\" onclick=\"tweenTo(".$i.");\">$i</a></div>";
                       $i++;
                   }
                   echo "</div>\n";
		   /*
		   if( ! empty( $this->tweener->play_pause) && $this->tweener->play_pause)
		   {
		       echo "<div class=\"slide-nav\" id=\"resume\"><a href=\"javascript:;\" onclick=\"tweenPlay();\"></a></div>";
		   }
		    * 
		    */
		    echo "<div class=\"slide-nav\" id=\"resume\"><a href=\"javascript:;\" onclick=\"tweenPlay();\"></a></div>";
                }
		
                echo "        
                    <div class=\"top-left\"></div>
                    <div class=\"top-right\"></div>
                    <div class=\"bottom-left\"></div>
                    <div class=\"bottom-right\"></div>
                    </div><!-- close tweener -->";
        }

        function required_js(){
            echo "<script language=\"javascript\" type=\"text/javascript\">\n";
            echo "var fadeSpeed = " . $this->tweener->fadetime1 .";\n";
            echo "var paused = " . $this->tweener->paused. ";\n";
            echo "var loop = 1;\n";
            echo "var loops = " . $this->tweener->loop.";\n";
            echo "var tweener_navigation = ".$this->tweener->navigation.";\n";
            echo "var delay;\n";
            echo "var current_i;";
	    echo "var pause_visible = true";
            echo "

jQuery(document).ready(function(){
            fadein1();
        });
	

                function tweenPlay(){
		    window.clearTimeout(delay);
		    if( paused )
		    {
			// console.log( 'paused' );
			paused=false;
			for(i=1; i<=".count($this->slides).";i++){
			     jQuery('#slide'+i).css('display', 'none');
			 }
			 // console.log('current_i = ' + current_i);
			 i = parseInt(current_i)+1;
			 // console.log('count = " . count( $this->slides ) . "');
			 // console.log('i = ' + i);
			 if(i > ".count($this->slides)."){
			     i = 1;
			 }
			// console.log(i);
			function_name = \"fadein\"+i+\"()\";
			jQuery('#tweener>#resume>a').removeClass('paused');
			eval(function_name);
		    }
		    else
		    {
			// console.log( 'not paused' );
			paused = true;
			jQuery('#tweener>#resume>a').addClass('paused');
			
			
		    }
                }

                function tweenTo(id){
                   paused=true;
                   // current_i = id;
                    window.clearTimeout(delay);
                    for(i=1; i<=".count($this->slides).";i++){
                        jQuery('#slide'+i).css('display', 'none');
                    }
	
                    var function_name = \"fadein\"+id+\"()\";
                    eval(function_name);
		    jQuery('#tweener>#resume>a').addClass('paused');
		    jQuery('#tweener>#resume>a').css('display', 'none');
		    pause_visible = false;
                }
                
                 function tweenNav(id){
                        if(tweener_navigation){
                            for(i=1;i<=".count($this->slides).";i++){
                                jQuery('#slidenav'+i).removeClass('selected');
                            }
                            jQuery('#slidenav'+id).addClass('selected');
                        }
                    }
                ";
            $i=1;
            foreach($this->slides as $slide){
                $title_alpha = ceil($slide->title_alpha / 100);
                $title_alpha_init = ceil($slide->title_alpha_init / 100);
                $excerpt_alpha = ceil($slide->excerpt_alpha / 100);
                $excerpt_alpha_init = ceil($slide->excerpt_alpha_init / 100);
                $button_alpha_init = ceil($slide->button_alpha_init / 100);
                $button_alpha = ceil($slide->button_alpha / 100);
                echo "
                    function fadein". $i ."(){
			current_i = '" . $i . "';
			// console.log('current_i =  " . $i . "');
                        tweenNav(".$i.");
                        jQuery('#slide". $i . "').css('display', 'none');
                        jQuery('#slide". $i . ">.slide-text>.slide-title').css('top', ". $slide->title_y_init .");
                        jQuery('#slide". $i . ">.slide-text>.slide-title').css('left', ". $slide->title_x_init .");
                        jQuery('#slide". $i . ">.slide-text>.slide-title').css('opacity', '". $title_alpha_init ."');
                        jQuery('#slide". $i . ">.slide-text>.slide-title').css('display', 'none');
                        jQuery('#slide". $i . ">.slide-text>.slide-content').css('opacity', '".$excerpt_alpha_init."');
                        jQuery('#slide". $i . ">.slide-text>.slide-content').css('top', ". $slide->excerpt_y_init . ");
                        jQuery('#slide". $i . ">.slide-text>.slide-content').css('left', ". $slide->excerpt_x_init . ");
                        jQuery('#slide". $i . ">.slide-text>.slide-content').css('display', 'none');
                        jQuery('#slide". $i . ">.slide-text>.learn-more>a').css('display', 'none');
                        jQuery('#slide". $i . ">.slide-text>.learn-more>a').css('opacity', '".$button_alpha_init."');
                        jQuery('#slide". $i . ">.slide-text>.learn-more>a').css('top', ".$slide->button_y_init.");
                        jQuery('#slide". $i . ">.slide-text>.learn-more>a').css('left', ".$slide->button_x_init.");
			// console.log('fadeIN');
                        jQuery('#slide". $i . "').fadeIn(". $slide->fadetime1 .", function(){
                            // fadein". $i . "_headline();
				window.clearTimeout(delay);
			    delay = setTimeout( function(){fadein". $i . "_headline()}, 0);
                        });
                    }
                function fadein". $i . "_headline(){
                   jQuery('#slide". $i . ">.slide-text>.slide-title').css('display', 'block');
                    jQuery('#slide". $i . ">.slide-text>.slide-title').animate({top: ".$slide->title_y .", left: ".$slide->title_x.", opacity: ".$title_alpha."}, ". $slide->fadetime1 .", function(){
                         // fadein". $i . "_text();
			     window.clearTimeout(delay);
			 ";
			 if( isset( $slide->pause1 ) )
			     echo "delay = setTimeout(function(){ fadein". $i . "_text()}, " .  $slide->pause1 . ");\n ";
			else
			     echo "delay = setTimeout(fadein". $i . "_text(), 0);\n ";
                   echo " });
                };

                function fadein". $i . "_text(){
                    jQuery('#slide". $i . ">.slide-text>.slide-content').css('opacity', '".$excerpt_alpha_init."');
                    jQuery('#slide". $i . ">.slide-text>.slide-content').css('display', 'block');
                    jQuery('#slide". $i . ">.slide-text>.slide-content').animate({top: ".$slide->excerpt_y.", left: ".$slide->excerpt_x.", opacity: ".$excerpt_alpha ."}, ". $slide->fadetime1 .", function() {
                         // fadein". $i ."_button();
			     window.clearTimeout(delay);
			 ";
			 if( empty($slide->has_button))
			 {
			     echo "if( ! pause_visible )
			    {
				jQuery('#tweener>#resume>a').fadeIn(500);
				pause_visible = true;
			    };";
			    $nextnum = $i+1;
			    if($nextnum <= count($this->slides)){
				echo "if(!paused){
				    delay = setTimeout(function(){fadein". $nextnum ."()}, ". $slide->slide_delay.");
				  }else{
				    jQuery('#tweenNav>#resume>a').css('display', 'block');
				    }
				";
				}else{

				echo "     if(!paused){
					    window.clearTimeout(delay);
					    delay = setTimeout(function(){ loopit(); }, ". $slide->slide_delay.");
					  }else{
					    jQuery('#tweenNav>#resume>a').css('display', 'block');
					    }";
				}
			     
			 }
			 else
			 {
			 echo "delay = setTimeout(fadein". $i ."_button(), 0);";
			 }
                   echo "});

                }";
		if( ! empty($slide->has_button)){
                echo "function fadein". $i . "_button(){
                    jQuery('#slide". $i . ">.slide-text>.learn-more>a').css('display', 'block');
                    jQuery('#slide". $i . ">.slide-text>.learn-more>a').animate({top: ".$slide->button_y.", left:".$slide->button_x.", opacity: ".$button_alpha."}, ".$slide->fadetime2.", function(){\n";
                    echo "if( ! pause_visible )
			    {
				jQuery('#tweener>#resume>a').fadeIn(500);
				pause_visible = true;
			    };";
		    $nextnum = $i+1;
                    if($nextnum <= count($this->slides)){
                        echo "if(!paused){
                                delay = setTimeout(\"fadein". $nextnum ."()\", ". $slide->slide_delay.");
                              }else{
                                jQuery('#tweenNav>#resume>a').css('display', 'block');
                                }
                            ";
                    }else{
                       
                    echo "          if(!paused){
				window.clearTimeout(delay);
                                delay = setTimeout(\"loopit()\", ". $slide->slide_delay.");
                              }else{
                              jQuery('#tweenNav>#resume>a').css('display', 'block');
                        }";
                    };
                
                 echo "   });
                }\n";
		}
                   $i++;
            }
           
            echo "
                function loopit(){
                    loop++;
                    if(loop <= loops){
                        for(i=1; i<".count($this->slides)."; i++){
                            nextz = loop * i;
                            jQuery('#slide". $i . "').css('z-index', nextz);
                        }
                         for(i=2; i<=".count($this->slides)."; i++){
                            jQuery('#slide'+i).css('display', 'none');
                         }
                        fadein1();
                    }
                }
                </script>";
        }
	
	
	/* ***************************************************************
	Manual Mode
	*************************************************************** */
	public function setSlides( $slides = array() )
	{
	    foreach( $slides as $slide )
	    {
		$slideObj = new stdClass();
		foreach( $slide as $name => $value )
		{
		    $slideObj->{$name} = $value;
		}
		$this->slides[] = $slideObj;
	    }
	   
	}
	
	public function setConfig( $config = array () )
	{
	    $tweener = new stdClass();
	    foreach( $config as $name=>$value )
	    {
		$tweener->{$name} = $value;
	    }
	    $this->tweener = $tweener;
	}
	
        
}

/* 
 * FOR WP ONLY
 */

/* uncomment below for wordpress setup....  */

    add_action( 'plugins_loaded', 'slide_tweener_setup' );

    global $slide_tweener;
    $slide_tweener = new SlideTweener();

    add_action( 'admin_menu', 'setup_admin_menu' ); 


    function setup_admin_menu(){
	    add_submenu_page( NGGFOLDER, 'Advanced Slideshow', 'Advanced Slides', 'NextGEN Manage gallery', 'slide-tweener', 'slide_tweener_admin' );
    }

    function slide_tweener_admin(){
	    global $slide_tweener;
	    include('admin/tweener-admin.php');
    }
    
    // [slide-tweener id=1]
    function slide_tweener_shortcode( $atts ) {
        $a = shortcode_atts( array(
            'id' => 1,
        ), $atts );

        $slide_tweener->displayTweener($id);
    }
    add_shortcode( 'slide-tweener', 'slide_tweener_shortcode' );
    
    
 // END WordPress setup   
    
 /*
  *  Manual Conversion Functions
  */   
if( !function_exists('get_bloginfo') )
{
    function get_bloginfo( $string = null ){
	if( $string == "wpurl" )
	{
	    global $site_url;
	    return $site_url;
	}
    }
}
    
?>