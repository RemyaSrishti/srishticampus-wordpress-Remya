<?php

/**

* Template Name: Student Placements Page

*

*/

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}
get_header(); 
?>
<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>
<!-- or -->
<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.js"></script>

<main id="site-content">

<section class="project-banner">
    <div class="container">
        <div class="banner-sec">
            <div class="projectheading">
                <h3 style="text-transform: uppercase;">Student Placements</h3>
            </div>
        </div>
    </div>
</section>

<section class="g-sec placement">
    <div class="container">

      <div class="row">
         <div class="col-lg-12">
        
      </div>
    </div>

        <div class="row">
            <div class="col-lg-12">

              <div class="row" id="sss" style="justify-content: center;">
			  <?php
			  global $wpdb;
	
	$total = $wpdb->get_var( "SELECT COUNT(id) FROM `placed_students`" );
	$items_per_page = 8;
	$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	$offset = ( $page * $items_per_page ) - $items_per_page;	
	$getPlacements = $wpdb->get_results("SELECT * from `placed_students` order by `id` desc limit ${offset}, ${items_per_page}");	
	
	if(!empty($getPlacements))
	{
		foreach($getPlacements as $placements)
		{
			 ?> 
			 
			 <div class="col-md-4 col-lg-3 aa">
                      <div class="grids-bx placBx">
                        <div class="jobtitles prfSection">
                          <div class="prfImgs">                             
                              <img src="https://www.srishticampus.com/placedstudents/<?php echo $placements->image; ?>" alt="profile">
                          </div>
                          <h3><?php echo $placements->name; ?></h3>
                        </div>
                        <div class="placeDecp">
                          <p><i class="fa fa-suitcase"></i>  <?php echo $placements->title; ?></p>
                          <p><i class="fa fa-id-card-alt"></i>  <?php echo $placements->company; ?></p>                         
                        </div>
                        <div class="clear"></div>
                      </div>
                  </div>
				  
		 <?php	  
			  }
	} else {
		$html = "No Placements Found";
	}	
	 ?> 
	 
	 <div class="pag"><?php	echo paginate_links( array(
					'base' => add_query_arg( 'cpage', '%#%' ),
					'format' => '',
					'prev_text' => __('&laquo;', 'prev'),
					'next_text' => __('&raquo;', 'next'),
					'total' => ceil($total / $items_per_page),
					'current' => $page
				)) ?> </div>
				
			  
			  
         </div>

		 
		 
		 
		 <div class="page-load-status">
  <div class="loader-ellips infinite-scroll-request">
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
    <span class="loader-ellips__dot"></span>
  </div>
  <p class="infinite-scroll-last">End of content</p>
  <p class="infinite-scroll-error">No more pages to load</p>
</div>


		 
		 
		 
                       </div>
                    </div>
                </div>
            </section>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>


