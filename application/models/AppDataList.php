<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AppDataList extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->database();
    }

	public function get_app_data_list( $env, $plat, $num, $offset ) {
		$this->db->from( $env."_".$plat )
				->order_by( 'distrib_id', "DESC" )
				->limit( $num, $offset );

		return $this->db->get()->result_array();
	}

	public function get_app_list_num( $env, $plat ) {
		return $this->db->count_all_results( $env."_".$plat );;
	}

	public function get_app_data( $env, $plat, $distrib_id ) {
		$this->db->from( $env."_".$plat )
				->where( 'distrib_id', $distrib_id );

		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0] : null;
	}

	public function get_latest_ditrib_id( $env, $plat ) {
		$this->db->select_max( 'distrib_id' )
				->from( $env."_".$plat );
		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0]['distrib_id'] : 0;
	}

	public function add_app_data( $env, $plat, $app_ver ) {
		$data = [
			'app_version' => $app_ver,
			'upload_time' => date( "Y-m-d H:i:s" )
		];
		return $this->db->insert( $env."_".$plat, $data );
	}

	public function delete_app_data( $env, $plat, $distrib_id ) {
		return $this->db->where( 'distrib_id', $distrib_id )
						->delete( $env."_".$plat );
	}
}

?>