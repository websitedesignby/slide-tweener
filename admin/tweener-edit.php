<h2><?php _e('Advanced Slide Set','nggallery'); ?></h2>
<h3>Tweener ID = <?php echo $tweener_id; ?></h3>
<div id="synch-gallery">
    <form name="edit_slides" action="admin.php?page=slide-tweener" method="POST">
        <input type="hidden" name="tweener" value="resynch-gallery" />
        <input type="hidden" name="tweener_id" value="<?php echo $tweener_id; ?>" />
        <input type="submit" value="Synchronize with Gallery" class="button-primary" />
    </form>
</div>
<em>* This will add any pictures added to the original gallery and delete any slides no longer in the original gallery</em>

<div id="edit-slides">
    <form name="edit_slides" action="admin.php?page=slide-tweener" method="POST">
        <input type="hidden" name="tweener" value="tweener-update" />
        <input type="hidden" name="tweener_id" value="<?php echo $tweener_id;?>" />
        <div id="tweener">
            <ul>
               <li><label for="height">Height:</label> <input name="height" value="<?php if( isset($tweener) ){ echo $tweener->height; }?>" size="3" /> x 
               <label for="width">Width:</label> <input name="width" value="<?php if( isset($tweener) ){ echo $tweener->width; }?>" size="3" /></li>
            </ul>
        </div>
<?php
if(count($slides)){

    $args_posts = array('posts_per_page'=> -1, 'orderby'=>'post_date', 'order' => 'ASC');
    $posts = get_posts($args_posts);
    $args_pages = $args_pages = array('post_type'=>'page', 'posts_per_page'=> -1, 'orderby'=>'post_date', 'order' => 'ASC');
    $pages = get_posts($args_pages);
    $all_posts = array_merge($posts, $pages);
    // rs_print_array($all_posts);
    ?>

    <?php
    $ngg = new nggGallery();
    foreach($slides as $slide){
        $thumbnail_url = $ngg->get_thumbnail_url($slide->pid, $slide->path, $slide->filename);
        $title = $slide->picture_title;
        if(strlen($slide->title)){
            $title = $slide->title;
        }
        $description = $slide->picture_description;
        if(strlen($slide->excerpt)){
            $description = $slide->excerpt;
        }
        $slide_delay = $slide->slide_delay_tweener;
        if($slide->slide_delay > 0){
            $slide_delay = $slide->slide_delay;
        }
        $fadetime1 = $slide->fadetime1_tweener;
        if($slide->fadetime1 > 0){
            $fadetime1 = $slide->fadetime1;
        }
        $fadetime2 = $slide->fadetime2_tweener;
        if($slide->fadetime2 > 0){
            $fadetime2 = $slide->fadetime2;
        }
        $button_text = "Learn More";
        if(strlen($slide->button_text)>0){
            $button_text = $slide->button_text;
        }
        $url = $slide->url;
        if(strlen($url) == 0){
            foreach($all_posts as $post){
                // echo $slide->post_id . " == " . $post->ID."<br />";
                if($slide->post_id==$post->ID){
                    $url = $post->guid;
                }
            }
        }
        ?>
        
        <div class="slide">
            <input type="hidden" name="tweener_slides[]" value="<?php echo $slide->tweener_slide_id; ?>" />
            <div class="thumb">
                <img src="<?php echo $thumbnail_url;?>" />
                    <ul>
                        <li><label for="sort_order">Sort Order:</label> <input type="text" size="3" name="sort_order_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->sort_order;?>" /></li>
                            <li>Title &amp; Description fade in after <input type="text" size="3" name="fadetime1_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $fadetime1?>" />/1000 seconds</li>
                            <li><label for="title">Title: </label> <input type="text" name="title_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo stripslashes($title);?>" /></li>
                            <li>Title X: <input type="text" name="title_x_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->title_x?>" size="3" /> Y: <input type="text" name="title_y_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->title_y?>" size="3" />
                                | Start X: <input type="text" name="title_x_init_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->title_x_init?>" size="3" /> Start Y: <input type="text" name="title_y_init_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->title_y_init ?>" size="3" />
                                | Title Alpha: <input type="text" name="title_alpha_<?php echo $slide->tweener_slide_id; ?>" size="3" value="<?php echo $slide->title_alpha;?>" /> Alpha Start: <input type="text" name="title_alpha_init_<?php echo $slide->tweener_slide_id; ?>" size="3" value="<?php echo $slide->title_alpha_init;?>" /></li>
                            <li><label for="excerpt">Excerpt:</label> <textarea name="excerpt_<?php echo $slide->tweener_slide_id; ?>"><?php echo stripslashes($description); ?></textarea></li>
                            <li>Excerpt X: <input type="text" name="excerpt_x_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->excerpt_x?>" size="3" /> Y: <input type="text" name="excerpt_y_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->excerpt_y?>" size="3" />
                                | Start X: <input type="text" name="excerpt_x_init_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->excerpt_x_init?>" size="3" /> Start Y: <input type="text" name="excerpt_y_init_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->excerpt_y_init ?>" size="3" />
                                | Title Alpha: <input type="text" name="excerpt_alpha" size="3" value="<?php echo $slide->excerpt_alpha;?>" /> Alpha Start: <input type="text" name="excerpt_alpha_init_<?php echo $slide->tweener_slide_id; ?>" size="3" value="<?php echo $slide->excerpt_alpha_init;?>" /></li>
                            <li>Button fades in after <input type="text" size="3" name="fadetime2_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $fadetime2?>" />/1000 seconds</li>
                            <li><label for="button_text">Button Text: </label> <input type="text" name="button_text_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $button_text;?>" /></li>
                            <li>Button X: <input type="text" name="button_x_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->button_x?>" size="3" /> Y: <input type="text" name="button_y_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->button_y?>" size="3" />
                                | Start X: <input type="text" name="button_x_init_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->button_x_init?>" size="3" /> Start Y: <input type="text" name="button_y_init_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->button_y_init ?>" size="3" />
                                | Button Alpha: <input type="text" name="button_alpha_<?php echo $slide->tweener_slide_id; ?>" size="3" value="<?php echo $slide->button_alpha;?>" /> Alpha Start: <input type="text" name="button_alpha_init_<?php echo $slide->tweener_slide_id; ?>" size="3" value="<?php echo $slide->button_alpha_init;?>" /></li>
                            <li><label for="url">URL: </label> <input type="text" name="url_<?php echo $slide->tweener_slide_id; ?>" style="width:500px;" value="<?php echo $url;?>" /></li>
                            <li><label for="button_onclick">onclick=</label><input type="text" name="button_onclick_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide->button_onclick;?>" /></li>
                            <li><label for="slide_delay">Slide Delay:</label> <input type="text" name="slide_delay_<?php echo $slide->tweener_slide_id; ?>" value="<?php echo $slide_delay; ?>" size="3" /></li>
                    </ul>
            </div>
            
        </div>
        <?php
    }
    ?>
        <p><input type="submit" name="existingContinue" class="button-primary"  value="<?php _e('Save','nggallery') ;?> &raquo;"/></p>
    </form>
    <form name="edit_slides" action="admin.php?page=slide-tweener" method="POST">
        <input type="hidden" name="tweener" value="tweener-delete" />
        <input type="hidden" name="tweener_id" value="<?php echo $tweener_id;?>" />
        <input type="submit" name="deleteContinue" class="button-secondary" value="<?php _e('Delete', 'nggallery'); ?>" onclick="return confirm('Are you sure you want to delete this Slide Show?')" />
    </form>
</div>
    <?php
}
// rs_print_array($slides);
?>