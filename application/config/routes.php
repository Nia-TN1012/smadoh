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

$route['apps/ios/develop'] = "apps/ios_develop_controller";
$route['apps/ios/staging'] = "apps/ios_staging_controller";
$route['apps/ios/production'] = "apps/ios_production_controller";
$route['apps/android/develop'] = "apps/android_develop_controller";
$route['apps/android/staging'] = "apps/android_staging_controller";
$route['apps/android/production'] = "apps/android_production_controller";
$route['apps/uwp/develop'] = "apps/uwp_develop_controller";
$route['apps/uwp/staging'] = "apps/uwp_staging_controller";
$route['apps/uwp/production'] = "apps/uwp_production_controller";

$route['download/ios/develop/ipa'] = "apps/ios_develop_controller/download_app";
$route['download/ios/staging/ipa'] = "apps/ios_staging_controller/download_app";
$route['download/ios/production/ipa'] = "apps/ios_production_controller/download_app";
$route['download/ios/develop/plist'] = "apps/ios_develop_controller/download_plist";
$route['download/ios/staging/plist'] = "apps/ios_production_controller/download_plist";
$route['download/ios/production/plist'] = "apps/ios_staging_controller/download_plist";
$route['download/android/develop/apk'] = "apps/android_develop_controller/download_app";
$route['download/android/staging/apk'] = "apps/android_staging_controller/download_app";
$route['download/android/production/apk'] = "apps/android_production_controller/download_app";
$route['download/uwp/develop/appx'] = "apps/uwp_develop_controller/download_app";
$route['download/uwp/staging/appx'] = "apps/uwp_staging_controller/download_app";
$route['download/uwp/production/appx'] = "apps/uwp_production_controller/download_app";

$route['apps/ios/develop/upload-ipa'] = "apps/ios_develop_controller/upload_app";
$route['apps/ios/staging/upload-ipa'] = "apps/ios_staging_controller/upload_app";
$route['apps/ios/production/upload-ipa'] = "apps/ios_production_controller/upload_app";
$route['apps/android/develop/upload-apk'] = "apps/android_develop_controller/upload_app";
$route['apps/android/staging/upload-apk'] = "apps/android_staging_controller/upload_app";
$route['apps/android/production/upload-apk'] = "apps/android_production_controller/upload_app";
$route['apps/uwp/develop/upload-appx'] = "apps/uwp_develop_controller/upload_app";
$route['apps/uwp/staging/upload-appx'] = "apps/uwp_staging_controller/upload_app";
$route['apps/uwp/production/upload-appx'] = "apps/uwp_production_controller/upload_app";

$route['apps/ios/develop/delete-ipa'] = "apps/ios_develop_controller/delete_app";
$route['apps/ios/staging/delete-ipa'] = "apps/ios_staging_controller/delete_app";
$route['apps/ios/production/delete-ipa'] = "apps/ios_production_controller/delete_app";
$route['apps/android/develop/delete-apk'] = "apps/android_develop_controller/delete_app";
$route['apps/android/staging/delete-apk'] = "apps/android_staging_controller/delete_app";
$route['apps/android/production/delete-apk'] = "apps/android_production_controller/delete_app";
$route['apps/uwp/develop/delete-appx'] = "apps/uwp_develop_controller/delete_app";
$route['apps/uwp/staging/delete-appx'] = "apps/uwp_staging_controller/delete_app";
$route['apps/uwp/production/delete-appx'] = "apps/uwp_production_controller/delete_app";

$route['apps/uwp/manage-certificate'] = "apps/uwp_manage_certificate_controller";
$route['apps/uwp/manage-certificate/upload-cert'] = "apps/uwp_manage_certificate_controller/upload_cert";
$route['apps/uwp/manage-certificate/disable-cert'] = "apps/uwp_manage_certificate_controller/disable_cert";
$route['download/uwp/develop/cert'] = "apps/uwp_develop_controller/download_cert";
$route['download/uwp/staging/cert'] = "apps/uwp_staging_controller/download_cert";
$route['download/uwp/production/cert'] = "apps/uwp_production_controller/download_cert";

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

// REST API
$route['api/v1/user/info']['GET'] = "rest/rest_user_controller/info";
$route['api/v1/user/list']['GET'] = "rest/rest_user_controller/list";

$route['api/v1/apps/ios/develop/list']['GET'] = "rest/rest_app_ios_develop_controller/list";
$route['api/v1/apps/ios/staging/list']['GET'] = "rest/rest_app_ios_staging_controller/list";
$route['api/v1/apps/ios/production/list']['GET'] = "rest/rest_app_ios_production_controller/list";
$route['api/v1/apps/android/develop/list']['GET'] = "rest/rest_app_android_develop_controller/list";
$route['api/v1/apps/android/staging/list']['GET'] = "rest/rest_app_android_staging_controller/list";
$route['api/v1/apps/android/production/list']['GET'] = "rest/rest_app_android_production_controller/list";
$route['api/v1/apps/uwp/develop/list']['GET'] = "rest/rest_app_uwp_develop_controller/list";
$route['api/v1/apps/uwp/staging/list']['GET'] = "rest/rest_app_uwp_staging_controller/list";
$route['api/v1/apps/uwp/production/list']['GET'] = "rest/rest_app_uwp_production_controller/list";

$route['api/v1/apps/ios/develop/register']['POST'] = "rest/rest_app_ios_develop_controller/register";
$route['api/v1/apps/ios/staging/register']['POST'] = "rest/rest_app_ios_staging_controller/register";
$route['api/v1/apps/ios/production/register']['POST'] = "rest/rest_app_ios_production_controller/register";
$route['api/v1/apps/android/develop/register']['POST'] = "rest/rest_app_android_develop_controller/register";
$route['api/v1/apps/android/staging/register']['POST'] = "rest/rest_app_android_staging_controller/register";
$route['api/v1/apps/android/production/register']['POST'] = "rest/rest_app_android_production_controller/register";
$route['api/v1/apps/uwp/develop/register']['POST'] = "rest/rest_app_uwp_develop_controller/register";
$route['api/v1/apps/uwp/staging/register']['POST'] = "rest/rest_app_uwp_staging_controller/register";
$route['api/v1/apps/uwp/production/register']['POST'] = "rest/rest_app_uwp_production_controller/register";