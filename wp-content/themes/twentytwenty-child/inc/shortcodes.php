<?php
/**
 * For managing shortcodes
 **/
add_shortcode("all-courses", "show_all_courses");
function show_all_courses($atts) {	
	$id = (!empty($atts['id'])) ? $atts['id'] : 0;
	$html .= '<section class="on-training">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="online-tr">';
	if($id != 0) {		
		global $wpdb;
		$sql = "SELECT *  FROM `course_details` WHERE technology='".$id."'";
		$getCourses = $wpdb->get_results($sql);	
		//print_r($getCourses);
		if(!empty($getCourses))
		{
			foreach($getCourses as $courses)
			{
				$html .= '<div class="online-itemas cert-training">
                            <div class="course-img">
                                <img src="https://www.srishticampus.com/packageImages/'.$courses->image.'" alt="image">                            
							</div>
                            <div class="training-description"> 
								<h3 class="training">'.$courses->name.'</h3>
                                <div class="dur-tr">
                                    <ul>                                        
                                        <li><i class="fas fa-clock" aria-hidden="true"></i>'.$courses->hour.' hrs </li>
                                        <li><i class="fas fa-user-graduate" aria-hidden="true"></i> '.$courses->placement.'% placement</li>
                                    </ul>
                                </div>
								<div class="view-details">
									<a class="view-training edit" href="#" data-toggle="modal" data-target="#modalRegisterForm">FEES</a>                                    
									<a class="view-training edit" href="#" data-toggle="modal" data-target="#modalRegisterForm">SYLLABUS</a>
									<a class="view-training entroll-training editenroll" href="https://www.srishticampus.com/login_new_login.php">ENROLL NOW</a>
								</div>								                                                           
                            </div>							
                        </div>';
			}
		} else {
			$html = "No Courses Found";
		}
	} else {
		$html = "No Courses Found";
	}
	
	$html .= ' </div>
            </div>
        </div>
    </div>
           
</section>';
	return $html;
}

add_shortcode("academic-projects", "show_academic_projects");
function show_academic_projects($atts) {
	$html .= '<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="latestnews">
                    <div class="why">
                        <h3><span>Our</span>projects</h3>
                    </div>
                    <div class="main-news">';		
	global $wpdb;
	$sql = "SELECT *  FROM `projects`";
	$getProjects = $wpdb->get_results($sql);	
	
	if(!empty($getProjects))
	{
		foreach($getProjects as $projects)
		{
			$html .= ' <div class="news">
                            <div class="placement-news projectnews">
                                <h2>"'.$projects->name.'"</h2>
                                <p>'.substr($projects->details,0,119).'';
								if (strlen($projects->details) > 119){
									$html .= '...';
								}
								$html .= '</p>
                                <button class="readmore" data-toggle="modal" data-target="#modalRegisterForm">DOWNLOAD</button>
                            </div>
                        </div>';
			
		}
	} else {
		$html = "No Images Found";
	}	
	$html .= '</div>
                </div>
            </div>
        </div>
    </div>
</section>';
	return $html;
}

add_shortcode("job-placements", "show_job_placements");
function show_job_placements($atts) {
	$html .= '<section class="g-sec placement">
    <div class="container">

      <div class="row">
         <div class="col-lg-12">
        
      </div>
    </div>

        <div class="row">
            <div class="col-lg-12">

              <div class="row" style="justify-content: center;">';		
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
			$html .= '<div class="col-md-4 col-lg-3">
                      <div class="grids-bx placBx">
                        <div class="jobtitles prfSection">
                          <div class="prfImgs">                             
                              <img src="https://www.srishticampus.com/placedstudents/'. $placements->image.'" alt="profile">
                          </div>
                          <h3>'. $placements->name.'</h3>
                        </div>
                        <div class="placeDecp">
                          <p><i class="fa fa-suitcase"></i> '. $placements->title.'</p>
                          <p><i class="fa fa-id-card-alt"></i> '. $placements->company.'</p>                         
                        </div>
                        <div class="clear"></div>
                      </div>
                  </div>';			
		}
	} else {
		$html = "No Placements Found";
	}	
	 $html .= '<div class="pag">'.paginate_links( array(
					'base' => add_query_arg( 'cpage', '%#%' ),
					'format' => '',
					'prev_text' => __('&laquo;', 'prev'),
					'next_text' => __('&raquo;', 'next'),
					'total' => ceil($total / $items_per_page),
					'current' => $page
				)).'</div> ';
	$html .= '</div>
                       </div>
                    </div>
                </div>
            </section>';
	return $html;
}

