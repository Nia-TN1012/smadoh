<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UWPCertModel extends CI_Model {
    const UWP_CERT_AVAILABLE = 1;
    const UWP_CERT_EXPIRED = 0;

    const TYPE_KEY_DEVELOP = "develop";
    const TYPE_KEY_STAGING = "staging";
    const TYPE_KEY_PRODUCTION = "production";
    const TYPE_KEY_NAME_DEVELOP = "開発";
    const TYPE_KEY_NAME_STAGING = "ステージング";
    const TYPE_KEY_NAME_PRODUCTION = "本番";

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_cert_list() {
		return $this->db->get( 'uwp_cert' )->result_array();
    }

    public function has_valid_cert( $target_type ) {
        return $this->db->where( 'type_key', $target_type )
                        ->where( 'expire_time >=', date( "Y-m-d H:i:s" ) )
                        ->count_all_results( 'uwp_cert' ) > 0;
    }

    public static function get_type_key_name( $type_key ) {
        $type_key_name = "不明";
        switch( $type_key ) {
            case static::TYPE_KEY_DEVELOP:
                $type_key_name = static::TYPE_KEY_NAME_DEVELOP;
                break;
            case static::TYPE_KEY_STAGING:
                $type_key_name = static::TYPE_KEY_NAME_STAGING;
                break;
            case static::TYPE_KEY_PRODUCTION:
                $type_key_name = static::TYPE_KEY_NAME_PRODUCTION;
                break;
        }

        return $type_key_name;
    }

    public function update_cert( $target_type, $hash_value, $memo, $create_time, $expire_time ) {
        return $this->db->set( 'hash_value', $hash_value )
                        ->set( 'memo', $memo )
                        ->set( 'create_time', $create_time )
                        ->set( 'expire_time', $expire_time )
                        ->set( 'upload_time', date( "Y-m-d H:i:s" ) )
                        ->where( 'type_key', $target_type )
                        ->update( 'uwp_cert' );
    }

    public function disable_cert( $target_type ) {
        return $this->db->set( 'expire_time', date( "Y-m-d H:i:s" ) )
                        ->where( 'type_key', $target_type )
                        ->update( 'uwp_cert' );
    }
}

?>