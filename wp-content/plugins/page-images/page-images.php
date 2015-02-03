<?php
/* Plugin Name: WP Page Images 
 * Version: 0.1.0
 */

add_action( 'admin_init', 'pageImages_plugin_init', 2);
add_action( 'admin_head-post.php', 'pageImages_design' );
add_action( 'admin_head-post-new.php', 'pageImages_design' );
add_action('save_post', 'pageImages_save_data', 10, 2 );


function pageImages_design(){ ?>
        <script type='text/javascript' src='<?php echo plugins_url('page-images/js') ?>/script.js'></script>
        <link rel='stylesheet' href='<?php echo plugins_url('page-images/css') ?>/style.css' type='text/css'/>
<?php
}
function pageImages_plugin_init(){

      add_meta_box(
        'slider_page_images',
        'Slider Images',
        'pageImages_view',
        'page',
        'normal',
        'high'
    );

    add_meta_box(
        'slider_page_images_info',
        'Info Block',
        'pageImages_info',
        'page',
        'normal',
        'high'
    );
/*
    add_meta_box(
        'slider_page_images',
        'Images',
        'pageImages_view',
        'clients',
        'normal',
        'high'
    );
 
    add_meta_box(
        'slider_page_images',
        'Images',
        'pageImages_view',
        'licensing',
        'normal',
        'high'
    );

    add_meta_box(
        'slider_page_images',
        'Images',
        'pageImages_view',
        'workwear_promotional',
        'normal',
        'high'
    );
         */  
}

if(function_exists('f_sort')){
    function f_sort($key, $ord ){ 
        return function ($a, $b) use ($key, $ord) 
        { 
            if($ord == 'ASC'){
                return strcasecmp($a[$key], $b[$key] );
            }elseif($ord == 'DESC'){
                return strcasecmp($b[$key], $a[$key]);
            }
        }; 
}
}

function sort_by (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
    return $array;
}

function pageImages_save_data( $post_id, $post_object ){
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  
        return;
    
    if ( 'revision' == $post_object->post_type )
        return;
        
    $data = array();
        
    if($_POST['pageImages_data']){
        for($i = 0; $i <= count($_POST['pageImages_data']['image_url']); $i++){
            if($_POST['pageImages_data']['image_url'][$i]){
                $data[] = array(
                                    'image_url' => $_POST['pageImages_data']['image_url'][$i],
                                    'image_id' => $_POST['pageImages_data']['image_id'][$i],
                                    'position'  => $_POST['pageImages_data']['position'][$i],
                                    'title'  => $_POST['pageImages_data']['title'][$i],
                                    'description'  => $_POST['pageImages_data']['description'][$i],
                                    'tab'  => $_POST['pageImages_data']['tab'][$i],
                                    'link'  => $_POST['pageImages_data']['link'][$i],
                                    'use_for'  => $_POST['pageImages_data']['use_for'][$i],
                                );
            }
        }
        sort_by($data, 'position');
        if($data){
            update_post_meta( $post_id, 'pageImages_data', $data );
        }
        else{
            delete_post_meta( $post_id, 'pageImages_data');
        }
    }
    else{
         delete_post_meta( $post_id, 'pageImages_data' );
    }
}

function getPageImages($postId){
    $pageImages_data = get_post_meta( $postId, 'pageImages_data', true );
    return $pageImages_data;
}