add_shortcode("students-placements-companies-carousel", "show_students_placements_companies_carousel");
function show_students_placements_companies_carousel($atts) {
	global $wpdb;	
	$getCompanyLogos = $wpdb->get_results("SELECT * from `wp_companylogos` order by `id` desc");	
	$html .= '

<section class="common-padd clients">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h4 class="f-uppercase sub-head-red company">Companies in which our students are placed</h4>
                <div class="clients_slider">
                    <div class="owl-carousel owl-theme clients2">';
					if(!empty($getCompanyLogos))
					{
						foreach($getCompanyLogos as $logos)
						{					
							$html .= '<div class="item">
								<div class="clientlogo whitebg">
									<span>
										<img src="'.site_url('/wp-content/uploads/company_logos/'.$logos->logo.'').'" alt="logo">
									</span>
								</div>
							</div>';
						}
					}
                       
            $html .= '</div>
                </div>
            </div>
        </div>
    </div>
</section>';
	return $html;
}

add_shortcode("students-placements-companies", "show_students_placements_companies");
function show_students_placements_companies($atts) {
	global $wpdb;
	$limit = (!empty($atts['limit'])) ? "LIMIT ".$atts['limit']."" : '';
	$getCompanyLogos = $wpdb->get_results("SELECT * from `wp_companylogos` where display_home=1 order by RAND() ".$limit."");	
	$html .= '';
	if(!empty($getCompanyLogos))
	{
		foreach($getCompanyLogos as $logos)
		{					
			$html .= '<div class="col-lg-3" data-aos="zoom-in">
		  <div class="company-grid" >
			  <img alt="logo" src="'.site_url('/wp-content/uploads/company_logos/'.$logos->logo.'').'">
		  </div>
	  </div>';
		}
	}
                       
           
	return $html;
}

add_shortcode("placed-students-home", "show_placed_students_home");
function show_placed_students_home($atts) {
	global $wpdb;
	$limit = (!empty($atts['limit'])) ? "LIMIT ".$atts['limit']."" : '';
	$getStudents = $wpdb->get_results("SELECT * from `placed_students` where home_view=1 order by RAND() ".$limit."");	
	$html .= '';
	if(!empty($getStudents))
	{
		foreach($getStudents as $students)
		{					
			$html .= '<div class="col-lg-3">
                          <div class="student-grid" data-aos="flip-left">
                              <div class="student-image">
                                  <img src="https://www.srishticampus.com/placedstudents/'. $students->image.'" alt="image">                                 
                              </div>
                              <div class="student-details">
                                  <h5>'. $students->name.'</h5>
                                  <span class="company_name" >'. $students->company.'</span>
                              </div>
                          </div>
                      </div>';
		}
	}
                       
           
	return $html;
}
add_shortcode("page-footer", "show_page_footer");
function show_page_footer($atts) {
	global $wpdb;
	$getStudents = $wpdb->get_results("select title from placed_students order by id desc limit 5");
	
	if(!empty($getStudents))
	{
		$html = '<ul>';

			foreach($getStudents as $students){
				 
				$html .= '<li><a href="'.site_url('/placements').'">'.$students->title.'</a></li>';
				 
			}
		
		$html .= '</ul>';
	}
	return $html;
}

add_shortcode("all_company-logos", "show_all_company_logos");
function show_all_company_logos($atts) {
	global $wpdb;	
	$getCompanyLogos = $wpdb->get_results("SELECT * from `wp_companylogos` order by `id` desc");	
	$html .= '';
	if(!empty($getCompanyLogos))
	{
		foreach($getCompanyLogos as $logos)
		{					
			$html .= '<li>
				<a href="#">
					<img src="'.site_url('/wp-content/uploads/company_logos/'.$logos->logo.'').'" >
				</a>
			 </li>';
		}
	}      
	return $html;
}
