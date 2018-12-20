<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class iOSAppListBase_Controller extends AppListBase_Controller {

    const PLATFORM = "ios";
    const ENVIRONMENT = "unknown";
    
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
				'ipa_link'			=> site_url( "apps/".static::PLATFORM."/".static::ENVIRONMENT."/app/download?dstid=".$row['distrib_id'] ),
				'ota_plist_link'	=> "itms-services://?action=download-manifest&url=".site_url( "apps/".static::PLATFORM."/".static::ENVIRONMENT."/plist/download?dstid=".$row['distrib_id'] ),
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
				'ipa_link'			=> site_url( "apps/".static::PLATFORM."/".static::ENVIRONMENT."/app/download?dstid=".$row['distrib_id'] ),
				'ota_plist_link'	=> "itms-services://?action=download-manifest&url=".site_url( "apps/".static::PLATFORM."/".static::ENVIRONMENT."/plist/download?dstid=".$row['distrib_id'] ),
				'upload_time' 		=> $row['upload_time']
			];
		}
		
		return $app_view_data;
    }
    
    /**
	 * ipaファイルをダウンロードします。
	 */
	public function download_app() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		// パラメータチェック
		$distrib_id = @$_GET['dstid'] ?: 0;
		if( ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
			$this->show_error( "指定した配布IDのパラメーターが無効です。", 400 );
			return;
		}

		$filepath = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/".basename( $app_data['dir_hash'] )."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_ipa_name' ).".ipa" );
		if( !file_exists( $filepath ) ) {
			$this->show_error( "ipaファイルが見つかりません。", 404, "ipaファイルが削除された可能性があります。" );
			return;
		}

		header( "Content-Description: File Transfer" );
		header( "Content-Type: application/octet-stream" );
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
	 * Over-The-Air配信経由で、iOS端末にインストールさせます。
	 * （注: iOS端末以外は何も起きません）
	 */
	public function download_plist() {
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

		$filepath = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/".basename( $app_data['dir_hash'] )."/manifest.plist";
		if( !file_exists( $filepath ) ) {
			$this->show_error( "manifest.plistが見つかりません。", 404, "manifest.plistが削除された可能性があります。" );
			return;
		}

		header( "Content-Description: File Transfer" );
		header( "Content-Type: text/xml" );
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
	 * ipaファイルをアップロードし、Over-The-Air配信用のplistファイルを出力します。
	 */
	public function upload_app() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}

        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT, true );

		if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

		if( !isset( $_FILES['app_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: ipaファイルが指定されていません。";
		}
		else if( !isset( $_POST['app_version'] ) || empty( $_POST['app_version'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: バージョンが指定されていません。";
		}
		else if( !is_uploaded_file( $_FILES['app_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: ipaファイルのアップロードに失敗しました。";
		}
		else {
			$dir_hash = AppDataList::generate_dir_hash( static::PLATFORM, static::ENVIRONMENT, $_SESSION['login_user_data']['user_id'] );
			$app_ver = $_POST['app_version'];

			if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver, $dir_hash ) ) {
				$res['error'] = true;
				$res['message'] = "エラー: ipaファイルのアップロードに失敗しました。";
			}
			else {
				$app_data = $this->appdatalist->get_app_data_by_dir_hash( static::PLATFORM, static::ENVIRONMENT, $dir_hash );
				$upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$dir_hash}";
				if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
					!move_uploaded_file( $_FILES['app_file']['tmp_name'], $upload_dest_path."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_ipa_name' ).".ipa" ) ) ) {
					$res['error'] = true;
					$res['message'] = "エラー: ipaファイルのアップロードに失敗しました。";
				}
				else {
					$this->generate_ota_plist( $app_data['distrib_id'], $app_ver, $upload_dest_path );
					$this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $_SESSION['login_user_data']['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$app_data['distrib_id']} をアップロードしました。" );
					$res['error'] = false;
					$res['message'] = "ipaファイルをアップロードしました。";
				}
			}
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
    
    private function generate_ota_plist( $new_distrib_id, $app_version, $upload_dest_path ) {
        $platform = static::PLATFORM;
		$environment = static::ENVIRONMENT;
		$ipa_url = site_url( "apps/{$platform}/{$environment}/app/download?dstid={$new_distrib_id}" );

		$ota_plist_template = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>items</key>
    <array>
        <dict>
            <key>assets</key>
            <array>
                <dict>
                    <key>kind</key>
                    <string>software-package</string>
                    <key>url</key>
                    <string>{$ipa_url}</string>
                </dict>
            </array>
            <key>metadata</key>
            <dict>
                <key>bundle-identifier</key>
                <string>{$this->config->item( $platform.'_'.$environment.'_bundle_id' )}</string>
                <key>bundle-version</key>
                <string>{$app_version}</string>
                <key>kind</key>
                <string>software</string>
                <key>title</key>
                <string>{$this->config->item( $platform.'_'.$environment.'_app_name' )}</string>
            </dict>
        </dict>
    </array>
</dict>
</plist>
XML;
		$ota_plist = new SimpleXMLElement( $ota_plist_template );
		$ota_plist->asXML( "{$upload_dest_path}/manifest.plist" );
	}

	/**
	 * アプリリストから項目を削除します。
	 */
	public function delete_app() {
		if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$this->show_error_404( "指定のターゲットは、app_configによって無効化されています。" );
			return;
		}
		
        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT, true );

        if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

        $distrib_id = $this->input->post( "id" );
		if( !isset( $distrib_id ) || ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }
		else if( $this->delete_artifact_file( "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/".basename( $app_data['dir_hash'] ) ) &&
				$this->appdatalist->delete_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) {
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

	/**
	 * 指定したアプリパッケージのフォルダーを丸ごと削除します。
	 */
	private function delete_artifact_file( $artifact_path ) {
		if( file_exists( $artifact_path ) ) {
			$dir = $artifact_path;
			$paths = array();
			while ( $glob = glob( $dir ) ) {
				$paths = array_merge( $glob, $paths );
				$dir .= '/*';
			}
			array_map( 'unlink', array_filter( $paths, 'is_file' ) );
			array_map( 'rmdir',  array_filter( $paths, 'is_dir') );
		}
		return true;
	}
}

?>