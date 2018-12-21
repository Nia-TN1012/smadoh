<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = "top";
$route['404_override'] = 'error_controller/error_404';
$route['translate_uri_dashes'] = TRUE;

$route['about'] = "top/about";

$route['apps/(ios|android|uwp)/(develop|staging|production)'] = "apps/$1_$2_controller";
$route['apps/(ios|android|uwp)/(develop|staging|production)/app/(download|upload|delete)'] = "apps/$1_$2_controller/$3_app";

$route['apps/ios/(develop|staging|production)/plist/download'] = "apps/ios_$1_controller/download_plist";
$route['apps/uwp/(develop|staging|production)/certificate/download'] = "apps/uwp_$1_controller/download_cert";
$route['apps/uwp/manage-certificate'] = "apps/uwp_manage_certificate_controller";
$route['apps/uwp/manage-certificate/upload-cert'] = "apps/uwp_manage_certificate_controller/upload_cert";
$route['apps/uwp/manage-certificate/disable-cert'] = "apps/uwp_manage_certificate_controller/disable_cert";

$route['login'] = "user/login_controller";
$route['login/signin'] = "user/login_controller/signin";
$route['logout'] = "user/logout_controller";
$route['user/manage'] = "user/user_manage_controller";
$route['user/new'] = "user/create_user_controller";
$route['user/create'] = "user/create_user_controller/create";
$route['user/edit'] = "user/edit_user_controller";
$route['user/update'] = "user/edit_user_controller/update";
$route['user/remove'] = "user/user_manage_controller/remove";
$route['user/set-role'] = "user/user_manage_controller/set_role";
$route['user/token'] = "user/user_token_controller";
$route['user/token/create'] = "user/user_token_controller/create_token";
$route['user/token/delete'] = "user/user_token_controller/delete_token";

$route['user/resetpass/request'] = "user/password_reset_controller";
$route['user/resetpass/request/send'] = "user/password_reset_controller/send";
$route['user/resetpass/confirm'] = "user/password_reset_controller/confirm";
$route['user/resetpass/confirm/reset'] = "user/password_reset_controller/reset_password";

// REST API
$route['api/v1/user/info']['GET'] = "rest/rest_user_controller/info";
$route['api/v1/user/list']['GET'] = "rest/rest_user_controller/list";

$route['api/v1/apps/(ios|android|uwp)/(develop|staging|production)/list']['GET'] = "rest/rest_app_$1_$2_controller/list";
$route['api/v1/apps/(ios|android|uwp)/(develop|staging|production)/register']['POST'] = "rest/rest_app_$1_$2_controller/register";

$route['api/v1/apps/uwp/certificate/update']['POST'] = "rest/rest_app_uwp_certificate_controller/update_cert";