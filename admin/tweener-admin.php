<?php
/*
 * DATABASE SCHEMA
 * tweener
 * tweener_slide
 *      slide_id
 *      post_id     : wp_posts (ID)
 *      pid         : ngg_pictures
 *      title       : default = wp_posts->post_title
 *      excerpt     : default = wp_posts->post_excerpt
 *      url         : default = permalink wp_posts->guid ?
 *
 */

global $nggdb;
global $wpdb;

// table names
global $tbl_tweener, $tbl_tweener_slides;
$tbl_tweener= $wpdb->prefix . 'tweener';
$tbl_tweener_slides = $wpdb->prefix.'tweener_slides';

function tweener_install_db(){
    global $wpdb, $tbl_tweener, $tbl_tweener_slides;

    if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') ) {
        if ( ! empty($wpdb->charset) )
                $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty($wpdb->collate) )
                $charset_collate .= " COLLATE $wpdb->collate";
    }
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    if( !$wpdb->get_var( "SHOW TABLES LIKE '$tbl_tweener'" ) ) {
    // build tweener table
    $sql = "CREATE TABLE " . $tbl_tweener . " (
	`tweener_id` INT NOT NULL AUTO_INCREMENT,
        `gallery_id` INT UNSIGNED NOT NULL ,
        `height` INT UNSIGNED NOT NULL ,
        `width` INT UNSIGNED NOT NULL ,
        `fadetime1` INT UNSIGNED NOT NULL ,
        `fadetime2` INT UNSIGNED NOT NULL ,
        `slide_delay` INT UNSIGNED NOT NULL ,
        `loop` INT UNSIGNED NOT NULL,
        `paused` VARCHAR (20),
        `navigation` INT UNSIGNED NOT NULL, 
        `type` VARCHAR( 100 ) NOT NULL ,
        PRIMARY KEY (  `tweener_id` )
		) $charset_collate;";
      
      dbDelta($sql);
    }

     if( !$wpdb->get_var( "SHOW TABLES LIKE '$tbl_tweener_slides'" ) ) {
    // build tweener_slides table
     $sql = "CREATE TABLE " . $tbl_tweener_slides . " (
	`tweener_slide_id` INT NOT NULL AUTO_INCREMENT,
        `tweener_id` INT UNSIGNED NOT NULL, 
        `pid` INT UNSIGNED NOT NULL ,
        `sort_order` INT NOT NULL, 
        `post_id` INT UNSIGNED NOT NULL, 
        `fadetime1` INT UNSIGNED NOT NULL ,
        `fadetime2` INT UNSIGNED NOT NULL ,
        `slide_delay` INT UNSIGNED NOT NULL,
        `title_x_init` INT NOT NULL,
        `title_y_init` INT NOT NULL,
        `title` VARCHAR ( 255 ) NOT NULL,
        `title_x` INT NOT NULL,
        `title_y` INT NOT NULL,
        `title_alpha` INT UNSIGNED NOT NULL DEFAULT '100',
        `title_alpha_init` INT UNSIGNED NOT NULL, 
        `excerpt` TEXT NOT NULL,
        `excerpt_x_init` INT NOT NULL,
        `excerpt_y_init` INT NOT NULL,
        `excerpt_x` INT NOT NULL,
        `excerpt_y` INT NOT NULL,
        `excerpt_alpha` INT UNSIGNED NOT NULL DEFAULT '100',
        `excerpt_alpha_init` INT UNSIGNED NOT NULL,
        `url` VARCHAR ( 255 ) NOT NULL,
        `button_x` INT NOT NULL,
        `button_y` INT NOT NULL,
        `button_alpha` INT UNSIGNED NOT NULL DEFAULT '100',
        `button_x_init` INT NOT NULL,
        `button_y_init` INT NOT NULL,
        `button_alpha_init` INT UNSIGNED NOT NULL,
        `button_text` VARCHAR ( 255 ) NOT NULL,
        `button_onclick` VARCHAR ( 255 ) NOT NULL, 
        `type` VARCHAR( 100 ) NOT NULL ,
        PRIMARY KEY (  `tweener_slide_id` )
		) $charset_collate;";

      dbDelta($sql);
     }

}

