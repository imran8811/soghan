<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


//********************************\\
  //---- User routes start ----\\

$route['/'] = "soghan/index";
$route['login'] = "soghan/user_login";
$route['logout'] = "soghan/logout";
$route['register'] = "soghan/user_register";
$route['profile'] = "soghan/user_profile";
$route['change_password'] = "soghan/change_password";
$route['check'] = "soghan/check_password";
$route['save_password'] = "soghan/save_password";
$route['forgot'] = "soghan/forgot_password";
$route['forgot-password'] = "soghan/forgot_password";
$route['reset/(:any)'] = "soghan/reset_password";
$route['reset'] = "soghan/reset_password";
$route['cities'] = "soghan/get_cities_by_country";
$route['cities_search'] = "soghan/search_cities_by_country";
$route['maidan'] = "soghan/get_maidan_by_city";
$route['subcats'] = "soghan/get_subcat_by_cat";
$route['types'] = "soghan/get_types_by_subcat";
$route['genders'] = "soghan/get_genders_by_subcat";
$route['type_genders'] = "soghan/get_genders_by_type";
$route['search'] = "soghan/search_post";
$route['search/(:num)'] = "soghan/search_post/$1";
$route['quick_search'] = "soghan/search_post";
$route['quick_search/(:num)'] = "soghan/search_post/$1";
$route['vendors/(:num)'] = "soghan/vendors_list/$1";
$route['vendors/(:num)/(:num)'] = "soghan/vendors_list/$1/$1";
$route['detail/(:num)'] = "soghan/vendor_detail/$1";
$route['market_place'] = "soghan/market_places";
$route['market_place/(:num)'] = "soghan/market_places/$1";
$route['market_place_detail/(:num)'] = "soghan/ad_detail/$1";
$route['related_links'] = "soghan/get_links";
$route['link_details/(:num)'] = "soghan/get_link_detail/$1";
$route['calendar'] = "soghan/calendar";
$route['calendar_events'] = "soghan/get_events";
$route['event_details/(:num)'] = "soghan/event_detail/$1";
$route['raceinfo'] = "soghan/youtube_videos";
$route['watch/(:any)'] = "soghan/watch_video";
$route['load'] = "soghan/load_videos";
$route['search_video'] = "soghan/search_videos";
$route['news'] = "soghan/news";

//---- User routes end ----\\


//********************************\\
  //---- Admin routes start ----\\

$route['admin_login'] = "admin/user_login";
$route['dashboard'] = "admin/dashboard";
$route['mdcities'] = "admin/get_cities_by_country";
$route['pro_notification'] = "admin/product_notification";
$route['push_form'] = "admin/push_form";

//===============Events====================
$route['add_event'] = "admin/event_form";
$route['save_event'] = "admin/save_event";  
$route['edit_event/(:num)'] = "admin/event_form/$1";
$route['update_event'] = "admin/event_form";
$route['view_events'] = "admin/get_events";
$route['view_events/(:num)'] = "admin/get_events/$1";
$route['del_event/(:num)'] = "admin/delete_event/$1";

//================Vendors==================
$route['add_vendor'] = "admin/vendor_form";
$route['edit_vendor/(:num)'] = "admin/vendor_form/$1";
$route['save_vendor'] = "admin/save_vendor";  
$route['view_vendors'] = "admin/get_vendors";
$route['view_vendors/(:num)'] = "admin/get_vendors/$1";
$route['del_vendor/(:num)'] = "admin/delete_vendor/$1";


//================Vendor Details============
$route['add_vendor_details'] = "admin/vendor_details_form";
$route['edit_vendor_details/(:num)'] = "admin/vendor_details_form/$1";
$route['save_vendor_details'] = "admin/save_vendor_details";  
$route['view_vendor_details'] = "admin/get_vendor_details";
$route['view_vendor_details/(:num)'] = "admin/get_vendor_details/$1";
$route['del_vendor_details/(:num)'] = "admin/delete_vendor_details/$1";


//================Links=====================
$route['add_links'] = "admin/links_form";
$route['edit_links/(:num)'] = "admin/links_form/$1";
$route['save_links'] = "admin/save_links";  
$route['view_links'] = "admin/get_links";
$route['view_links/(:num)'] = "admin/get_links/$1";
$route['del_links/(:num)'] = "admin/delete_links/$1";


//================Maidans=====================
$route['add_maidans'] = "admin/maidans_form";
$route['edit_maidans/(:num)'] = "admin/maidans_form/$1";
$route['save_maidans'] = "admin/save_maidans";  
$route['view_maidans'] = "admin/get_maidans";
$route['view_maidans/(:num)'] = "admin/get_maidans/$1";
$route['del_maidans/(:num)'] = "admin/delete_maidans/$1";


//================Adverts=====================
$route['add_advert'] = "admin/ad_form";
$route['edit_advert/(:num)'] = "admin/edit_ad/$1";
$route['save_advert'] = "admin/save_ad";  
$route['view_adverts'] = "admin/view_ads";
$route['view_adverts/(:num)'] = "admin/view_ads/$1";
$route['del_advert/(:num)'] = "admin/del_ad/$1";

//---- Admin routes end ----\\



//********************************\\
  //---- Mobile routes start ----\\

$route['signup'] = "api/save_user";
$route['signin'] = "api/user_login";
$route['verification'] = "api/user_verify";
$route['resend'] = "api/resend_email";
$route['add_market_place'] = "api/save_post";
$route['market_places'] = "api/get_posts";
$route['del_market_place'] = "api/delete_post";
$route['views/(:num)'] = "api/update_views/$1";
$route['links'] = "api/get_links";
$route['vendors'] = "api/get_vendors";
$route['vendors_android'] = "api/get_vendors_android";
$route['vendor_detail/(:num)'] = "api/get_vendor_detail/$1";
$route['events'] = "api/get_events";
$route['youtube'] = "api/youtube_mobile";
$route['add_token'] = "api/save_token";
$route['ads'] = "api/mobile_ads";
$route['ad_clicks'] = "api/ad_clicks";
$route['user_vendor'] = "api/user_vendor";

//---- Mobile routes end----\\

$route['default_controller'] = "soghan";
$route['404_override'] = '';