<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_User_Controller extends MY_Controller {

    public function __construct() {
		parent::__construct();
	}

    public function index() {
        $this->redirect_if_not_login( "user/manage" );

        if( !UserModel::is_admin() ) {
            $data_body['error'] = true;
            $data_body['message'] = "エラー: このページには、管理者権限以外はアクセスできません。";
        }
        else {
            $data_body['error'] = false;
        }

        $data['page_title'] = "ユーザー追加";

        $this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'user/create', $data_body );
		$this->load->view( 'common/footer' );
    }

    public function create() {
        $this->redirect_if_not_login( "user/manage" );

        if( !UserModel::is_admin() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }
        else {
            $this->load->library( 'form_validation' );
            $this->form_validation->set_rules( 'user_id', 'ユーザーID', 'required|regex_match[/^[\w\-]*$/]|callback_user_id_check', array( 'required' => "%s は必須項目です。", 'regex_match' => "ユーザーIDに使用できない文字が含まれています。" ) );
            $this->form_validation->set_rules( 'display_user_name', '名前', 'required', array( 'required' => "%s は必須項目です。" ) );
            $this->form_validation->set_rules( 'user_pass', 'パスワード', 'required', array( 'required' => "%s は必須項目です。" ) );
            $this->form_validation->set_rules( 'user_pass_cfm', 'パスワード確認', 'required|matches[user_pass]', array( 'required' => "%s は必須項目です。", 'matches' => "パスワードが一致しません。" ) );
            $this->form_validation->set_rules( 'role', 'ロール', 'callback_role_check' );

            if( $this->form_validation->run() ) {
                $user_data = [
                    'user_id'               => $this->input->post( "user_id" ),
                    'display_user_name'     => $this->input->post( "display_user_name" ),
                    'user_pass'             => $this->input->post( "user_pass" ),
                    'email'                 => $this->input->post( "email" ),
                    'role'                  => $this->input->post( "role" ),
                ];
                if( $this->usermodel->add_user( $user_data ) ) {
                    $res['error'] = false;
                    $res['message'] = "ユーザーを作成しました。";
                }
                else {
                    $res['error'] = true;
                    $res['message'] = "エラー: ユーザーの作成に失敗しました。";
                }
            }
            else {
                $res['error'] = true;
                $res['message'] = "エラー: ".validation_errors();
            }
        }
        
        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    // フォームバリデーション: ユーザーIDの重複チェック
    public function user_id_check( $user_id ) {
        if( !is_null( $this->usermodel->get_user_data_by_user_id( $user_id ) ) ) {
            $this->form_validation->set_message( 'username_check', "ユーザーID '{$user_id}' は、すでに使用されています。別のユーザーIDを入力してください。");
            return false;
        }
        else {
            return true;
        }
    }

    // フォームバリデーション: ロールのチェック
    public function role_check( $role ) {
        if( $role == UserModel::ROLE_ADMIN || $role == UserModel::ROLE_MANAGER || $role == UserModel::ROLE_USER ) {
            return true;
        }
        else {
            $this->form_validation->set_message( 'role_check', "不正なリクエストです。");
            return false;
        }
    }
}

?>