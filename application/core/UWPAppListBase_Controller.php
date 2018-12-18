<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UWPAppListBase_Controller extends AppListBase_Controller {

    const PLATFORM = "uwp";
    const ENVIRONMENT = "unknown";

    public function __construct() {
		parent::__construct();
		$this->load->model( 'uwpcertmodel' );
    }

	/**
	 * インデックスページ
	 * 
	 * @note	UWPのみ証明書ファイルの判定があるので、オーバーライドします。
	 */
    public function index() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		$data_head['page_title'] = $this->config->item( static::PLATFORM."_".static::ENVIRONMENT."_name" );
        $data_body['page_title'] = $data_head['page_title'];
        $data_body['platform'] = static::PLATFORM;
        $data_body['environment'] = static::ENVIRONMENT;

		$this->load->library( 'pagination' );
        $config['base_url'] = "/apps/".static::PLATFORM."/".static::ENVIRONMENT;
        $item_num = $this->appdatalist->get_app_list_num( static::PLATFORM, static::ENVIRONMENT );
		$config['total_rows'] = $item_num;
		$config['per_page'] = 10;
        $offset = isset( $_GET['p'] ) && $_GET['p'] > 0 ? ( $_GET['p'] - 1 ) * $config['per_page'] : 0;

        $data_body['app_view_list'] = $this->get_app_list( $config['per_page'], $offset );
		$data_body['item_num'] = $item_num;

		$data_body['latest_app_data'] = $this->get_latest_app_data();
		$data_body['has_valid_cert'] = $this->uwpcertmodel->has_valid_cert( static::ENVIRONMENT );

		$this->pagination->initialize( $config ); 
		
		$this->load->view( 'common/header', $data_head );
		$this->load->view( 'common/navigation' );
		$this->load->view( 'apps/'.static::PLATFORM.'_applist', $data_body );
		$this->load->view( 'common/footer' );
	}

	/**
	 * アプリデータリストを取得し、View向けに構築します。
	 */
    protected function get_app_list( $num, $offset )
	{
		$app_list = $this->appdatalist->get_app_data_list( static::PLATFORM, static::ENVIRONMENT, $num, $offset );
		$app_view_list = [];
		foreach( $app_list as $row ) {
			$app_view_list[] = [
				'distrib_id' 		=> $row['distrib_id'],
				'app_version' 		=> $row['app_version'],
				'appx_link'			=> site_url( "download/".static::PLATFORM."/".static::ENVIRONMENT."/appx?dstid=".$row['distrib_id'] ),
				'appx_direct_link'	=> "ms-appinstaller:?source=".site_url( "download/".static::PLATFORM."/".static::ENVIRONMENT."/appx?dstid=".$row['distrib_id'] ),
				'upload_time' 		=> $row['upload_time']
			];
		}
		
		return $app_view_list;
	}

	/**
	 * 最新のアプリデータを取得し、View向けに構築します。
	 */
	protected function get_latest_app_data()
	{
		$app_list = $this->appdatalist->get_app_data_list( static::PLATFORM, static::ENVIRONMENT, 1, 0 );
		if( empty( $app_list ) ) {
			return null;
		}
		else {
			$row = $app_list[0];
			$app_view_data = [
				'distrib_id' 		=> $row['distrib_id'],
				'app_version' 		=> $row['app_version'],
				'appx_link'			=> site_url( "download/".static::PLATFORM."/".static::ENVIRONMENT."/appx?dstid=".$row['distrib_id'] ),
				'appx_direct_link'	=> "ms-appinstaller:?source=".site_url( "download/".static::PLATFORM."/".static::ENVIRONMENT."/appx?dstid=".$row['distrib_id'] ),
				'upload_time' 		=> $row['upload_time']
			];
		}
		
		return $app_view_data;
    }

    /**
     * appxbundleファイルをダウンロードします。
     */
    public function download_app() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		$distrib_id = @$_GET['dstid'] ?: 0;
		if( ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
			$this->show_error( "指定した配布IDのパラメーターが無効です。", 400 );
			return;
		}

		$filepath = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$distrib_id}/".basename( $this->config->item( 'uwp_dev_appx_name' ).".appxbundle" );
		if( !file_exists( $filepath ) ) {
			$this->show_error( "appxbundleファイルが見つかりません。", 404, "appxbundleファイルが削除された可能性があります。" );
			return;
		}

		header( "Content-Description: File Transfer" );
		header( "Content-Type: application/appxbundle" );
		header( "Content-Disposition: attachment; filename=\"".basename( $filepath )."\"");
		header( "Expires: 0" );
		header( "Cache-Control: must-revalidate" );
		header( "Pragma: public" );
		header( "Content-Length: ".filesize( $filepath ) );
		ob_clean();
    	flush();
		readfile( $filepath );
    }
	
	/**
     * サイドロード用証明書をダウンロードします。
     */
    public function download_cert() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404();
			return;
		}

		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		$filepath = "uploads/certificate/".static::PLATFORM."/".static::ENVIRONMENT."/uwp_develop_sideload.cer";
		if( !file_exists( $filepath ) ) {
			$this->show_error( "証明書ファイルが見つかりません。", 404, "証明書ファイルが削除された可能性があります。" );
			return;
		}

		header( "Content-Description: File Transfer" );
		header( "Content-Type: application/pkix-cert" );
		header( "Content-Disposition: attachment; filename=\"".basename( $filepath )."\"");
		header( "Expires: 0" );
		header( "Cache-Control: must-revalidate" );
		header( "Pragma: public" );
		header( "Content-Length: ".filesize( $filepath ) );
		ob_clean();
    	flush();
		readfile( $filepath );
    }
	
	/**
     * appxbundleファイルをアップロードします。
     */
    public function upload_app() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );
        
        if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
		}

		if( !isset( $_FILES['appx_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: appxbundleファイルが指定されていません。";
		}
		else if( !isset( $_POST['app_version'] ) || empty( $_POST['app_version'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: バージョンが指定されていません。";
		}
		else if( !is_uploaded_file( $_FILES['appx_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: appxbundleファイルのアップロードに失敗しました。";
		}
		else {
			$new_distrib_id = intval( $this->appdatalist->get_latest_ditrib_id( static::PLATFORM, static::ENVIRONMENT ) ) + 1;
			$upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$new_distrib_id}";
			if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
				!move_uploaded_file( $_FILES['appx_file']['tmp_name'], $upload_dest_path."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_appx_name' ).".appxbundle" ) ) ) {
				$res['error'] = true;
				$res['message'] = "エラー: apkファイルのアップロードに失敗しました。";
			}
			else {
				$app_ver = $_POST['app_version'];

				if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver ) ) {
					$res['error'] = true;
					$res['message'] = "エラー: appxbundleファイルのアップロードに失敗しました。";
				}
				else {
					$this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $_SESSION['login_user_data']['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$new_distrib_id} をアップロードしました。" );
					$res['error'] = false;
					$res['message'] = "appxbundleファイルのアップロードしました。";
				}
			}
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
	}

	/**
	 * 指定したビルドを一覧から削除します。
	 */
	public function delete_app() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}
		
        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );
        
        if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

        $distrib_id = $this->input->post( "id" );
		if( !isset( $distrib_id ) || ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }
        else if( $this->appdatalist->delete_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) {
			$this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $_SESSION['login_user_data']['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$distrib_id} を削除しました。" );
            $res['error'] = false;
            $res['message'] = "配布ID: #{$distrib_id} を削除しました。";
        }
        else {
            $res['error'] = true;
            $res['message'] = "エラー: 配布ID: #{$distrib_id} の削除に失敗しました。";
        }

        $this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
	}
}