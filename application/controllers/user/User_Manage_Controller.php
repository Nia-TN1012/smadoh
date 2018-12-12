<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Manage_Controller extends MY_Controller {

    public function __construct() {
		parent::__construct();
	}

    public function index() {
        $this->redirect_if_not_login( "user/manage" );

        $data['page_title'] = "ユーザーの管理";

        $this->load->library( 'pagination' );
        $config['base_url'] = "/user/manage";
        $user_num = $this->usermodel->get_user_count();
        $config['total_rows'] = $user_num;
        $config['per_page'] = 10;
        $config['page_query_string'] = true;
        $config['query_string_segment'] = "p";
        $config['use_page_numbers'] = true;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<div><nav aria-label="pagenate"><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav></div>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['attributes'] = [ 'class' => "page-link" ];

        $offset = isset( $_GET['p'] ) ? ( $_GET['p'] - 1 ) * $config['per_page'] : 0;

        $data_body['user_list'] = $this->get_user_list( $config['per_page'], $offset );
        $data_body['user_num'] = $user_num;

        $this->pagination->initialize( $config ); 

        $this->load->view( 'common/header', $data );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'user/manage', $data_body );
		$this->load->view( 'common/footer' );
    }
    
    private function get_user_list( $num, $offset ) {
        $user_list = [];
        foreach( $this->usermodel->get_user_list( $num, $offset ) as $row ) {
            $user_list[] = [
				'id' 		            => $row['id'],
                'user_id' 		        => $row['user_id'],
                'display_user_name' 	=> $row['display_user_name'],
				'email'			        => $row['email'],
                'role'	                => $row['role'],
                'role_name'	            => UserModel::get_role_name( $row['role'] ),
				'register_time' 	    => $row['register_time']
			];
        }

        return $user_list;
    }

    public function remove() {
        $this->redirect_if_not_login( "user/manage" );
        
        if( !UserModel::is_admin() ) {
            log_message( 'error', "Permission denied because login user is not admin." );
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

        $id = $this->input->post( "id" );
		if( !isset( $id ) || ( $user_data = $this->usermodel->get_user_data( $id ) ) == null ) {
            log_message( 'error', "No input user data." );
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }
        else if( $id == $_SESSION['login_user_data']['id'] ) {
            $res['error'] = true;
            $res['message'] = "エラー: 現在ログイン中のユーザーを削除することはできません。";
        }
        else if( $this->usermodel->remove_user( $id ) ) {
            $res['error'] = false;
            $res['message'] = "ユーザー: '".$user_data['display_user_name']."' を削除しました。";
        }
        else {
            $res['error'] = true;
            $res['message'] = "エラー: ユーザー: '".$user_data['display_user_name']."' の削除に失敗しました。";
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }

    public function set_role() {
        $this->redirect_if_not_login( "user/manage" );
        
        if( !UserModel::is_admin() ) {
            log_message( 'error', "Permission denied because login user is not admin." );
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

        $id = $this->input->post( "id" );
		if( !isset( $id ) || ( $user_data = $this->usermodel->get_user_data( $id ) ) == null ) {
            log_message( 'error', "No input user data." );
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }
        else if( $id == $_SESSION['login_user_data']['id'] ) {
            $res['error'] = true;
            $res['message'] = "エラー: 現在ログイン中のユーザーの権限を変更することはできません。";
        }
        else {
            $role = $this->input->post( "role" );
            if( $role != UserModel::ROLE_ADMIN && $role != UserModel::ROLE_MANAGER && $role != UserModel::ROLE_USER ) {
                $res['error'] = true;
                $res['message'] = "エラー: 不正なリクエストです。";
            }
            else if( $this->usermodel->update_user( $id, [ 'role' => $role ] ) ) {
                $res['error'] = false;
                $res['message'] = "ユーザー: ".$user_data['display_user_name']." の権限を ".UserModel::get_role_name( $role )." に変更しました。";
            }
            else {
                $res['error'] = true;
                $res['message'] = "エラー: ユーザー: ".$user_data['display_user_name']." の権限の変更に失敗しました。";
            }
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
}

?>