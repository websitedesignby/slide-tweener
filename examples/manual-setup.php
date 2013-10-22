<?php

/************************
 * Setup Slide Tweener
 */


$slideTweener = new SlideTweener();

$slideshow_config = array(
   'height' => 248,
    'width' => 985,
    'navigation' => true, 
    'play_pause' => true,
    'fadetime1' => 3000, // default fadetime 1
    'paused' => 'false',
    'loop' => 10000000,
);

$slideTweener->setConfig( $slideshow_config );

$slidepath = "images/slides";

$slide1 = new stdClass();
$slide1->path = $slidepath;
$slide1->filename = 'walking1.jpg';

// baseline

$quote_x = 16;
$quote_y = 45;
$quote_x_offset = -300;

$excerpt_y = 124;
$excerpt_x = 36;

$slide_delay = 10000;
$pause1 = 2000;

$slides = array(
    
    'slide2' => array(
      'path' => $slidepath,
      'filename' => 'slide1-248.jpg',
      'title' => '<div id="knack-tumbler"></div>',
      'excerpt' => '',
      'url' => '',
      'title_alpha' => 100,
      'title_alpha_init' => 0,
      'excerpt_alpha' => 100,
      'excerpt_alpha_init' => 0,
      'button_alpha' => 0,
      'button_alpha_init' =>0,
      'title_y' => $quote_y - 35,
      'title_y_init' => $quote_y - 35,
      'title_x' => $quote_x,
      'title_x_init' => $quote_x,
      'excerpt_y' => 0,
      'excerpt_y_init' => 0,
      'excerpt_x' => 0,
      'excerpt_x_init' => 0,
      'button_y' => 0,
      'button_y_init' => 0,
      'button_x' => 0,
      'button_x_init' => 0,
      'fadetime1' => 1750, // how long to fade in title and exerpt
      'fadetime2' => 0, // how long to fade in button
      'slide_delay' => 5000,
	'pause1' => $pause1,
	'has_button' => false,
      
  ),
   'gates' => array(
      'path' => $slidepath,
      'filename' => 'bill-gates.jpg',
      'title' => '<span class="quote black" id="billGates">&ldquo;The key for us, number one, has always been hiring very smart people.&rdquo;</span>',
      'excerpt' => '<span class="author black">&mdash; Bill Gates</span>',
      'url' => '',
      'title_alpha' => 100,
      'title_alpha_init' => 0,
      'excerpt_alpha' => 100,
      'excerpt_alpha_init' => 0,
      'button_alpha' => 0,
      'button_alpha_init' =>0,
      'title_y' => $quote_y,
      'title_y_init' => $quote_y,
      'title_x' => $quote_x,
      'title_x_init' => $quote_x + $quote_x_offset,
      'excerpt_y' => $excerpt_y + 5,
      'excerpt_y_init' => $excerpt_y + 5,
      'excerpt_x' => $excerpt_x,
      'excerpt_x_init' => $excerpt_x,
      'button_y' => 0,
      'button_y_init' => 0,
      'button_x' => 0,
      'button_x_init' => 0,
      'fadetime1' => 1500, // how long to fade in title and exerpt
      'fadetime2' => 2000, // how long to fade in button
      'slide_delay' => 3000,
       'pause1' => $pause1,
       'has_button' => false,
      
  ),
    'buffet' => array(
      'path' => $slidepath,
      'filename' => 'markets.jpg',
      'title' => '<span class="quote white" id="buffet">&ldquo;Price is what you pay.<br />Value is what you get.&rdquo;</span>',
      'excerpt' => '<span class="author white">&mdash; Warren Buffet</span>',
      'url' => '',
      'title_alpha' => 100,
      'title_alpha_init' => 0,
      'excerpt_alpha' => 100,
      'excerpt_alpha_init' => 0,
      'button_alpha' => 0,
      'button_alpha_init' =>0,
      'title_y' => $quote_y,
      'title_y_init' => $quote_y,
      'title_x' => $quote_x + 50,
      'title_x_init' => $quote_x + $quote_x_offset,
      'excerpt_y' => $excerpt_y,
      'excerpt_y_init' => $excerpt_y,
      'excerpt_x' => $excerpt_x + 35,
      'excerpt_x_init' => $excerpt_x + 35,
      'button_y' => 0,
      'button_y_init' => 0,
      'button_x' => 0,
      'button_x_init' => 0,
      'fadetime1' => 1500, // how long to fade in title and exerpt
      'fadetime2' => 2000, // how long to fade in button
      'slide_delay' => 3000,
	'pause1' => $pause1,
	'has_button' => false,
      
  ),
    'iacocca ' => array(
      'path' => $slidepath,
      'filename' => 'iacocca.jpg',
      'title' => '<span class="quote black" id="iacocca">&ldquo;I hire people brighter than me<br>and then I get out of their way.&rdquo;</span>',
      'excerpt' => '<span class="author black">&mdash; Lee Iacocca</span>',
      'url' => '',
      'title_alpha' => 100,
      'title_alpha_init' => 0,
      'excerpt_alpha' => 100,
      'excerpt_alpha_init' => 0,
      'button_alpha' => 0,
      'button_alpha_init' =>0,
      'title_y' => $quote_y,
      'title_y_init' => $quote_y,
      'title_x' => $quote_x,
      'title_x_init' => $quote_x + $quote_x_offset,
      'excerpt_y' => $excerpt_y,
      'excerpt_y_init' => $excerpt_y,
      'excerpt_x' => $excerpt_x,
      'excerpt_x_init' => $excerpt_x,
      'button_y' => 0,
      'button_y_init' => 0,
      'button_x' => 0,
      'button_x_init' => 0,
      'fadetime1' => 1500, // how long to fade in title and exerpt
      'fadetime2' => 2000, // how long to fade in button
      'slide_delay' => 3000,  
	'pause1' => $pause1,
	'has_button' => false,
  ),
    
   'walton ' => array(
      'path' => $slidepath,
      'filename' => 'walton.jpg',
      'title' => '<span class="quote black" id="iacocca">&ldquo;High expectations are the key to everything.&rdquo;</span>',
      'excerpt' => '<span class="author black">&mdash; Sam Walton</span>',
      'url' => '',
      'title_alpha' => 100,
      'title_alpha_init' => 0,
      'excerpt_alpha' => 100,
      'excerpt_alpha_init' => 0,
      'button_alpha' => 0,
      'button_alpha_init' =>0,
      'title_y' => $quote_y + 25,
      'title_y_init' => $quote_y + 25,
      'title_x' => $quote_x,
      'title_x_init' => $quote_x + $quote_x_offset,
      'excerpt_y' => $excerpt_y,
      'excerpt_y_init' => $excerpt_y,
      'excerpt_x' => $excerpt_x,
      'excerpt_x_init' => $excerpt_x,
      'button_y' => 0,
      'button_y_init' => 0,
      'button_x' => 0,
      'button_x_init' => 0,
      'fadetime1' => 1500, // how long to fade in title and exerpt
      'fadetime2' => 2000, // how long to fade in button
      'slide_delay' => 3000, 
       'pause1' => $pause1,
       'has_button' => false,
  ),
);

$slideTweener->setSlides( $slides );

?>