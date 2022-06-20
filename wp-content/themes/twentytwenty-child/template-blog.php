<?php



/**

* Template Name: Blog Page

*

*/



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}

get_header(); 

?>
<main id="site-content">
<section class="bnner_sec-rs gallery">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="banner-sec">
                    <div class="hdng gall">
                        <h3 style="text-transform: uppercase;">Blog</h3>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="sics-abt">
                    <div class="blog-main">

                        <?php
						$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
						$args = array(
							'post_type'     => 'post',
							'category_name' => 'blog',
							'posts_per_page' => 5,
							'paged'         => $paged,
							'orderby'       => 'date',
						);
						$my_query = new WP_Query($args);
							if($my_query->have_posts()) {
								while($my_query->have_posts() ) {
									$my_query->the_post();
                            ?>
                            <div class="blog-card">
                                <h2><?php the_title(); ?></h2>
                                <h5><?php the_time( get_option( 'date_format' ) ); ?></h5>
                                <div class="blog-fflex">
                                    <div>
                                        <div class="blog-img" >
                                            <?php
                                            the_post_thumbnail();
                                            ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="blog-desc main">
										<?php the_excerpt(); ?>
                                        </div>
                                        <a href="<?php the_permalink() ?>">
                                            <button class="readmore">READ MORE</button>
                                        </a>
                                    </div>
                                </div>
                             </div>
                                <?php
								}
								 wp_reset_postdata();
                            }							
                            ?>
<div class="pag">
					<?php
					$big = 999999999; // need an unlikely integer
							echo paginate_links( array(
								'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format' => '',
								'prev_text' => __('&laquo;', 'prev'),
								'next_text' => __('&raquo;', 'next'),
								'current' => max( 1, get_query_var('paged') ),
								'total' => $my_query->max_num_pages
							) );
					?>
					</div>
                       
                    </div>
					
                </div>
            </div>
        </div>
	</div>
</section>
</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>


