<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class RestBase_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model( 'usertokenmodel' );
    }

    /**
     * トークンを指定して、ユーザー認証します。
     * 
     * @param string    token		トークン
	 * 
	 * @return array	HTTPステータスコードと、ユーザーデータ（成功時）もしくはエラーレスポンス（失敗時）で構成した配列
	 * 
	 * 成功時:
	 * [
	 * 		'status_code'	=> 200
	 * 		'response'		=> UserModelクラスのget_user_data_by_user_id()関数の戻り値
	 * ]
	 * 
	 * 失敗時:
	 * [
	 * 		'status_code'	=> 401 or 500,
	 * 		'response'		=> エラーメッセージ
	 * ]
     */
    protected function authenticate( $token ) {
		if( !isset( $token ) ) {
			$res = [
				'status_code'	=> 401,
				'response'		=> "Error: No API token specified."
			];
        }
        else if( ( $user_id = $this->usertokenmodel->get_user_id_by_token( $token ) ) === null ) {
			$res = [
				'status_code'	=> 401,
				'response'		=> "Error: Invalid API token."
			];
		}
		else {
			$user_data = $this->usermodel->get_user_data_by_user_id( $user_id );
			if( is_null( $user_data ) ) {
				$res = [
					'status_code'	=> 500,
					'response'		=> "Error: Failed to get user data."
				];
			}
			else {
				$res = [
					'status_code'	=> 200,
					'response'		=> $user_data
				];
			}
		}

		return $res;
	}
}