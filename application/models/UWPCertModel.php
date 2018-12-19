<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * UWPアプリのサイドロード用証明書管理のモデル
 */
class UWPCertModel extends CI_Model {
    const UWP_CERT_AVAILABLE = 1;               // 証明書は利用可能
    const UWP_CERT_EXPIRED = 0;                 // 証明書は有効期限切れ

    // キー名
    const ENVIRONMENT_DEVELOP = "develop";         
    const ENVIRONMENT_STAGING = "staging";
    const ENVIRONMENT_PRODUCTION = "production";
    // 表示名
    const ENVIRONMENT_NAME_DEVELOP = "開発";
    const ENVIRONMENT_NAME_STAGING = "ステージング";
    const ENVIRONMENT_NAME_PRODUCTION = "本番";

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * サイドロード用証明書リストを取得します。
     */
    public function get_cert_list() {
		return $this->db->get( 'uwp_cert' )->result_array();
    }

    /**
     * 指定した環境のサイドロード用証明書データを取得します。
     */
    public function get_cert_data( $environment ) {
        return $this->db->where( 'environment', $environment )
                        ->get( 'uwp_cert' )
                        ->result_array()[0];
    }

    /**
     * 指定した環境のサイドロード用証明書が有効かどうかを判別します。
     * 
     * @param string    environment     環境
     * 
     * @return bool     true: 有効 / false: 無効
     */
    public function has_valid_cert( $environment ) {
        return $this->db->where( 'environment', $environment )
                        ->where( 'expire_time >=', date( "Y-m-d H:i:s" ) )
                        ->count_all_results( 'uwp_cert' ) > 0;
    }

    /**
     * ターゲットのキーから表示名を取得します。
     * 
     * @param string    environment     環境
     */
    public static function get_environment_name( $environment ) {
        $environment_name = "不明";
        switch( $environment ) {
            case static::ENVIRONMENT_DEVELOP:
                $environment_name = static::ENVIRONMENT_NAME_DEVELOP;
                break;
            case static::ENVIRONMENT_STAGING:
                $environment_name = static::ENVIRONMENT_NAME_STAGING;
                break;
            case static::ENVIRONMENT_PRODUCTION:
                $environment_name = static::ENVIRONMENT_NAME_PRODUCTION;
                break;
        }

        return $environment_name;
    }

    /**
     * サイドロード用証明書情報を更新します。
     * 
     * @param string    environment     環境
     * @param string    hash_value      証明書のハッシュ値
     * @param string    memo            メモ
     * @param string    create_time     証明書の作成び
     * @param string    expire_time     証明書の有効期限
     * 
     * @return bool     true: 更新成功 / false: 更新失敗
     */
    public function update_cert( $environment, $hash_value, $memo, $create_time, $expire_time ) {
        return $this->db->set( 'hash_value', $hash_value )
                        ->set( 'memo', $memo )
                        ->set( 'create_time', $create_time )
                        ->set( 'expire_time', $expire_time )
                        ->set( 'upload_time', date( "Y-m-d H:i:s" ) )
                        ->where( 'environment', $environment )
                        ->update( 'uwp_cert' );
    }

    /**
     * サイドロード用証明書情報を無効にします。
     * 
     * @param string    environment     環境
     * 
     * @return bool     true: 無効化成功 / false: 無効化失敗
     */
    public function disable_cert( $environment ) {
        return $this->db->set( 'expire_time', date( "Y-m-d H:i:s" ) )
                        ->where( 'environment', $environment )
                        ->update( 'uwp_cert' );
    }
}

?>