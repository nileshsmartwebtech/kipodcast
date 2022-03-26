<?php 
global $db;
global $developer;
$fields=array("pid");
$isset=check_isset($fields);

if($isset){
	$p=get_request_protect_data($fields);
	$post=get_all_data_protected($_REQUEST);
	$pid=$p['pid'];
	$page=get_post($pid);
	$single=[];
	if 	(has_category('',get_field('channel',$pid))):
		$category = get_the_category(get_field('channel',$pid)); 
		$perma = get_category_link($category[0]);
		$single['cat_name']=$category[0]->cat_name;
		$single['cat_slug']=$category[0]->slug;
	else:
		$single['cat_name']='כללי';
		$single['cat_slug']="";
	endif;

	$single['title']=get_the_title($pid);
	$post_date = get_the_date( 'j בF, Y',$pid );
	$single['post_date']=$post_date;
	$single['post_time']="";
	if(strlen(get_field('episode',$pid))>0):
	endif;
	if(strlen(get_field('time',$pid))>0):
		$single['post_time']=get_field('time',$pid);
	endif;

	$single['large_image']=get_thumbnail_url($pid, 'large');
	$single['audio_url']=get_field('audio_url',$pid);
	$single['thumb_url']=get_thumbnail_url($pid, 'thumbnail');
	$single['mobart_url']=get_thumbnail_url($pid, 'medium');
	$single['post_thumbnail_caption']=the_post_thumbnail_caption($pid);

	$single['creator_image']="";
	$single['creator_name']="";
	$single['creator_desc']="";

	$creator=get_field('channel',$pid);
	if (strlen($creator)>0):
		$single['creator_image']=get_thumbnail_url($creator);
		$single['creator_name']=get_the_title($creator);
		$single['creator_desc']=get_the_excerpt($creator);
	endif;
	$single['content']=$page->post_content;
	$arr_tags=[];
	$tags = get_the_tags($pid);
	
	foreach ( $tags as $tag ) {
	 	$arr_tags[]=$tag->name;
	}

	$single['tags']=$arr_tags;

	$related=[];
	$args = array(  
				'post_type' => 'podcast',
				'post_status' => 'publish',
				'posts_per_page' => -1, 
			 	//'category_name' => $category[0]->slug,
				 'post__not_in' => array($pid),
			);

	$query = new WP_Query( $args ); 

		$count=0;
	while ( $query->have_posts() ) : 		
		$query->the_post(); 
		$pid=get_the_ID();
		if(has_category($category[0]->slug, get_field('channel',$pid))): 
			$count++;
			$related[]=get_post_detail($pid);
			if($count>6){
				break;
			}
		endif;
	endwhile;
	wp_reset_postdata(); 

	$single['related']=$related;
	$data['single']=$single;
	
}else{
	$status=0;
	$msg=$gbl_msg_invalid_args;
}
