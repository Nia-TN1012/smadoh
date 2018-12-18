<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * アプリリストのモデル
 */
class AppDataList extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->database();
    }

	/**
	 * プラットフォームと環境を指定して、登録したアプリデータリストを取得します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param int		num				取得する数
	 * @param int		offset			オフセット
	 * 
	 * @return mixed	成功時: 登録したアプリデータリストの配列 / 失敗時: false
	 */
	public function get_app_data_list( $platform, $environment, $num = 10, $offset = 0 ) {
		$this->db->from( $platform."_".$environment )
				->order_by( 'distrib_id', "DESC" )
				->limit( $num, $offset );

		return $this->db->get()->result_array();
	}

	/**
	 * プラットフォームと環境を指定して、登録したアプリデータの総数を取得します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * 
	 * @return mixed	成功時: 登録したアプリデータの総数 / 失敗時: false
	 */
	public function get_app_list_num( $platform, $environment ) {
		return $this->db->count_all_results( $platform."_".$environment );
	}

	/**
	 * ディレクトリ識別用のハッシュ値を生成します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param string	additional_key	ハッシュの元にする追加のキー（任意）
	 * 
	 * @return string	ハッシュ値（SHA-256）	
	 */
	public static function generate_dir_hash( $platform, $environment, $additional_key = "" ) {
		return hash( "sha256", "{$platform}-{$environment}-".time()."-".$additional_key );
	}

	/**
	 * 配布IDを指定して、アプリデータを取得します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param int		distrib_id		配布ID
	 * 
	 * @return array	成功時: アプリデータ / 失敗時: null
	 */
	public function get_app_data( $platform, $environment, $distrib_id ) {
		$this->db->from( $platform."_".$environment )
				->where( 'distrib_id', $distrib_id );

		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0] : null;
	}

	/**
	 * ディレクトリ識別用ハッシュ値を指定して、アプリデータを取得します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param string	dir_hash		ディレクトリ識別用ハッシュ値
	 * 
	 * @return array	成功時: アプリデータ / 失敗時: null
	 */
	public function get_app_data_by_dir_hash( $platform, $environment, $dir_hash ) {
		$this->db->from( $platform."_".$environment )
				->where( 'dir_hash', $dir_hash );

		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0] : null;
	}

	/**
	 * アプリデータを登録します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param string	app_ver			アプリのバージョン
	 * @param string	dir_hash		ディレクトリ識別用ハッシュ値
	 * 
	 * @return bool		成功時: true / 失敗時: false
	 */
	public function add_app_data( $platform, $environment, $app_ver, $dir_hash ) {
		$data = [
			'app_version'	=> $app_ver,
			'dir_hash'		=> $dir_hash,
			'upload_time'	=> date( "Y-m-d H:i:s" )
		];
		return $this->db->insert( $platform."_".$environment, $data );
	}

	/**
	 * 配布IDを指定して、アプリデータを削除します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param int		distrib_id		配布ID
	 * 
	 * @return bool		成功時: true / 失敗時: false
	 */
	public function delete_app_data( $platform, $environment, $distrib_id ) {
		return $this->db->where( 'distrib_id', $distrib_id )
						->delete( $platform."_".$environment );
	}
}

?>