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
	 * 最新の配布IDを取得します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * 
	 * @return int		最新の配布ID（1個も登録されていない場合、0）
	 */
	public function get_latest_ditrib_id( $platform, $environment ) {
		$this->db->select_max( 'distrib_id' )
				->from( $platform."_".$environment );
		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0]['distrib_id'] : 0;
	}

	/**
	 * アプリデータを登録します。
	 * 
	 * @param string	platform		プラットフォーム
	 * @param string	environment		環境
	 * @param string	app_ver			アプリのバージョン
	 * 
	 * @return bool		成功時: true / 失敗時: false
	 */
	public function add_app_data( $platform, $environment, $app_ver ) {
		$data = [
			'app_version' => $app_ver,
			'upload_time' => date( "Y-m-d H:i:s" )
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