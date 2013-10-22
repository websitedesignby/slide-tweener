<?php
/*
 * $nggdb->find_all_galleries('gid', 'asc', TRUE, 25, $start, false);
 * find_all_galleries($order_by = 'gid', $order_dir = 'ASC', $counter = false, $limit = 0, $start = 0, $exclude = true)
 */
global $nggdb, $tweener;
$gallery_list = $nggdb->find_all_galleries();
$tweener_list = $slide_tweener->getTweeners();

// rs_print_array($gallery_list);

// $posts = get_posts(array('post_type'=>'page'));
// rs_print_array($posts);

// options are not included with installation
$default_tweenerWidth = 900;
$default_tweenerHeight = 300;
$default_tweenerFadeTime1 = 900;
$default_tweenerFadeTime2 = 1200;
$default_tweenerSlideDelay  = 4000;
$default_tweenerPaused = "false";
$default_tweenerLoop = 9999;

if(!isset($ngg->options['tweenerWidth'])){
    $tWidth = $default_tweenerWidth;
}else{
    $tWidth = $ngg->options['tweenerWidth'];
}
if(!isset($ngg->options['tweenerHeight'])){
    $tHeight = $default_tweenerHeight;
}else{
    $tHeight = $ngg->options['tweenerHeight'];
}
if(isset($ngg->options['tweenerFadeTime1'])){
    $tweenerFadeTime1 = $ngg->options['tweenerFadeTime1'];
}else{
    $tweenerFadeTime1 = $default_tweenerFadeTime1;
}
if(isset($ngg->options['tweenerFadeTime2'])){
    $tweenerFadeTime2 = $ngg->options['tweenerFadeTime2'];
}else{
    $tweenerFadeTime2 = $default_tweenerFadeTime2;
}
if(isset($ngg->options['tweenerSlideDelay'])){
    $tweenerSlideDelay = $ngg->options['tweenerSlideDelay'];
}else{
    $tweenerSlideDelay = $default_tweenerSlideDelay;
}
if(isset($ngg->options['tweenerPaused'])){
    $tweenerPaused= $ngg->options['tweenerPaused'];
}else{
    $tweenerPaused = $default_tweenerPaused;
}
if(isset($ngg->options['tweenerLoop'])){
    $tweenerLoop = $ngg->options['tweenerLoop'];
}else{
    $tweenerLoop = $default_tweenerLoop;
}
?>
<h2><?php _e('Advanced Slides','nggallery'); ?></h2>
<form name="tweener_select1" method="POST" action="<?php echo  "admin.php?page=slide-tweener" ?>" onsubmit="return checkTweenerId(this);">
<input type="hidden" name="tweener" value="tweener-select" />
<table class="form-table ngg-options">
        <tr>
                <th><?php _e('Select an Existing Slideshow','nggallery') ?></th>
                <td><select name="tweener_id"><option value="0">Select an existing slideshow...</option><?php
                foreach($tweener_list as $tweener){
                    echo "<option value=\"".$tweener->tweener_id."\">".stripslashes($tweener->title)."</option>";
                }
                    ?></select>   <input type="submit" name="existingContinue" class="button-primary"  value="<?php _e('Continue','nggallery') ;?> &raquo;"/></td>

        </tr>
</table>
</form>
<form name="tweener_select2" method="POST" action="<?php echo  "admin.php?page=slide-tweener" ?>" onsubmit="return checkGalleryId(this);">
<input type="hidden" name="tweener" value="tweener-add" />
<table class="form-table ngg-options">
        <tr>
                <th><?php _e('Create a New Slideshow Using','nggallery') ?></th>
                <td><select name="gallery_id"><option value="0">Select a gallery...</option>
                        <?php
                        foreach($gallery_list as $gallery){
                            ?>
                        <option value="<?php echo $gallery->gid; ?>"><?php echo $gallery->title; ?></option>
                        <?php
                        }
                        ?>
                        <?php // get_tweeners(); ?></select> </td>
        </tr>
        <tr>
            <td>Size:</td><td><input type="text" value="<?php echo $tWidth; ?>" name="width" size="3" /> (w) x <input type="text" value="<?php echo $tHeight;?>" name="height" size="3" /> (h)</td>
        </tr>
        <tr>
            <td>Fade Time 1:</td><td><input type="text" size="3" value="<?php echo $tweenerFadeTime1;?>" name="fadeTime1" /></td>
        </tr>
        <tr>
            <td>Fade Time 2:</td><td><input type="text" size="3" value="<?php echo $tweenerFadeTime2;?>" name="fadeTime2" /></td>
        </tr>
        <tr>
            <td>Slide Delay (milliseconds):</td><td><input type="text" size="3" value="<?php echo $tweenerSlideDelay;?>" name="delay" /></td>
        </tr>
        <tr>
            <td>Paused:</td><td><input type="text" value="<?php echo $tweenerPaused;?>" name="paused" /></td>
        </tr>
        <tr>
            <td>Loop:</td><td><input type="text" value="<?php echo $tweenerLoop;?>" name="loop" /></td>
        </tr>
        <tr>
            <td>Navigation:</td><td><input type="checkbox" name="navigation" checked="checked" value="1" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td><td><input type="submit" name="newContinue" class="button-primary"  value="<?php _e('Continue','nggallery') ;?> &raquo;"/></td>
        </tr>
</table>
</form>