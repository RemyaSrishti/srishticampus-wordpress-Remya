<?php
/*
* Plugin Name: gallery
* Description: This plugin to create custom gallery from database using WP_List_Table class.
* Version:     1.1.0
* Author:      Remya
*/

defined( 'ABSPATH' ) or die( '¡Sin trampas!' );

add_action( 'plugins_loaded', 'wpbh_plugin_load_textdomain' );

function wpbh_plugin_load_textdomain() {
load_plugin_textdomain( 'wpbh', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

global $wpbh_db_version;
$wpbh_db_version = '1.0.1'; 


function wpbh_install()
{
    global $wpdb;
    global $wpbh_db_version;

    $table_name = $wpdb->prefix . 'gallery'; 


    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      image_url VARCHAR (50) NOT NULL,     
      PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('wpbh_db_version', $wpbh_db_version);

    $installed_ver = get_option('wpbh_db_version');
    if ($installed_ver != $wpbh_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          image_url VARCHAR (50) NOT NULL,		 
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('wpbh_db_version', $wpbh_db_version);
    }
}

register_activation_hook(__FILE__, 'wpbh_install');


function wpbh_install_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'gallery'; 

}

register_activation_hook(__FILE__, 'wpbh_install_data');


function wpbh_update_db_check()
{
    global $wpbh_db_version;
    if (get_site_option('wpbh_db_version') != $wpbh_db_version) {
        wpbh_install();
    }
}

add_action('plugins_loaded', 'wpbh_update_db_check');



if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class Custom_Table_Example2_List_Table extends WP_List_Table
 { 
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'contact',
            'plural' => 'contacts',
        ));
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }  

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }
	
	function column_image_url($item)
    {
		  $actions = array(
            'edit' => sprintf('<a href="?page=gallery_form&id=%s">%s</a>', $item['id'], __('Edit', 'wpbh')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'wpbh')),
        );
        return sprintf('%s %s',
            '<img src="'.site_url('/wp-content/uploads/gallery/'.$item['image_url'].'').'" width="150px" alt="Girl in a jacket">',
            $this->row_actions($actions)
        );
    }
	
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', 
            'image_url' => __('Image', 'wpbh')
			
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'image_url' => array('id', true)
            
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gallery'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'gallery'; 

        $per_page = 10; 

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);
       
        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");


        //$paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
		$paged = isset($_REQUEST['paged']) ?  max(0, intval($_REQUEST['paged'] -1) * 10)  : 0;
		$orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
		$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';


        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);


        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page) 
        ));
    }
}

function wpbh_admin_menu()
{
    add_menu_page(__('Gallery', 'wpbh'), __('Gallery', 'wpbh'), 'activate_plugins', 'gallery', 'wpbh_gallery_page_handler');
    add_submenu_page('Gallery', __('Gallery', 'wpbh'), __('Gallery', 'wpbh'), 'activate_plugins', 'gallery', 'wpbh_gallery_page_handler');
   
    add_submenu_page('gallery', __('Add new', 'wpbh'), __('Add new', 'wpbh'), 'activate_plugins', 'gallery_form', 'wpbh_gallery_form_page_handler');
}

add_action('admin_menu', 'wpbh_admin_menu');


function wpbh_gallery_page_handler()
{
    global $wpdb;

    $table = new Custom_Table_Example2_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Images deleted: %d', 'wpbh'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Gallery', 'wpbh')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=gallery_form');?>"><?php _e('Add new', 'wpbh')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="contacts-table" method="POST">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php  $table->display() ?>
    </form>

</div>
<?php
}


