<?php

/**

* Template Name: Company Logos Page

*

*/

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}
get_header(); 
?>
<main id="site-content">

<section class="project-banner">
    <div class="container">
        <div class="banner-sec">
            <div class="projectheading">
                <h3 style="text-transform: uppercase;">Placements</h3>
            </div>
        </div>
    </div>
</section>

<section class="gallery" >
	<div class="container">
		 <ul class="m-3">
		 <?php echo do_shortcode('[all_company-logos]'); ?> 
		</ul>
	 </div>
</section>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>


