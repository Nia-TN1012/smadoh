<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Token_Controller extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model( 'usertokenmodel' );
    }
    
    public function index() {
        $this->redirect_if_not_login( "user/token" );

        $data['page_title'] = "APIトークン管理";

        $user_data = $this->usermodel->get_user_data( $_SESSION['login_user_data']['id'] );
        if( is_null( $user_data ) ) {
            $data_body['error'] = true;
            $data_body['message'] = "エラー: 不正なリクエストです。";
        }
        else {
            $data_body['error'] = false;
            $data_body['user_token_list'] = $this->get_user_token_list();
        }

        $this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'user/token', $data_body );
		$this->load->view( 'common/footer' );
    }

    private function get_user_token_list() {
        $token_list = $this->usertokenmodel->get_token_list( $_SESSION['login_user_data']['user_id'] );
        $token_view_list = [];
        $now_date = date( "Y-m-d H:i:s" );
		foreach( $token_list as $row ) {
			$token_view_list[] = [
				'token' 		    => $row['token'],
				'create_time' 		=> $row['create_time'],
				'expire_time'		=> $row['expire_time'],
				'status'  	        => $now_date <= $row['expire_time'] ? UserTokenModel::API_TOKEN_AVAILABLE : UserTokenModel::API_TOKEN_EXPIRED
			];
		}
		
		return $token_view_list;
    }

    public function create_token() {
        $this->redirect_if_not_login( "user/token" );

        if( $this->usertokenmodel->can_create_token( $_SESSION['login_user_data']['user_id'] ) ) {
            if( $this->usertokenmodel->create_token( $_SESSION['login_user_data']['user_id'] ) ) {
                $res['error'] = false;
                $res['message'] = "APIトークンを作成しました。";
            }
            else {
                $res['error'] = true;
                $res['message'] = "エラー: APIトークンの生成に失敗しました。";
            }
        }
        else {
            $res['error'] = true;
            $res['message'] = "エラー: APIトークンは1ユーザーにつき、3つまでです。\n不要なAPIトークンを削除してから、再度実行してください。";
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    public function delete_token() {
        $this->redirect_if_not_login( "user/token" );

        $token = $this->input->post( "token" );
        if( !isset( $token ) || !$this->usertokenmodel->has_token( $token ) ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

        if( $this->usertokenmodel->delete_token( $token ) ) {
                $res['error'] = false;
                $res['message'] = "APIトークンを削除しました。";
        }
        else {
            $res['error'] = true;
            $res['message'] = "エラー: APIトークンの削除に失敗しました。";
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
}

?>