function wpbh_gallery_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'gallery'; 

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'image_url' => ''
    );


    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        
        $item = shortcode_atts($default, $_REQUEST);     

        $item_valid = wpbh_validate_contact($item);
		
        if ($item_valid === true) {
            if (!isset($_GET['id'])) {
				$name = $_FILES['uploaded']['name'];
				$ran = rand(1000,10000000);
				$upload_dir = wp_upload_dir();
				$uploads = $upload_dir['basedir'];
				$date=date("Y-m-d");
				$file_extension = pathinfo($_FILES['uploaded']['name']);
				$file_extension = $file_extension["extension"];
				/*$file_name = $ran.$key.$date.".jpg";*/
				$file_name = $ran.$i.$date.".".$file_extension;
				$file_size =$_FILES['uploaded']['size'];
				$file_tmp =$_FILES['uploaded']['tmp_name'];
				$file_type=$_FILES['uploaded']['type'];
				$filedest = $uploads . '/gallery/' . $file_name;
				move_uploaded_file($file_tmp,$filedest);			
				
				$item=array('image_url'=>$file_name);
				$result = $wpdb->insert($table_name, $item);
				$item['id'] = $wpdb->insert_id;
			
                if ($result) {
                    $message = __('Image was successfully saved', 'wpbh');
                } else {
                    $notice = __('There was an error while saving Image', 'wpbh');
                }
            } else {
			
				$name = $_FILES['uploaded']['name'];
				$ran = rand(1000,10000000);
				$upload_dir = wp_upload_dir();
				$uploads = $upload_dir['basedir'];
				$date=date("Y-m-d");
				$file_extension = pathinfo($_FILES['uploaded']['name']);
				$file_extension = $file_extension["extension"];
				/*$file_name = $ran.$key.$date.".jpg";*/
				$file_name = $ran.$i.$date.".".$file_extension;
				$file_size =$_FILES['uploaded']['size'];
				$file_tmp =$_FILES['uploaded']['tmp_name'];
				$file_type=$_FILES['uploaded']['type'];
				$filedest = $uploads . '/gallery/' . $file_name;
				move_uploaded_file($file_tmp,$filedest);			
				
				$item=array('id' => $item['id'],'image_url'=>$file_name);
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Image was successfully updated', 'wpbh');
                } else {
                    $notice = __('There was an error while updating Image', 'wpbh');
                }
            }
        } else {
            
            $notice = $item_valid;
        }
    }
    else {
        
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);			
            if (!$item) {
                $item = $default;
                $notice = __('Image not found', 'wpbh');
            }
        }
    }

    
    add_meta_box('gallery_form_meta_box', __('Gallery', 'wpbh'), 'wpbh_gallery_form_meta_box_handler', 'gallery', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Gallery', 'wpbh')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=gallery');?>"><?php _e('back to list', 'wpbh')?></a>
    </h2>

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

     <form enctype="multipart/form-data" action="" method="post">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <?php do_meta_boxes('gallery', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'wpbh')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function wpbh_gallery_form_meta_box_handler($item)
{
    ?>
<tbody >
	<style>
    div.postbox {width: 70%; margin-left: 73px;}
	</style>	
		
	<div class="formdata">		
		
    <form enctype="multipart/form-data" action="" method="post">
       
		<p>
            <label for="Title2"><?php _e('image:', 'wpbh')?></label> 
		<br>	
            <input name="uploaded" type="file" maxlength="20" style="padding: 0px !important;width: 202px;line-height: 25px;height: auto !important;color: black;background: none repeat scroll 0px 0px #ffffff;border: none;display: inline;border-radius: 0px;font-weight: normal;" />
			
			<?php if($item['id']!=''){ ?>
				
				<img src="<?php echo site_url('/wp-content/uploads/gallery/'.$item['image_url'].'');?>" width="150" alt="Girl in a jacket">
			<?php }?>
		  
		  
		  </p>
		</form>
		</div>
</tbody>
<?php
}


function wpbh_validate_contact($item)
{
    $messages = array();

   // if (empty($item['uploaded'])) $messages[] = __('Image is required', 'wpbh');

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}


function wpbh_languages()
{
    load_plugin_textdomain('wpbh', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'wpbh_languages');