function tweener_db_check()
{
    global $tbl_tweener_slides, $wpdb;
    $sql = "SHOW TABLES LIKE '" . $tbl_tweener_slides . "'";
    $result = $wpdb->query($sql);
    if( empty( $result ) )
    {
        tweener_install_db();
    }
}

function tweener_insert($table, $name_values, $formats){
    global $wpdb;
    tweener_db_check();
    $result = $wpdb->insert( $table, $name_values, $formats );
}
?>
<script language="javascript" type="text/javascript">
function checkGalleryId(form){
    if(form.gallery_id.value == 0){
        alert('Please select a gallery.');
        return false;
    }
}
function checkTweenerId(form){
   if(form.tweener_id.value == 0){
        alert('Please select a slideshow.');
        return false;
    }
}
</script>
<div class="wrap">
<!-- Slideshow settings -->
<?php
if(isset($_POST['tweener'])){
    switch($_POST['tweener']){

        case "resynch-gallery":
            $tweener_id = $_POST['tweener_id'];
            $tweener = $slide_tweener->getTweener($tweener_id);
            
            $gallery_id = $tweener->gallery_id;
            $gallery = $nggdb->get_gallery( $gallery_id );
            $slides = $slide_tweener->tweenerGetSlides($tweener_id);

            $fadetime1 = $tweener->fadetime1;
            $fadetime2 = $tweener->fadetime2;
            $slide_delay = $tweener->slide_delay;

            $insert = "INSERT INTO $tbl_tweener_slides (pid, slide_delay, fadetime1, fadetime2) VALUES";
            $delete_sql = "DELETE FROM $tbl_tweener_slides WHERE pid=";
            $values = array();
            if(count($gallery)){
                foreach($gallery as $image){
                    $add = true;
                    foreach($slides as $slide){
                        if($image->pid == $slide->pid){
                            $add = false;
                        }
                    }
                    if($add){
                        echo "add $image->pid";
                        tweener_insert($tbl_tweener_slides, array('tweener_id'=>$tweener_id, 'pid'=>$image->pid, 'slide_delay'=>$slide_delay, 'fadetime1'=>$fadetime1, 'fadetime2'=>$fadetime2),
                                            array('%d', '%d', '%d', '%d', '%d'));
                    }
                }
                foreach($slides as $slide){
                    $delete = true;
                    foreach($gallery as $image){
                        if($image->pid == $slide->pid){
                            $delete = false;
                        }
                    }
                    if($delete){
                        $wpdb->query("DELTE FROM $tbl_tweener_slides WHERE pid=".$slide->pid);
                    }
                }
                $tweener = $slide_tweener->getTweener($tweener_id);
                $slides = $slide_tweener->tweenerGetSlides($tweener_id);
                include("tweener-edit.php");
            }
            break;

        case "tweener-delete":
            $tweener_id = $_POST['tweener_id'];
            $sql = "DELETE FROM $tbl_tweener WHERE tweener_id=$tweener_id";
            $wpdb->query($sql);
            $sql = "DELETE FROM $tbl_tweener_slides WHERE tweener_id=$tweener_id";
            $wpdb->query($sql);
            include("tweener-default.php");
            break;

        case "tweener-update":
            if(isset($_POST['tweener_id'])){
                $tweener_id = $_POST['tweener_id'];
                $tweener_height = $_POST['height'];
                $tweener_width = $_POST['width'];
                $wpdb->update($tbl_tweener, array('height'=>$tweener_height, 'width'=>$tweener_width), array('tweener_id'=>$tweener_id), '%d', '%d');
                foreach($_POST['tweener_slides'] as $tweener_slide_id){
                    $sort_order = $_POST['sort_order_'.$tweener_slide_id];
                    $fadetime1 = $_POST['fadetime1_'.$tweener_slide_id];
                    $fadetime2 = $_POST['fadetime2_'.$tweener_slide_id];
                    $slide_delay = $_POST['slide_delay_'.$tweener_slide_id];
                    $title = $_POST['title_'.$tweener_slide_id];
                    $title_x = $_POST['title_x_'.$tweener_slide_id];
                    $title_y = $_POST['title_y_'.$tweener_slide_id];
                    $title_x_init = $_POST['title_x_init_'.$tweener_slide_id];
                    $title_y_init = $_POST['title_y_init_'.$tweener_slide_id];
                    $title_alpha = $_POST['title_alpha_'.$tweener_slide_id];
                    $title_alpha_init = $_POST['title_alpha_init_'.$tweener_slide_id];
                    $excerpt = $_POST['excerpt_'.$tweener_slide_id];
                    $excerpt_x = $_POST['excerpt_x_'.$tweener_slide_id];
                    $excerpt_y = $_POST['excerpt_y_'.$tweener_slide_id];
                    $excerpt_x_init = $_POST['excerpt_x_init_'.$tweener_slide_id];
                    $excerpt_y_init = $_POST['excerpt_y_init_'.$tweener_slide_id];
                    $url = $_POST['url_'.$tweener_slide_id];
                    $button_x = $_POST['button_x_'.$tweener_slide_id];
                    $button_y = $_POST['button_y_'.$tweener_slide_id];
                    $button_alpha = $_POST['button_alpha_'.$tweener_slide_id];
                    $button_x_init = $_POST['button_x_init_'.$tweener_slide_id];
                    $button_y_init = $_POST['button_y_init_'.$tweener_slide_id];
                    $button_alpha_init = $_POST['button_alpha_init_'.$tweener_slide_id];
                    $button_text = $_POST['button_text_'.$tweener_slide_id];
                    $button_onclick = $_POST['button_onclick_'.$tweener_slide_id];
                    $wpdb->update($tbl_tweener_slides, array('sort_order'=>$sort_order, 'fadetime1'=>$fadetime1, 'fadetime2'=>$fadetime2, 'slide_delay'=>$slide_delay, 'title'=>$title,
                        'title_x'=>$title_x, 'title_y'=>$title_y, 'title_x_init'=>$title_x_init, 'title_y_init'=>$title_y_init, 'title_alpha'=>$title_alpha, 'excerpt'=>$excerpt, 'excerpt_x'=>$excerpt_x, 'excerpt_y'=>$excerpt_y,
                     'excerpt_x_init'=>$excerpt_x_init, 'excerpt_y_init'=>$excerpt_y_init, 'url'=>$url, 'button_x'=>$button_x, 'button_y'=>$button_y, 'button_alpha'=>$button_alpha, 'button_alpha_init'=>$button_alpha_init, 
                        'button_x_init'=>$button_x_init, 'button_y_init'=>$button_y_init, 'button_onclick'=>$button_onclick),
                            array('tweener_slide_id'=>$tweener_slide_id), 
                            array('%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%s'));
                }
                $tweener = $slide_tweener->getTweener($tweener_id);
                $slides = $slide_tweener->tweenerGetSlides($tweener_id);
                include("tweener-edit.php");
            }
            break;

        case "tweener-insert":
                if(isset($_POST['gallery_id'])){
                    tweener_insert( $tbl_tweener, array( 'gallery_id' => $_POST['gallery_id'], 'height'=>$_POST['height'], 'width'=>$_POST['width'],
                                    'fadetime1'=>$_POST['fadetime1'], 'fadetime2'=>$_POST['fadetime2'], 'slide_delay'=>$_POST['delay'], 'paused'=>$_POST['paused'],
                                    'loop'=>$_POST['loop'], 'navigation'=>$_POST['navigation']), array('%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%d') );
                    $tweener_id =  $wpdb->insert_id;
                    $sorters = array();
                    $pids = array();
                    if(isset($_POST['sorter'])){
                        $sorters = $_POST['sorter'];
                    }
                    if(isset($_POST['post_id'])){
                        $post_ids = $_POST['post_id'];
                    }
                    if(isset($_POST['page_id'])){
                        $page_ids = $_POST['page_id'];
                    }
                    if(isset($_POST['pid'])){
                        $pids = $_POST['pid'];
                        $i = 0;
                        foreach($pids as $pid){
                            if(isset($post_ids[$i])){
                                $post_id = $post_ids[$i];
                            }else{
                                $post_id = $page_ids[$i];
                            }
                            tweener_insert($tbl_tweener_slides, array('pid'=>$pid, 'sort_order'=>$sorters[$i], 'post_id'=>$post_id, 'tweener_id'=>$tweener_id),
                                            array('%d', '%d', '%d', '%s'));
                            $i++;
                        }
                    }
                    $tweener = $slide_tweener->getTweener($tweener_id);
                    $slides = $slide_tweener->tweenerGetSlides($tweener_id);
                    include("tweener-edit.php");
                }
                break;

        case "tweener-select":
                $tweener_id = $_POST['tweener_id'];
                $tweener = $slide_tweener->getTweener($tweener_id);
                $slides = $slide_tweener->tweenerGetSlides($tweener_id);
                include("tweener-edit.php");
                break;

        case "tweener-add":
            if($_POST['gallery_id']>0){
                $navigation = 0;
                if(isset($_POST['navigation'])){
                    $navigation = 1;
                }
                ?>
                <form name="tweener_select1" method="POST" action="<?php echo  "admin.php?page=slide-tweener" ?>" >
                <input type="hidden" name="tweener" value="tweener-insert" />
                <input type="hidden" name="gallery_id" value="<?php echo $_POST['gallery_id']; ?>" />
                <input type="hidden" name="height" value="<?php echo $_POST['height'];?>" />
                <input type="hidden" name="width" value="<?php echo $_POST['width'];?>" />
                <input type="hidden" name="fadetime1" value="<?php echo $_POST['fadeTime1']; ?>" />
                <input type="hidden" name="fadetime2" value="<?php echo $_POST['fadeTime2']; ?>" />
                <input type="hidden" name="delay" value="<?php echo $_POST['delay']; ?>" />
                <input type="hidden" name="paused" value="<?php echo $_POST['paused']; ?>" />
                <input type="hidden" name="loop" value="<?php echo $_POST['loop']; ?>" />
                <input type="hidden" name="navigation" value="<?php echo $navigation; ?>" />
                <?php
                $gallery = $nggdb->get_gallery( $_POST['gallery_id'] );
                if(count($gallery)){
                    $args_posts = array('post_type'=>'post', 'posts_per_page'=> -1, 'orderby'=>'post_date', 'order' => 'ASC');
                    $posts = get_posts($args_posts);
                    $select_posts = "(no posts)";
                    if(count($posts)){
                        $select_posts = "<select name=\"post_id[]\"><option value=\"0\">none</option>";
                        foreach($posts as $post){
                            $select_posts .= "<option value=\"".$post->ID."\">".$post->post_title."</option>";
                       }
                       $select_posts .= "</select>";
                    }
                    $args_pages = $args_pages = array('post_type'=>'page', 'posts_per_page'=> -1, 'orderby'=>'post_date', 'order' => 'ASC');
                    $pages = get_posts($args_pages);
                    $select_pages = "(no pages)";
                    if(count($pages)){
                        $select_pages = "<select name=\"page_id[]\"><option value=\"0\">none</option>";
                        foreach($pages as $page){
                            $select_pages .= "<option value=\"".$page->ID."\">".$page->post_title."</option>";
                        }
                        $select_pages .= "</select>";
                    }
                   
                ?>
                <p><em>Select related (link) pages and set sorting:</em></p>
                <table id="ngg-listimages" class="widefat" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope='col' id='id' class='manage-column column-id'  style="">ID</th>
                            <th scope='col' id='sortby' class='manage-column column-sortby'  style="">Sort Order</th>
                            <th scope='col' id='thumbnail' class='manage-column column-thumbnail'  style="">Thumbnail</th>
                            <th scope='col' id='filename' class='manage-column column-filename'  style="">Filename</th>
                            <th scope='col' id='alt_title_desc' class='manage-column select-post'  style="">Select Post Link</th>
                            <th scope='col' id='additional' class='manage-column select-page'  style="">Select Page Link</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope='col' id='id' class='manage-column column-id'  style="">ID</th>
                            <th scope='col' id='sortby' class='manage-column column-id'  style="">Sort Order</th>
                            <th scope='col' id='thumbnail' class='manage-column column-thumbnail'  style="">Thumbnail</th>
                            <th scope='col' id='filename' class='manage-column column-filename'  style="">Filename</th>
                            <th scope='col' id='alt_title_desc' class='manage-column select-post'  style="">Select Post Link</th>
                            <th scope='col' id='additional' class='manage-column select-page'  style="">Select Page Link</th>
                        </tr>
                    </tfoot>
                    <tbody>
                <?php
                $i=0;
                foreach($gallery as $image){
                    $height = $image->meta_data['height'];
                    $width = $image->meta_data['width'];
                    ?>
                    <tr id="picture-<?php echo $image->pid; ?>" class="iedit"  valign="top">
                        <td class="id column-id" scope="row" style=""><?php echo $image->pid; ?><input type="hidden" name="pid[]" value="<?php echo $image->pid;?>" /></td>
                        <td class="sortby column-sortby" scope="row" style=""><input type="text" value="<?php echo $i+1;?>" name="sorter[]" size="3" /></td>
			<td class="thumbnail column-thumbnail"><?php echo $image->thumbHTML; ?></td>
                        <td class="filename column-filename"><strong><?php echo $image->filename; ?></strong></td>
                        <td class="select-post column-select-post"><?php echo $select_posts; ?>
                         </td>
                         <td class="select-page column-select-page"><?php echo $select_pages; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                // rs_print_array($gallery);
                ?>
                    </tbody>
                </table>
                <input type="submit" name="existingContinue" class="button-primary"  value="<?php _e('Continue','nggallery') ;?> &raquo;"/>
                </form>
                <?php
                }
            }
            break;
        case "save-gallery":
            if($_POST['gallery_id']>0){
                ?>
                <form name="tweener_select1" method="POST" action="<?php echo  "admin.php?page=slide-tweener" ?>" >
                <input type="hidden" name="tweener-update" value="0" />
                <?php
                $gallery = $nggdb->get_gallery( $_POST['gallery_id'] );
                if(count($gallery)){
                ?>
                <table id="ngg-listimages" class="widefat fixed" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope='col' id='id' class='manage-column column-id'  style="">ID</th>
                            <th scope='col' id='thumbnail' class='manage-column column-thumbnail'  style="">Thumbnail</th>
                            <th scope='col' id='filename' class='manage-column column-filename'  style="">Filename</th>
                            <th scope='col' id='alt_title_desc' class='manage-column column-alt_title_desc'  style="">Alt &amp; Title Text / Description</th>
                            <th scope='col' id='additional' class='manage-column column-tags'  style="">Additional</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope='col' id='id' class='manage-column column-id'  style="">ID</th>
                            <th scope='col' id='thumbnail' class='manage-column column-thumbnail'  style="">Thumbnail</th>
                            <th scope='col' id='filename' class='manage-column column-filename'  style="">Filename</th>
                            <th scope='col' id='alt_title_desc' class='manage-column column-alt_title_desc'  style="">Alt &amp; Title Text / Description</th>
                            <th scope='col' id='additional' class='manage-column column-tags'  style="">Additional</th>
                        </tr>
                    </tfoot>
                    <tbody>
                <?php
                foreach($gallery as $image){
                    $height = $image->meta_data['height'];
                    $width = $image->meta_data['width'];
                    ?>
                    <tr id="picture-<?php echo $image->pid; ?>" class="iedit"  valign="top">
                        <td class="id column-id" scope="row" style=""><?php echo $image->pid; ?></td>
			<td class="thumbnail column-thumbnail"><?php echo $image->thumbHTML; ?></td>
                        <td class="filename column-filename"><strong><?php echo $image->filename; ?></strong></td>
                        <td class="alt_title_desc column-alt_title_desc"><input name="alttext[1]" type="text" style="width:95%; margin-bottom: 2px;" value="<?php echo ""; ?>" /><br/>
							<textarea name="description[1]" style="width:95%; margin-top: 2px;" rows="2" ></textarea>
                         </td>
                         <td class="tags column-tags"><textarea name="tags[1]" style="width:95%;" rows="2"></textarea></td>
                    </tr>
                    <?php
                }
                // rs_print_array($gallery);
                ?>
                    </tbody>
                </table>
                </form>
                <?php
                }
            }
            break;
        default:
            echo "<strong>Posted: </strong>";
            rs_print_array($_POST);
            break;
    }
    
}else{
    include("tweener-default.php");
}
?>
</div>