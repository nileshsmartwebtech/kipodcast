<?php 
global $db;
global $developer;
$fields=array("slug");
$isset=check_isset($fields);

if($isset){
	$p=get_request_protect_data($fields);
	$post=get_all_data_protected($_REQUEST);
	/* $args  = array(
        'post_type' => 'podcast',
        'post_status' => 'publish',
        'cat' => 27,
        'order' => 'DESC'
    );
    $query = new WP_Query($args);*/

 /*   $args=array(
  'cat' => 27,
  'post_type' => 'podcast',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'caller_get_posts'=> 1
);
$new = new WP_Query($args);
*/
/*
$loop = new WP_Query( array( 
    'post_type' => 'podcast', 
    'cat' => 10, // Whatever the category ID is for your aerial category
    'posts_per_page' => 10,
    'orderby' => 'date', // Purely optional - just for some ordering
    'order' => 'DESC' // Ditto
) );


while ( $loop->have_posts() ) : $loop->the_post();
the_title();
echo '<div class="entry-content">';
the_content();
echo '</div>';
endwhile;*/
/*

$args = array(
						'posts_per_page'	=> -1,
						'post_type'		=> 'podcast',
						'meta_key'		=> 'channel',
						'meta_value'	=> 12,
						'orderby'			=> 'episode',
						'order'				=> 'DESC'
					);


					$query = new WP_Query( $args ); 
					$data['q']=$query;*/
$list=[];
$term = get_queried_object();
if( have_rows('categories_ad', 'option') ):
	while( have_rows('categories_ad', 'option') ) : the_row();

						$deskad = get_sub_field('desktop_ad');
						$mobad = get_sub_field('mobile_ad');
						$adurl = get_sub_field('ad_url');
	endwhile;
endif;
				
$args = array(  
							'post_type' => 'podcast',
							'post_status' => 'publish',
							'posts_per_page' => -1
						);
						$slug=$post['slug'];
						
					$query = new WP_Query( $args ); 
					?>
					<?php 
					
				  while ( $query->have_posts() ) : $query->the_post(); 
						$pid=get_the_ID();
						 if(has_category($slug, get_field('channel',$pid))): 
						 	$single=[];
						 	$single['pid']=$pid;
						 	$single['image']=get_thumbnail_url(get_the_ID(), 'medium');
						 	$single['audio']=get_field('audio_url');
						 	$single['thumb']=get_thumbnail_url(get_the_ID(), 'thumbnail');
						 	$single['mobart']=get_thumbnail_url(get_the_ID(), 'medium');
						 	$single['title']=get_the_title();
						 	$single['cat_slug']="";

						 	$podid=get_the_ID();
							$cid=get_field('channel',$podid);
								if 	(has_category('',$cid)):
									$category = get_the_category($cid);
									//$single['category']=$category;
									if($category[0]->cat_name!=='דעות'):
										$single['cat_name']=$category[0]->cat_name;
										$single['cat_slug']=$category[0]->slug;
									else: 
										$single['cat_name']=$category[1]->cat_name;
										$single['cat_slug']=$category[1]->slug;
									endif;
								else:
									$single['cat_name']='כללי';
								endif;

							$single['podcast_name']=get_field('podcast_name');
							$post_date = get_the_date( 'j בF, Y' );
							$single['post_date']=$post_date;
							$single['post_time']="";
							if(strlen(get_field('time'))>0):
								 $single['post_time']=get_field('time');
							endif;

							$creator=get_field('channel');

							if(strlen(get_field('podcast_name'))>0):
									$single['podcast_name']=get_field('podcast_name');
							elseif (strlen($creator)>0):
									$single['podcast_name']=get_the_title($creator);
							endif;

							$single['desc_short']=get_the_excerpt();
						 	$list[]=$single;
						 	//get_template_part( 'template-parts/poditem', 'big');
						endif;
					endwhile;
					wp_reset_postdata(); 

$data['list']=$list;
$data['total']=count($list);
}else{
	$status=0;
	$msg=$gbl_msg_invalid_args;
}
