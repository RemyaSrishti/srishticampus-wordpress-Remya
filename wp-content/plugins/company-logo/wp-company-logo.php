<?php
/*
* Plugin Name: Company Logos
* Description: This plugin to upload custom company logos from database using WP_List_Table class.
* Version:     1.1.0
* Author:      Remya
*/

defined( 'ABSPATH' ) or die( '¡Sin trampas!' );

add_action( 'plugins_loaded', 'wpbh_plugin_load_textdomains' );

function wpbh_plugin_load_textdomains() {
load_plugin_textdomain( 'wpbh', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

global $wpbh_db_version;
$wpbh_db_version = '1.0.1'; 


function wpbh_installs()
{
    global $wpdb;
    global $wpbh_db_version;

    $table_name = $wpdb->prefix . 'companylogos'; 


    $sql = "CREATE TABLE " . $table_name . " (
      id int(11) NOT NULL AUTO_INCREMENT,
      logo VARCHAR (50) NOT NULL, 
	  display_home INT (11) NOT NULL,	  
      PRIMARY KEY  (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('wpbh_db_version', $wpbh_db_version);

    $installed_ver = get_option('wpbh_db_version');
    if ($installed_ver != $wpbh_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          logo VARCHAR (50) NOT NULL,	
		  display_home INT (11) NOT NULL,			  
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('wpbh_db_version', $wpbh_db_version);
    }
}

register_activation_hook(__FILE__, 'wpbh_installs');


function wpbh_install_datas()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'companylogos'; 

}

register_activation_hook(__FILE__, 'wpbh_install_datas');


function wpbh_update_db_checks()
{
    global $wpbh_db_version;
    if (get_site_option('wpbh_db_version') != $wpbh_db_version) {
        wpbh_installs();
    }
}

add_action('plugins_loaded', 'wpbh_update_db_checks');



if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class Custom_Table_Example2_List_Tables extends WP_List_Table
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
	
	function column_logo($item)
    {
		  $actions = array(
            'edit' => sprintf('<a href="?page=logo_form&id=%s">%s</a>', $item['id'], __('Edit', 'wpbh')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'wpbh')),
        );
        return sprintf('%s %s',
            '<img src="'.site_url('/wp-content/uploads/company_logos/'.$item['logo'].'').'" width="150px" alt="Girl in a jacket">',
            $this->row_actions($actions)
        );
    }
	
	function column_display_home($item)
    {
		if($item['display_home'] == 1)  {
			return '<div class="wp-menu-image dashicons-before dashicons-star-filled" aria-hidden="true"><br></div>';
		}
    }
	
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', 
            'logo' => __('Company Logos', 'wpbh'),
			'display_home' => __('', 'wpbh')
			
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'logo' => array('id', true)
            
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete',
			'add_to_home' => 'Add to Home Page',
			'remove_from_home' => 'Remove from Home Page'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'companylogos'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }	
		
		if ('add_to_home' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("UPDATE $table_name SET display_home=1 WHERE id IN($ids)");
            }
        }
		
		if ('remove_from_home' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("UPDATE $table_name SET display_home=0 WHERE id IN($ids)");
            }
        }			
    }

    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'companylogos'; 

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

function wpbh_admin_menus()
{
    add_menu_page(__('Company Logos', 'wpbh'), __('Company Logos', 'wpbh'), 'activate_plugins', 'logo', 'wpbh_logo_page_handler');
    add_submenu_page('Company Logos', __('Company Logos', 'wpbh'), __('Company Logos', 'wpbh'), 'activate_plugins', 'logo', 'wpbh_logo_page_handler');
   
    add_submenu_page('logo', __('Add new', 'wpbh'), __('Add new', 'wpbh'), 'activate_plugins', 'logo_form', 'wpbh_logo_form_page_handler');
}

add_action('admin_menu', 'wpbh_admin_menus');


function wpbh_logo_page_handler()
{
    global $wpdb;

    $table = new Custom_Table_Example2_List_Tables();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Images deleted: %d', 'wpbh'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Company Logo', 'wpbh')?> <a class="add-new-h2"
                                 href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=logo_form');?>"><?php _e('Add new', 'wpbh')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="contacts-table" method="POST">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php  $table->display() ?>
    </form>

