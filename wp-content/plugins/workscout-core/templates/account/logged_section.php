<!-- User Profile -->
<?php 
	$current_user = wp_get_current_user();
	$roles = $current_user->roles;
	$role = array_shift( $roles ); 
	if(!empty($current_user->user_firstname)){
		$name = $current_user->user_firstname;
	} else {
		$name =  $current_user->display_name;
	}

$user_id = get_current_user_id();

?>
<div class="header-notifications user-menu">
	<div class="header-notifications-trigger">
		<a href="#">
			<div class="user-avatar status-online"><?php echo get_avatar( $current_user->user_email, 32 );?></div>
			<div class="user-avatar-title"><?php esc_attr_e( 'Hi,', 'workscout_core' ); ?> <b><?php echo $name; ?></b></div>
		</a>
	</div>

	<div class="header-notifications-dropdown">
		<ul class="user-menu-small-nav">
			<?php $dashboard_page = get_option('workscout_dashboard_page');  if( $dashboard_page ) : ?>
                <li <?php if( is_page() && $post->ID == $dashboard_page ) : ?>class="active" <?php endif; ?>><a href="<?php echo esc_url(get_permalink($dashboard_page)); ?>"> <?php esc_html_e('Dashboard','workscout_core');?></a></li>
                <?php endif; ?>

				<?php $messages_page = get_option('workscout_messages_page');  if( $messages_page ) : ?>
                <li <?php if(  is_page() && $post->ID == $messages_page ) : ?>class="active" <?php endif; ?>>
                    <a href="<?php echo esc_url(get_permalink($messages_page)); ?>"><?php esc_html_e('Messages','workscout_core');?> 
                    <?php 
                    $counter = workscout_get_unread_counter();
                    if($counter) { ?>
                    <span class="small-tag"><?php echo esc_html($counter); ?></span>
                    <?php } ?>
                    </a>
                </li>
                <?php endif; ?>

                
                
                <?php if(in_array($role,array('administrator','admin','candidate'))) : ?>
				<?php $bookmarks_page = get_option('pp_bookmarks_page');  
                if( class_exists('WP_Job_Manager_Bookmarks') && $bookmarks_page ) : ?>
                <li id="bookmarks_page-menu" <?php if( isset($post) && $post->ID == $bookmarks_page ) : ?>class="active" <?php endif; ?>><a href="<?php echo esc_url(get_permalink($bookmarks_page)); ?>"> <?php esc_html_e('Bookmarks','workscout');?></a></li>
                <?php endif; ?>

                 <?php 
                $alerts_page = get_option('job_manager_alerts_page_id');  
                if( class_exists('WP_Job_Manager_Alerts') && $alerts_page ) : ?>
                <li id="alerts_page-menu" <?php if(isset($post) && $post->ID == $alerts_page ) : ?>class="active" <?php endif; ?>>
                    <a href="<?php echo esc_url(get_permalink($alerts_page)); ?>" >
                        <?php esc_html_e('Job Alerts','workscout');?>
                    </a>
                </li>
                <?php endif; ?>
                <?php 
                    $resumes_dashboard = get_option('resume_manager_candidate_dashboard_page_id');  
                    if(class_exists('WP_Resume_Manager') &&  $resumes_dashboard ) : ?>
                    <li id="resumes_dashboard-menu" <?php if(isset($post) && $post->ID == $resumes_dashboard ) : ?>class="active" <?php endif; ?>>
                        <a href="<?php echo esc_url(get_permalink($resumes_dashboard)); ?>">
                            <?php esc_html_e('Manage Resumes','workscout');?> <span class="small-tag"><?php 
                        $count_publish =  workscout_count_posts_by_user($user_id,'resume','publish');  
                        $count_pending =  workscout_count_posts_by_user($user_id,'resume','pending');  
                        $count_pending_payment =  workscout_count_posts_by_user($user_id,'resume','pending_payment');  
                        $count_draft =  workscout_count_posts_by_user($user_id,'resume','draft');  
                        $total_pending_count = $count_publish+$count_pending+$count_draft; 
                        echo $total_pending_count; ?></span>
                        </a>
                    </li>
                    <?php endif; ?> 

                    

                
                
                <?php endif; ?>
                
                <?php if(in_array($role,array('administrator','admin','employer'))) : ?>
				
	                <?php 
	                $jobs_dashboard = get_option('job_manager_job_dashboard_page_id');  
	                if( $jobs_dashboard ) : ?>
	                <li id="jobs_dashboard-menu" <?php if(isset($post) && $post->ID == $jobs_dashboard ) : ?>class="active" <?php endif; ?>>
	                    <a href="<?php echo esc_url(get_permalink($jobs_dashboard)); ?>">
	                        <?php esc_html_e('Manage Jobs','workscout');?> <span class="small-tag"><?php 
	                        $count_publish =  workscout_count_posts_by_user($user_id,'job_listing','publish');  
	                        $count_pending =  workscout_count_posts_by_user($user_id,'job_listing','pending');  
	                        $count_pending_payment =  workscout_count_posts_by_user($user_id,'job_listing','pending_payment');  
	                        $count_draft =  workscout_count_posts_by_user($user_id,'job_listing','draft');  
	                        $total_pending_count = $count_publish + $count_pending+$count_draft; 
	                        echo $total_pending_count; ?></span>
	                    </a>
	                </li>
	                <?php endif; ?> 
	                <?php 
	                $comapny_dashboard = get_option('job_manager_company_dashboard_page_id');  
	                if( $comapny_dashboard ) : ?>
	                <li id="submit_page-menu" <?php if(isset($post) && $post->ID == $comapny_dashboard ) : ?>class="active" <?php endif; ?>>
	                    <a href="<?php echo esc_url(get_permalink($comapny_dashboard)); ?>">
	                        <?php esc_html_e('Manage Companies','workscout');?>
	                        
	                    </a>
	                </li>
	                <?php endif; ?>
	   				
                
                <?php endif; ?>
                

                <?php $profile_page = get_option('workscout_profile_page');  if( $profile_page ) : ?>
	                <li id="profile_page-menu" <?php if(isset($post) && $post->ID == $profile_page ) : ?>class="active" <?php endif; ?>><a href="<?php echo esc_url(get_permalink($profile_page)); ?>"> <?php esc_html_e('My Profile','workscout');?></a></li>
	                <?php endif; ?>
                
			<!-- <li><a href="#">My Profile</a></li> -->
			<li><a href="<?php echo wp_logout_url(get_permalink()); ?>"><i class="sl sl-icon-power"></i><?php esc_html_e('Logout','workscout_core');?></a></li>
		</ul>
	</div>
</div>