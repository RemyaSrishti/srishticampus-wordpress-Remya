<?php

/**

* Template Name: Home Page

*

*/

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}

get_header(); 
?>
 <main>
          <!-- banner_section start -->
          <section class="banner_section">
            <?php echo do_shortcode('[masterslider id="1"]'); ?>  
 			
          </section>
          <!-- banner_section end -->
          
          <!-- about_section start -->
		   <?php
			$args = array(
				'post_type'     => 'post',
				'category_name' => 'home-about'
			);
			$my_query = new WP_Query($args);
			if($my_query->have_posts()) {
				while($my_query->have_posts() ) {
					$my_query->the_post();			
			 ?>
          <section class="about_section common-padding">
              <div class="container">
                  <div class="row">
                      <div class="col-lg-6" data-aos="fade-up">
                           <?php
							the_post_thumbnail();
							?>
                      </div>
                      <div class="col-lg-6">
                          <div class="about_content" data-aos="zoom-in">
                              <?php the_content(); ?>                              
                          </div>
						  <div class="about_section_learn_more">
							<a href="<?php the_permalink() ?>"><button class="btn main-button" >Learn More</button></a>
						  </div>
                      </div>
                  </div>
              </div>
          </section>		 
		  <?php
				}
				wp_reset_postdata();
			}     
			?>
          <!-- about_section end -->
          
          <!-- service_section start -->
          <section class="service_section common-padding">
              <div class="container">
                  <div class="row">
                        <div class="col-lg-4"  data-aos="fade-up">
                            <span class="heading-badge">Our Services</span>
                              <h4 class="sub-heading">Services provided by Srishti Campus</h4>
                            <a href="'.site_url('/online-training'). '"><button class="btn main-button" >Learn More</button></a>
                        </div>
						<?php
						$args = array(
							'post_type'     => 'post',
							'category_name' => 'home'
						);
						$my_query = new WP_Query($args);
						if($my_query->have_posts()) {
							while($my_query->have_posts() ) {
								$my_query->the_post();			
						 ?>
                        <div class="col-lg-4">
                            <div class="service-grid" data-aos="fade-up">
                                <div>
                                    <h4><?php the_title(); ?></h4>
                                    <p><?php the_excerpt(); ?></p>
                                </div>
                                <div class="service-grid-footer">
                                    <span class="blue-badge">
                                        <?php
                                            the_post_thumbnail();
                                            ?>
                                    </span>
                                    <a href="<?php the_permalink() ?>"><button class="btn" >Read More <i class="bi bi-arrow-right"></i></button></a>
                                </div>
                            </div>
                        </div>
						<?php
							}
							wp_reset_postdata();
						}     
						?>
                  </div>
              </div>
          </section>
          <!-- service_section end -->
          
           <!-- testimonial_section start -->
          <section class="testimonial_section common-padding">
              <div class="container">
                  <div class="row">
                      <span class="heading-badge">Testimonials</span>
                      <h4 class="sub-heading">Reviews of Student Testimonials from Google</h4>                     
					  <?php echo do_shortcode('[trustindex no-registration=google]'); ?> 
                      <div class="col-lg-12">
                          <div class="w-100 d-flex justify-content-center my-5">
                              <a href="https://www.google.com/search?q=srishti+campus&oq=srishti+campus&aqs=chrome..69i57j46i175i199j0j0i22i30l2j69i60l2j69i65.2764j0j7&sourceid=chrome&ie=UTF-8#lrd=0x3b05b95e87b42537:0x5d73d1ce107e6a53,1,,," target="_blank"><button class="btn main-button" >View All</button></a>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <!-- testimonial_section end -->
          
          <!-- student-placement_section start -->
          <section class="student_placement_section_section common-padding">
              <div class="container">
                  <div class="row">
                      <span class="heading-badge text-center">Student Placements</span>
                      <h4 class="sub-heading text-center">You can be the next in this list</h4>
                     <?php echo do_shortcode('[placed-students-home limit="8"]'); ?> 
                      <div class="col-lg-12">
                          <div class="w-100 d-flex justify-content-center my-5">
                              <a href="'.site_url('/student-placements'). '"><button class="btn main-button" >View All</button></a>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <!-- student-placement_section end -->
          
           <!-- company_section start -->
          <section class="company_section_section common-padding">
              <div class="container-fluid px-5">
                  <div class="row">
                      <span class="heading-badge text-center">Placements</span>
                      <h4 class="sub-heading text-center">Our Students Works At</h4>                      
                     
                      <?php echo do_shortcode('[students-placements-companies limit="30"]'); ?> 
                      
                      <div class="col-lg-12">
                          <div class="w-100 d-flex justify-content-center my-4">
                              <a href="'.site_url('/company-logos'). '"><button class="btn main-button" >View All</button></a>
                          </div>
                      </div>
                  </div>
              </div>
          </section>
          <!-- company_section end -->
          
      </main>

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>