</div>
<?php
}


function wpbh_logo_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'companylogos'; 

    $message = '';
    $notice = '';

    $default = array(
        'id' => 0,
        'logo' => '',
		'display_home' => 0
    );


    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {		
        
        $item = shortcode_atts($default, $_REQUEST);     

        $item_valid = wpbh_validate_contacts($item);
		
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
				$filedest = $uploads . '/company_logos/' . $file_name;
				move_uploaded_file($file_tmp,$filedest);	

				$is_disp_home = 0;
				if(isset($_POST['display_home'])) {
					$is_disp_home = 1;
				} else {
					$is_disp_home = 0;
				}				
				
				$item=array('logo'=>$file_name,'display_home'=>$is_disp_home);
				$result = $wpdb->insert($table_name, $item);
				$item['id'] = $wpdb->insert_id;
			
                if ($result) {
                    $message = __('Image was successfully saved', 'wpbh');
                } else {
                    $notice = __('There was an error while saving Image', 'wpbh');
                }
            } else {				
				$is_disp_home = 0;
				if(isset($_POST['display_home'])) {
					$is_disp_home = 1;
				} else {
					$is_disp_home = 0;
				}	
			
				if (!empty($_FILES['uploaded']['name'])){
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
					$filedest = $uploads . '/company_logos/' . $file_name;
					move_uploaded_file($file_tmp,$filedest);
					
					$item=array('id' => $item['id'],'logo'=>$file_name,'display_home'=>$is_disp_home);
				} else {
					$item=array('id' => $item['id'],'display_home'=>$is_disp_home);
				}
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));	
				$item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);				
                if ((0 === $result) || ( 0 < $result)) {
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

    
    add_meta_box('logo_form_meta_box', __('Company Logo', 'wpbh'), 'wpbh_logo_form_meta_box_handler', 'logo', 'normal', 'default');

    ?>
<div class="wrap">
    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Company Logo', 'wpbh')?> <a class="add-new-h2"
                                href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=logo');?>"><?php _e('back to list', 'wpbh')?></a>
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
                    
                    <?php do_meta_boxes('logo', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'wpbh')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

function wpbh_logo_form_meta_box_handler($item)
{
    ?>
<tbody >
	<style>
    div.postbox {width: 70%; margin-left: 73px;}
	</style>	
		
	<div class="formdata">		
		
    <form enctype="multipart/form-data" action="" method="post">
        <p>
            <label for="Title2"><?php _e('Would you like to display it in home page:', 'wpbh')?></label> 
				
            <input type="checkbox" name="display_home" id="display_home" value="1" <?php if(($item['id']!='') && ($item['display_home'] == 1)){ echo "checked"; }?>/>		  
		  </p>		
		<p>
            <input name="uploaded" type="file" maxlength="20" style="padding: 0px !important;width: 202px;line-height: 25px;height: auto !important;color: black;background: none repeat scroll 0px 0px #ffffff;border: none;display: inline;border-radius: 0px;font-weight: normal;" />
					  
		  </p>		 
		  <p>
            <label for="Title2"><?php _e('Image:', 'wpbh')?></label> 
			<?php if(($item['id'] != '') && ($item['id'] != 0) && ($item['logo'] != '')){ ?>
				
				<img src="<?php echo site_url('/wp-content/uploads/company_logos/'.$item['logo'].'');?>" width="150" alt="Girl in a jacket">
			<?php }?>
		</p>
		</form>
		</div>
</tbody>
<?php
}


function wpbh_validate_contacts($item)
{
    $messages = array();

   // if (empty($item['uploaded'])) $messages[] = __('Image is required', 'wpbh');
   if (!isset($_GET['id'])) {
	    if (empty($_FILES['uploaded']['name'])) $messages[] = __('Image is required', 'wpbh');
   } 

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}


function wpbh_language()
{
    load_plugin_textdomain('wpbh', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'wpbh_language');
