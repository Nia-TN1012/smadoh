<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ユーザー管理
 */
class UserModel extends CI_Model {

	const ROLE_ADMIN 	= 7;	// システム管理者
	const ROLE_MANAGER	= 3;	// アプリ管理者
	const ROLE_USER		= 1;	// ユーザー

	function __construct() {
        parent::__construct();
        $this->load->database();
	}
	
	/**
	 * ログインしているかどうかを判別します。
	 */
	public static function is_login() {
        return isset( $_SESSION['login_user_data'] );
    }

	/**
	 * システム管理者としてログインしているかどうかを判別します。
	 */
    public static function is_admin() {
        return isset( $_SESSION['login_user_data']['role'] ) && $_SESSION['login_user_data']['role'] == static::ROLE_ADMIN;
    }

	/**
	 * アプリ管理者以上としてログインしているかどうかを判別します。
	 */
    public static function is_manager() {
        return isset( $_SESSION['login_user_data']['role'] ) && $_SESSION['login_user_data']['role'] >= static::ROLE_MANAGER;
    }

	/**
	 * ログイン認証をします。
	 * 
	 * @param string	user_id		ユーザーID
	 * @param string	user_pass	パスワード
	 * 
	 * @return int		ログイン認証に成功した時はID（0〜）、失敗した時は0
	 */
	public function get_login( $user_id, $user_pass ) {
		$this->db->select( 'id, password' )
				->from( 'user' )
        		->where( 'user_id', $user_id );
		
		$result = $this->db->get()->result_array();

		return !empty( $result ) && password_verify( $user_pass, $result[0]['password'] ) ? $result[0]['id'] : 0;
	}
	
	/**
	 * 指定したIDのユーザーデータを取得します。
	 * 
	 * @param int		id		取得するユーザーのID
	 * 
	 * @return array	ユーザーデータ（指定したIDが存在しない場合、null）
	 */
	public function get_user_data( $id ) {
		$this->db->select( 'id, user_id, display_user_name, email, role' )
				->from( 'user' )
				->where( 'id', $id );
		
		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0] : null;
	}

	/**
	 * 指定したユーザーIDのユーザーデータを取得します。
	 * 
	 * @param string	user_id		取得するユーザーのユーザーID
	 * 
	 * @return array	ユーザーデータ（指定したユーザーIDが存在しない場合、null）
	 */
	public function get_user_data_by_user_id( $user_id ) {
		$this->db->select( 'id, user_id, display_user_name, email, role' )
				->from( 'user' )
        		->where( 'user_id', $user_id );
		
		$result = $this->db->get()->result_array();

		return !empty( $result ) ? $result[0] : null;
	}

	/**
	 * ユーザーデータの一覧を取得します。
	 * 
	 * @param int		num			取得する数
	 * @param int		offset		オフセット
	 * 
	 * @return array	ユーザーデータの一覧
	 */
	public function get_user_list( $num, $offset ) {
		$this->db->select( 'id, user_id, display_user_name, email, role, register_time' )
				->from( 'user' )
				->limit( $num, $offset );
		
		$result = $this->db->get()->result_array();

		return $result;
	}

	/**
	 * ユーザーデータの登録数を取得します。
	 * 
	 * @return int	ユーザーデータの一覧
	 */
	public function get_user_count() {
		return $this->db->count_all_results( 'user' );
	}

	/**
	 * 権限を表す数値から権限名を取得します。
	 * 
	 * @param int		role	権限を表す数値
	 * 
	 * @return string	権限の名前（存在しない数値の場合、"不明"）
	 */
	public static function get_role_name( $role ) {
		$role_name = "不明";
		switch( $role ) {
			case static::ROLE_ADMIN:
				$role_name = "システム管理者";
				break;
			case static::ROLE_MANAGER:
				$role_name = "アプリ管理者";
				break;
			case static::ROLE_USER:
				$role_name = "ユーザー";
				break;
		}

		return $role_name;
	}

	/**
	 * ユーザーデータを新規追加します。
	 * 
	 * @param array		user_data	ユーザーデータ
	 * 
	 * @return bool		true: 正常に追加された / false: 追加に失敗した
	 */
	public function add_user( $user_data ) {
		$gen_date = date( "Y-m-d H:i:s" );
        $data = [
			'user_id' 				=> $user_data['user_id'],
			'display_user_name' 	=> $user_data['display_user_name'],
            'email' 				=> $user_data['email'],
            'password' 				=> password_hash( $user_data['user_pass'], PASSWORD_BCRYPT ),
            'role' 					=> $user_data['role'],
            'register_time' 		=> $gen_date,
            'update_time' 			=> $gen_date
        ];
        return $this->db->insert( 'user', $data );
	}

	/**
	 * 指定したIDのユーザーデータを削除します。
	 * 
	 * @param int		id	削除するユーザーのID
	 * 
	 * @return bool		true: 正常に削除された / false: 削除に失敗した
	 */
	public function remove_user( $id ) {
		return $this->db->where( 'id', $id )
						->delete( 'user' );
	}

	/**
	 * 指定したIDのユーザーデータを更新します。
	 * 
	 * @param int		id			更新するユーザーのID
	 * @param array		user_data	ユーザーデータ
	 * 
	 * @return bool		true: 正常に更新された / false: 更新に失敗した
	 */
	public function update_user( $id, $user_data ) {
		$data = $user_data;
		if( array_key_exists( 'password', $data ) ) {
			$data['password'] = password_hash( $data['password'], PASSWORD_BCRYPT );
		}
		$data['update_time'] = date( "Y-m-d H:i:s" );
		return $this->db->set( $data )
						->where( 'id', $id )
        				->update( 'user' );
	}
}

?>