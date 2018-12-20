<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edit_User_Controller extends MY_Controller {

    public function __construct() {
		parent::__construct();
    }
    
    public function index() {
        $this->redirect_if_not_login( "user/edit", true );

        $data['page_title'] = "ユーザー設定";

        $user_data = $this->usermodel->get_user_data( $_SESSION['login_user_data']['id'] );
        if( is_null( $user_data ) ) {
            $data_body['error'] = true;
            $data_body['message'] = "エラー: 不正なリクエストです。";
        }
        else {
            $data_body['error'] = false;
            $data_body['user_data'] = $user_data;
        }

        $this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'user/edit', $data_body );
		$this->load->view( 'common/footer' );
    }

    // ユーザー設定を更新します。
    public function update() {
        $this->redirect_if_not_login( "user/edit", true );

        // バリデーションを実行します。
        $this->load->library( 'form_validation' );
        // ユーザーID: 入力必須、使用できない文字を含んでいない、他ユーザーIDと重複しない
        $this->form_validation->set_rules( 'user_id', 'ユーザーID', 'required|regex_match[/^[\w\-]*$/]|callback_user_id_check', array( 'required' => "%s は必須項目です。", 'regex_match' => "ユーザーIDに使用できない文字が含まれています。" ) );
        // 名前: 入力必須
        $this->form_validation->set_rules( 'display_user_name', '名前', 'required', array( 'required' => "%s は必須項目です。" ) );
        // パスワード:
        //      現在のパスワードが入力されていない  -> パスワードは変更しないので、チェック不要
        //      現在のパスワードが入力されている    -> パスワードを入力するので、以下のチェックをする
        //          新しいパスワード: 入力必須、現在のパスワードと同じでない
        //          新しいパスワード（確認）: 入力必須、新しいパスワードと一致している
        $is_change_password = !empty( $this->input->post( "old_user_pass" ) );
        if( $is_change_password ) {
            $this->form_validation->set_rules( 'old_user_pass', '現在のパスワード', 'callback_pass_check['.$this->input->post( "new_user_pass" ).']' );
            $this->form_validation->set_rules( 'new_user_pass', '新しいパスワード', 'required', array( 'required' => "%s は必須項目です。" ) );
            $this->form_validation->set_rules( 'new_user_pass_cfm', 'パスワード確認', 'required|matches[new_user_pass]', array( 'required' => "%s は必須項目です。", 'matches' => "パスワードが一致しません。" ) );
        }
		
		if( $this->form_validation->run() ) {
            $user_data = [
                'user_id'               => $this->input->post( "user_id" ),
                'display_user_name'     => $this->input->post( "display_user_name" ),
                'email'                 => $this->input->post( "email" )
			];
			if( $is_change_password ) {
				$user_data['password'] = $this->input->post( "new_user_pass" );
			}

            if( $this->usermodel->update_user( $_SESSION['login_user_data']['id'], $user_data ) ) {
                $session_data['login_user_data'] = [
                    'id'					=> $_SESSION['login_user_data']['id'],
                    'user_id'		 		=> $user_data['user_id'],
                    'display_user_name'	 	=> $user_data['display_user_name'],
                    'email'					=> $user_data['email'],
                    'role' 					=> $_SESSION['login_user_data']['role']
                ];
                $this->session->set_userdata( $session_data );
                $res['error'] = false;
                $res['message'] = "ユーザーデータを更新しました。";
            }
            else {
                $res['error'] = true;
			    $res['message'] = "エラー: ユーザーデータの更新に失敗しました。";
            }
        }
        else {
            $res['error'] = true;
			$res['message'] = "エラー: ".validation_errors();
        }
        
        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    // フォームバリテーション: ユーザーIDの重複チェック
    public function user_id_check( $user_id ) {
        $user_data = $this->usermodel->get_user_data_by_user_id( $user_id );
        if( !is_null( $user_data ) && $user_data['id'] != $_SESSION['login_user_data']['id'] ) {
            $this->form_validation->set_message( 'user_id_check', "ユーザーID: {$user_id} は、他のユーザーで使用されています。別のユーザーIDを入力してください。");
            return false;
        }
        else {
            return true;
        }
    }

    // フォームバリテーション: 現在のパスワードと新しいパスワードが異なるかどうかチェック
    public function pass_check( $old_user_pass, $new_user_pass ) {
        if( $this->usermodel->get_login( $_SESSION['login_user_data']['user_id'], $old_user_pass ) <= 0 ) {
            $this->form_validation->set_message( 'pass_check', "現在のパスワードが間違っています。" );
            return false;
        }
        else if( $old_user_pass == $new_user_pass ) {
            $this->form_validation->set_message( 'pass_check', "新しいパスワードと現在のパスワードが同じです。" );
            return false;
        }
        else {
            return true;
        }
    }
}

?>