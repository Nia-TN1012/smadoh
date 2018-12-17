<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Androidのアプリデータリストのベースコントローラー
 */
class AndroidAppListBase_Controller extends AppListBase_Controller {

    const PLATFORM = "android";
    const ENVIRONMENT = "develop";

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
				'apk_link'			=> $this->config->base_url()."download/".static::PLATFORM."/".static::ENVIRONMENT."/apk?dstid=".$row['distrib_id'],
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
				'apk_link'			=> $this->config->base_url()."download/".static::PLATFORM."/".static::ENVIRONMENT."/apk?dstid=".$row['distrib_id'],
				'upload_time' 		=> $row['upload_time']
			];
		}
		
		return $app_view_data;
    }
    
    /**
     * apkファイルをダウンロードします。
     */
    public function download_app() {
		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		$distrib_id = @$_GET['dstid'] ?: 0;
		if( ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
			show_error( "HTTP 400: 不正なリクエストです。", 400, "Error" );
			return;
		}

		$filepath = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$distrib_id}/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_apk_name' ).".apk" );
		if( !file_exists( $filepath ) ) {
			show_error( "HTTP 404: apkファイルが見つかりません", 404, "Error" );
			return;
		}

		header( "Content-Description: File Transfer" );
		header( "Content-Type: application/vnd.android.package-archive" );
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
     * apkファイルをアップロードします。
     */
    public function upload_app() {
        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

		if( !isset( $_FILES['apk_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: apkファイルが指定されていません。";
		}
		else if( !isset( $_POST['app_version'] ) || empty( $_POST['app_version'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: バージョンが指定されていません。";
		}
		else if( !is_uploaded_file( $_FILES['apk_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: apkファイルのアップロードに失敗しました。";
		}
		else {
			$new_distrib_id = intval( $this->appdatalist->get_latest_ditrib_id( static::PLATFORM, static::ENVIRONMENT ) ) + 1;
			$upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$new_distrib_id}";
			if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
				!move_uploaded_file( $_FILES['apk_file']['tmp_name'], $upload_dest_path."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_apk_name' ).".apk" ) ) ) {
				$res['error'] = true;
				$res['message'] = "エラー: apkファイルのアップロードに失敗しました。";
			}
			else {
				$app_ver = $_POST['app_version'];

				if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver ) ) {
					$res['error'] = true;
					$res['message'] = "エラー: apkファイルのアップロードに失敗しました。";
				}
				else {
					$this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $_SESSION['login_user_data']['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$new_distrib_id} をアップロードしました。" );
					$res['error'] = false;
					$res['message'] = "apkファイルのアップロードしました。";
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