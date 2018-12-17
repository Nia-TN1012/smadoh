<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * APIトークンのモデル
 */
class UserTokenModel extends CI_Model {

	const API_TOKEN_AVAILABLE = 1;      // APIトークンは利用可能
	const API_TOKEN_EXPIRED = 0;        // APIトークンは有効期限切れ

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * ユーザーIDを指定して、APIトークンリストを取得します。
     * 
     * @param int       user_id     ユーザーID
     * 
     * @return array    APIトークンリストの配列
     */
    public function get_token_list( $user_id ) {
        $this->db->select( 'token, create_time, expire_time' )
                ->from( 'user_token' )
                ->where( 'user_id', $user_id )
                ->limit( 3 );

        return $this->db->get()->result_array();
    }

    /**
     * APIトークンを指定して、ユーザーIDを取得します。
     * 
     * @param string	token		APIトークン
	 * 
	 * @param string	有効なトークンの時: 対応するユーザーiD / 無効なトークンの時: null
     */
    public function get_user_id_by_token( $token ) {
        $this->db->select( 'user_id' )
                ->from( 'user_token' )
                ->where( 'token', $token )
				->where( 'expire_time >=', date( "Y-m-d H:i:s" ) );

		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0]['user_id'] : null;
    }

	/**
	 * APIトークンが発行できるかどうかを判別します。
	 * 
	 * @param string	user_id		ユーザーID
	 * 
	 * @return bool		true: 発行可能 / false: 発行不可（上限オーバー）
	 */
    public function can_create_token( $user_id ) {
        return $this->db->from( 'user_token' )
                        ->where( 'user_id', $user_id )
                        ->count_all_results() < max( $this->config->item( 'token_slot_num' ), 1 );
	}
	
	/**
	 * トAPIトークンを発行します。
	 * 
	 * @param string	user_id		ユーザーID
	 * 
	 * @return bool		true: 正常に発行した / false: 発行に失敗した
	 */
    public function create_token( $user_id ) {
        $new_token = bin2hex( OAuthProvider::generateToken( 32, true ) );
		$gen_date = date( "Y-m-d H:i:s" );
		$token_period = $this->config->item( 'token_period' );
		if( $token_period > 0 ) {
			$expire_date = ( new DateTime( $gen_date ) )->add( new DateInterval( "P{$token_period}M" ) )->format( "Y-m-d H:i:s" );
		}
		else {
			$expire_date = "9999-12-31 23:59:59";
		}

        $data = [
            'user_id' => $user_id,
            'token' => $new_token,
            'create_time' => $gen_date,
            'expire_time' => $expire_date
        ];
        return $this->db->insert( 'user_token', $data );
    }
	
	/**
	 * 指定したAPIトークンが存在するかどうかを判別します。
	 * 
	 * @param string	token	APIトークン
	 * 
	 * @return bool		true: 存在する / false: 存在しない
	 */
    public function has_token( $token ) {
		return $this->db->from( 'user_token' )
                        ->where( 'token', $token )
                        ->count_all_results() === 1;
    }
	
	/**
	 * 指定したAPIトークンを削除します。
	 * 
	 * @param string	token	APIトークン
	 * 
	 * @return bool		true: 削除完了 / false: 削除失敗
	 */
	public function delete_token( $token ) {
		return $this->db->where( 'token', $token )
						->delete( 'user_token' );
    }
}

?>