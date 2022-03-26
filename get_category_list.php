<?php 
$data['category']=get_category_list();
$status=1;
$msg="";

$arr_list=[];
$cat_list=get_category_list();
$arr_cat=[];
foreach ($cat_list as $key1 => $value1) {
	$arr_cat[$value1['term_id']]=(array)$value1;
}
$menuLocations = get_nav_menu_locations();
	$menuID = $menuLocations['primary'];
	$list = wp_get_nav_menu_items(5);
	foreach($list as $single)
	{
		
		if(isset($arr_cat[$single->object_id]))
		{
			$cat_row=(array)$arr_cat[$single->object_id];
			//$single->slug=$cat_row;
			$arr_list[]=['term_id'=>$cat_row['term_id'],'name'=>$single->title,'slug'=>$cat_row['slug'],'term_group'=>0,'o'=>$single->menu_order];
		}
	}
	//$data['s']=$list;
	//$data['c']=$cat_list;
	//$data['list']=$arr_cat;
	$data['category']=$arr_list;
?>