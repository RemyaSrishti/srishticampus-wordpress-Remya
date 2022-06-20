<?php

/**

* Template Name: Online Training Page

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
                <h3 style="text-transform: uppercase;">Online Training</h3>
            </div>
        </div>
    </div>
</section>

<section class="on-training">
    <div class="container">
        <div class="row">
         <div class="col-lg-12">
            <?php

			if ( have_posts() ) {

				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );
				}
			}

			?>
         </div>
        </div>

        <div class="row">
            <div class="col-12">
                
                <div class="online-tr">
                    <?php
					global $wpdb;	
					$total = $wpdb->get_var( "SELECT COUNT(id) FROM `course_details`" );
					$items_per_page = 8;
					$page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
					$offset = ( $page * $items_per_page ) - $items_per_page;
					$getTraining = $wpdb->get_results("SELECT * FROM course_details ORDER by priority ASC LIMIT ${offset}, ${items_per_page}");	

                    if(!empty($getTraining))
					{
						foreach($getTraining as $training)
						{
                        ?>
                        <div class="online-itemas">
                            <div class="trainng-img">
                                <?php
                                echo '<img src="https://www.srishticampus.com/packageImages/' .$training->image . '"  alt="image"/>';
                                ?>
                            </div>
                            <div class="training-description">
                                <h3 class="training"> <?php echo $training->name; ?> </h3>
                                <div class="dur-tr">
                                    <ul>
                                        <li><i class="fas fa-rupee-sign" aria-hidden="true"></i><?php echo $training->cost; ?> </li>
                                        <li><i class="fas fa-clock"></i><?php echo $training->hour; ?></li>
                                        <li><i class="fas fa-user-graduate"></i> <?php echo $training->placement; ?>% placement</li>
                                    </ul>
                                </div>
                                <div class="training-ul">
                                    <div class="heiBx">
                                        <p class="desps">
                                            <?php echo $training->description; ?>
                                        </p>
                                        <div class="dur-tr rateParts">
											<div class="rating_list">
                                                <a href="https://www.srishticampus.com/comments.php?tech=<?php echo $training->technology;?>">
                                                <?php
												$ratingResult = $wpdb->get_results("SELECT AVG(rating) AS avg FROM reviews where technology=".$training->technology."");                                                
												foreach($ratingResult as $rating)
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        $ratingClass = "far";
                                                        if ($i <= ceil($rating->avg)) {
                                                            $ratingClass = "fas";
                                                        }
                                                        ?>
                                                       
                                                        <span class=" fa-star  <?php echo $ratingClass; ?>" aria-hidden="true"></span>
                                                        <!-- </a> -->
                                                    <?php } ?>
                                                </a>
                                            </div>
                                           
											<div class="comments_list">
												<i class="fas fa-comments" aria-hidden="true"></i>
												<!-- <div class="dur-tr"> -->
												<?php
												echo '<a href="https://www.google.com/search?q=srishti+campus&oq=srishti+campus&aqs=chrome..69i57j46i175i199j0j0i22i30l2j69i60l2j69i65.2764j0j7&sourceid=chrome&ie=UTF-8#lrd=0x3b05b95e87b42537:0x5d73d1ce107e6a53,1,,," target="_blank">Comments </a>';
												?>
												<!-- </div> -->
											</div>                                            
                                        </div>
                                    </div>
                                </div> <!-- training-ul end -->
                                <div class="view-details">
                                    <?php
                                   
                                        echo '<a class="view-training" href="'.site_url('/?p='.$training->course_page.''). '">View Details </a>';
                                        echo '<a class="view-training entroll-training" href="https://www.srishticampus.com/enroll.php?id='.$training->id.'">ENROLL NOW </a>';
                                   
                                    ?>
                                </div>
                            </div><!-- training-description end -->
                        </div>
                    <?php
						}
					} else {
						echo 'No Details Found';
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


