<?php

/**

* Template Name: Gallery Page

*

*/

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}

get_header(); 
global $wpdb;
$upload_dir   = wp_upload_dir();
$total = $wpdb->get_var( "SELECT COUNT(id) FROM `wp_gallery`" );
$items_per_page = 9;
$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset = ( $page * $items_per_page ) - $items_per_page;	
$getImages = $wpdb->get_results("SELECT *  FROM `wp_gallery` ORDER BY id LIMIT ${offset}, ${items_per_page}");
?>
<main id="site-content">

<section class="project-banner">
    <div class="container">
        <div class="banner-sec">
            <div class="projectheading">
                <h3 style="text-transform: uppercase;">Gallery</h3>
            </div>
        </div>
    </div>
</section>

<section class="g-sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="galley-rs" id="page1">
				<?php
					if(!empty($getImages))
					{
						foreach($getImages as $images)
						{
				?>
						<div class="imgss">
							<a target="_blank" href="images/img001.jpg">
								<img src="<?php echo $upload_dir['baseurl'].'/gallery/'.$images->image_url;?>" alt="Cinque Terre">
							</a>

						</div>
                   <?php
						}
					} else {
						echo 'No images found';
					}
				?>
                </div>
				<div class="pag">
				<?php
				echo paginate_links( array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'prev_text' => __('&laquo;', 'prev'),
        'next_text' => __('&raquo;', 'next'),
        'total' => ceil($total / $items_per_page),
        'current' => $page
    ));
				?>
				</div>  
            </div>
        </div>
    </div>
</section>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>


