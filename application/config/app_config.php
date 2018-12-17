<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// App
// アプリのタイトル名
$config['home_title'] = "SMADOH";
// APIトークン
// APIトークンをストックできる数（1〜）
$config['token_slot_num'] = 3;
// APIトークンの有効期間（月単位、0以下は期限なし）
$config['token_period'] = 12;


// iOS
// iOS版のリストを使用するかどうか
$config['ios_use'] = true;
// iOS版のリスト表示名
$config['ios_root_name'] = "SMADOH Client（iOS版）";

// 開発環境のリストを使用するかどうか
$config['ios_develop_use'] = true;
// 開発環境のリスト表示名
$config['ios_develop_name'] = "Dev-SMADOH Client（iOS版）";
// 開発環境のアプリ表示名（manifet.plistでも使用）
$config['ios_develop_app_name'] = "Dev-SMADOH Client";
// 開発環境のアプリバンドル名（manifet.plistで使用）
$config['ios_develop_bundle_id'] = "net.chronoir.smadohcli.dev";
// 開発環境のアプリパッケージ名
$config['ios_develop_ipa_name'] = "smadoh-dev";

// ステージング環境のリストを使用するかどうか
$config['ios_staging_use'] = true;
// ステージング環境のリスト表示名
$config['ios_staging_name'] = "STG-SMADOH Client（iOS版）";
// ステージング環境のアプリ表示名（manifet.plistでも使用）
$config['ios_staging_app_name'] = "STG-SMADOH Client";
// ステージング環境のアプリバンドル名（manifet.plistで使用）
$config['ios_staging_bundle_id'] = "net.chronoir.smadohcli.stage";
// ステージング環境のアプリパッケージ名
$config['ios_staging_ipa_name'] = "smadoh-stg";

// 本番環境のリストを使用するかどうか
$config['ios_production_use'] = true;
// 本番環境のリスト表示名
$config['ios_production_name'] = "SMADOH Client（iOS版）";
// 本番環境のアプリ表示名（manifet.plistでも使用）
$config['ios_production_app_name'] = "SMADOH Client";
// 本番環境のアプリバンドル名（manifet.plistで使用）
$config['ios_production_bundle_id'] = "net.chronoir.smadohcli";
// 本番環境のアプリパッケージ名
$config['ios_production_ipa_name'] = "smadoh";

// Android
// Android版のリストを使用するかどうか
$config['android_use'] = true;
// Android版のリスト表示名
$config['android_root_name'] = "SMADOH Client（Android版）";

// 開発環境のリストを使用するかどうか
$config['android_develop_use'] = true;
// 開発環境のリスト表示名
$config['android_develop_name'] = "Dev-SMADOH Client（Android版）";
// 開発環境のアプリ表示名
$config['android_develop_app_name'] = "Dev-SMADOH Client";
// 開発環境のアプリパッケージ名
$config['android_develop_apk_name'] = "smadoh-dev";

// ステージング環境のリストを使用するかどうか
$config['android_staging_use'] = true;
// ステージング環境のリスト表示名
$config['android_staging_name'] = "STG-SMADOH Client（Android版）";
// ステージング環境のアプリ表示名
$config['android_staging_app_name'] = "STG-SMADOH Client";
// ステージング環境のアプリパッケージ名
$config['android_staging_apk_name'] = "smadoh-stg";

// 本番環境のリストを使用するかどうか
$config['android_production_use'] = true;
// 本番環境のリスト表示名
$config['android_production_name'] = "SMADOH Client（Android版）";
// 本番環境のアプリ表示名
$config['android_production_app_name'] = "SMADOH Client";
// 本番環境のアプリパッケージ名
$config['android_production_apk_name'] = "smadoh";

// Windows（UWP）
// Windows（UWP）版のリストを使用するかどうか
$config['uwp_use'] = true;
// Windows（UWP）版のリスト表示名
$config['uwp_root_name'] = "SMADOH Client（UWP版）";

// 開発環境のリストを使用するかどうか
$config['uwp_develop_use'] = true;
// 開発環境のリスト表示名
$config['uwp_develop_name'] = "Dev-SMADOH Client（UWP版）";
// 開発環境のアプリ表示名
$config['uwp_develop_app_name'] = "Dev-SMADOH Client";
// 開発環境のアプリパッケージ名
$config['uwp_develop_appx_name'] = "smadoh-dev";

// ステージング環境のリストを使用するかどうか
$config['uwp_staging_use'] = true;
// ステージング環境のリスト表示名
$config['uwp_staging_name'] = "STG-SMADOH Client（UWP版）";
// ステージング環境のアプリ表示名
$config['uwp_staging_app_name'] = "STG-SMADOH Client";
// ステージング環境のアプリパッケージ名
$config['uwp_staging_appx_name'] = "smadoh-stg";

// 本番環境のリストを使用するかどうか
$config['uwp_production_use'] = true;
// 本番環境のリスト表示名
$config['uwp_production_name'] = "SMADOH Client（UWP版）";
// 本番環境のアプリ表示名
$config['uwp_production_app_name'] = "SMADOH Client";
// 本番環境のアプリパッケージ名
$config['uwp_production_appx_name'] = "smadoh";

?>