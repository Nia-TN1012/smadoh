<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserTokenModel extends CI_Model {

	const API_TOKEN_AVAILABLE = 1;
	const API_TOKEN_EXPIRED = 0;

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_token_list( $user_id ) {
        $this->db->select( 'token, create_time, expire_time' )
                ->from( 'user_token' )
                ->where( 'user_id', $user_id );
				
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function get_user_id_by_token( $token ) {
        $this->db->select( 'user_id' )
                ->from( 'user_token' )
                ->where( 'token', $token )
				->where( 'expire_time >=', date( "Y-m-d H:i:s" ) );

		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0]['user_id'] : null;
    }

    public function can_create_token( $user_id ) {
        return $this->db->from( 'user_token' )
                        ->where( 'user_id', $user_id )
                        ->count_all_results();
	}
    
    public function create_token( $user_id ) {
        $new_token = sha1( OAuthProvider::generateToken( 32, true ) );
        $gen_date = date( "Y-m-d H:i:s" );
        $expire_date = ( new DateTime( $gen_date ) )->add( new DateInterval( "P12M" ) )->format( "Y-m-d H:i:s" );

        $data = [
            'user_id' => $user_id,
            'token' => $new_token,
            'create_time' => $gen_date,
            'expire_time' => $expire_date
        ];
        return $this->db->insert( 'user_token', $data );
    }
    
    public function has_token( $token ) {
		return $this->db->from( 'user_token' )
                        ->where( 'token', $token )
                        ->count_all_results() === 1;
    }
	
	public function delete_token( $token ) {
		return $this->db->where( 'token', $token )
						->delete( 'user_token' );
    }
}

?>