function pageImages_view(){
    global $post;
    $pageImages_data = get_post_meta( $post->ID, 'pageImages_data', true );
    wp_nonce_field( plugin_basename( __FILE__ ), 'noncename' );
?>

<div id="dynamic_form">
    <div id="field_wrap">
    <?php if ( $pageImages_data ): ?>
        
        <?php if ( is_admin() && get_the_title($_GET['post']) == 'Home') : ?>
            <?php sort_by($pageImages_data, 'position'); ?>
        <?php endif; ?>
        
        <?php foreach($pageImages_data as $data): ?>
        <?php if($data['use_for']=='for_slider'){ ?>
        <?php $croped_image = wp_get_attachment_image_src( $data['image_id'], 'info_block'); ?>
        <div class="field_row slide-item">
            
            <div class="image-block-title slider-item-title"><?php echo _e('Home page slide content') ?></div>
            <div class="field-content">
                <div class="field_left">
                    <div class="form_field">
                        <input value="<?php echo $data['use_for']; ?>" type="hidden" name="pageImages_data[use_for][]" />
                        <input type="hidden" class="image_data_field_id" name="pageImages_data[image_id][]" value="<?php echo $data['image_id']; ?>" />
                        <label>Image URL</label>
                        <input type="text" class="image_data_field" name="pageImages_data[image_url][]" value="<?php echo $data['image_url'] ?>" />
                        <input class="button" type="button" value="Choose File" onclick="pageImages_add_image(this)" /><input class="button" type="button" value="Remove" onclick="pageImages_reomove_field(this)" /><br/>
                        <div class="field_left image_wrap">
                            <img src="<?php echo $croped_image[0]; ?>" width="170" />
                        </div>
                    </div>
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Title') ?></label>
                        <input value="<?php echo $data['title'] ?>" type="text" name="pageImages_data[title][]" />
                    </div>           
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Position') ?></label>
                        <input value="<?php echo $data['position'] ?>" type="text" name="pageImages_data[position][]" />
                    </div>           
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Description') ?></label>
                        <textarea value="" type="text" name="pageImages_data[description][]" ><?php echo $data['description'] ?></textarea>
                    </div>  
                    <div class="form_field">
                        <label><?php echo _e('Tab') ?></label>
                        <input value="<?php echo $data['tab'] ?>" type="text" name="pageImages_data[tab][]" />
                    </div>       
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Link') ?></label>
                        <input value="<?php echo $data['link'] ?>" type="text" name="pageImages_data[link][]" />
                    </div>           
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php
        }
        endforeach;
    endif;
    ?>
    </div>
</div>
<div style="display:none" id="master-row">
    <div class="field_row add-item-block">
        <div class="field-content">
            <div class="field_left">
                <div class="form_field">
                    <input value="for_slider" type="hidden" name="pageImages_data[use_for][]" />
                    <input value="" class="image_data_field_id" type="hidden" name="pageImages_data[image_id][]" />
                    <label><?php echo _e('Image URL') ?></label>
                    <input class="image_data_field" value="" type="text" name="pageImages_data[image_url][]" />
                    <input type="button" class="button" value="Choose File" onclick="pageImages_add_image(this)" /><input class="button" type="button" value="Remove" onclick="pageImages_reomove_field(this)" /><br/>
                    <div class="field_left image_wrap"></div>   
                </div>
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Title') ?></label>
                    <input value="" type="text" name="pageImages_data[title][]" />
                </div>           
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Position') ?></label>
                    <input value="" type="text" name="pageImages_data[position][]" />
                </div>
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Description') ?></label>
                    <textarea value="" type="text" name="pageImages_data[description][]" ></textarea>
                </div>
                <div class="form_field">
                    <label><?php echo _e('Tab') ?></label>
                    <input value="" type="text" name="pageImages_data[tab][]" />
                </div> 
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Link') ?></label>
                    <input value="" type="text" name="pageImages_data[link][]" />
                </div>           
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div id="add_field_row">
    <input class="button" type="button" value="Add" onclick="pageImages_add_field_row(this);" />
</div>

<?php }
function pageImages_info(){
    global $post;
    $pageImages_data = get_post_meta( $post->ID, 'pageImages_data', true );
    wp_nonce_field( plugin_basename( __FILE__ ), 'noncename' );
?>

<div id="dynamic_form">
    <div id="field_wrap">
    <?php if ( $pageImages_data ): ?>
        
        <?php if ( is_admin() && get_the_title($_GET['post']) == 'Home') : ?>
            <?php sort_by($pageImages_data, 'position'); ?>
        <?php endif; ?>
        
        <?php foreach($pageImages_data as $data): ?>
        <?php if($data['use_for']=='Info block'){ ?>
        <?php $croped_image = wp_get_attachment_image_src( $data['image_id'],'info_block'); ?>
        <div class="field_row">
            
            <div class="image-block-title info-block-item-title"><?php echo _e('Info block content') ?></div>
            <div class="field-content">
                <div class="field_left">
                    <div class="form_field">
                        <input value="<?php echo $data['use_for']; ?>" type="hidden" name="pageImages_data[use_for][]" />
                        <input type="hidden" class="image_data_field_id" name="pageImages_data[image_id][]" value="<?php echo $data['image_id']; ?>" />
                        <label>Image URL</label>
                        <input type="text" class="image_data_field" name="pageImages_data[image_url][]" value="<?php echo $data['image_url'] ?>" />
                        <input class="button" type="button" value="Choose File" onclick="pageImages_add_image(this)" /><input class="button" type="button" value="Remove" onclick="pageImages_reomove_field(this)" /><br/>
                        <div class="field_left image_wrap">
                            <img src="<?php echo $croped_image[0]; ?>" width="170" />
                        </div>
                    </div>
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Title') ?></label>
                        <input value="<?php echo $data['title'] ?>" type="text" name="pageImages_data[title][]" />
                    </div>           
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Position') ?></label>
                        <input value="<?php echo $data['position'] ?>" type="text" name="pageImages_data[position][]" />
                    </div>           
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Description') ?></label>
                        <textarea value="" type="text" name="pageImages_data[description][]" ><?php echo $data['description'] ?></textarea>
                    </div>        
                </div>
                <div class="field_left">
                    <div class="form_field">
                        <label><?php echo _e('Link') ?></label>
                        <input value="<?php echo $data['link'] ?>" type="text" name="pageImages_data[link][]" />
                    </div>           
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php
        }
        endforeach;
    endif;
    ?>
    </div>
</div>
<div style="display:none" id="master-row">
    <div class="field_row add-item-block">
        <div class="field-content">
            <div class="field_left">
                <div class="form_field">
                    <input value="Info block" type="hidden" name="pageImages_data[use_for][]" />
                    <input value="" class="image_data_field_id" type="hidden" name="pageImages_data[image_id][]" />
                    <label>Image URL</label>
                    <input class="image_data_field" value="" type="text" name="pageImages_data[image_url][]" />
                    <input type="button" class="button" value="Choose File" onclick="pageImages_add_image(this)" /><input class="button" type="button" value="Remove" onclick="pageImages_reomove_field(this)" /><br/>
                    <div class="field_left image_wrap"></div>   
                </div>
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Title') ?></label>
                    <input value="" type="text" name="pageImages_data[title][]" />
                </div>           
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Position') ?></label>
                    <input value="" type="text" name="pageImages_data[position][]" />
                </div>
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Description') ?></label>
                    <textarea value="" type="text" name="pageImages_data[description][]" ></textarea>
                </div>
            </div>
            <div class="field_left">
                <div class="form_field">
                    <label><?php echo _e('Link') ?></label>
                    <input value="" type="text" name="pageImages_data[link][]" />
                </div>           
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>
<div id="add_field_row">
    <input class="button" type="button" value="Add" onclick="pageImages_add_field_row(this);" />
</div>
<?php }