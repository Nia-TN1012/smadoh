<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class iOSAppListBase_Controller extends AppListBase_Controller {

    const PLATFORM = "ios";
    const ENVIRONMENT = "develop";
    
    /**
	 * アプリリストを取得し、ビュー向けに加工します。
	 */
	protected function get_app_list( $num, $offset )
	{
		$app_list = $this->appdatalist->get_app_data_list( static::PLATFORM, static::ENVIRONMENT, $num, $offset );
		$app_view_list = [];
		foreach( $app_list as $row ) {
			$app_view_list[] = [
				'distrib_id' 		=> $row['distrib_id'],
				'app_version' 		=> $row['app_version'],
				'ipa_link'			=> $this->config->base_url()."download/".static::PLATFORM."/".static::ENVIRONMENT."/ipa?dstid=".$row['distrib_id'],
				'ota_plist_link'	=> "itms-services://?action=download-manifest&url=".$this->config->base_url()."download/".static::PLATFORM."/".static::ENVIRONMENT."/plist?dstid=".$row['distrib_id'],
				'upload_time' 		=> $row['upload_time']
			];
		}
		
		return $app_view_list;
	}

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
				'ipa_link'			=> $this->config->base_url()."download/".static::PLATFORM."/".static::ENVIRONMENT."/ipa?dstid=".$row['distrib_id'],
				'ota_plist_link'	=> "itms-services://?action=download-manifest&url=".$this->config->base_url()."download/".static::PLATFORM."/".static::ENVIRONMENT."/plist?dstid=".$row['distrib_id'],
				'upload_time' 		=> $row['upload_time']
			];
		}
		
		return $app_view_data;
    }
    
    /**
	 * ipaファイルをダウンロードします。
	 */
	public function download_app() {
		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		// パラメータチェック
		$distrib_id = @$_GET['dstid'] ?: 0;
		if( ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
			show_error( "HTTP 400: 不正なリクエストです。", 400, "Error" );
			return;
		}

		$filepath = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$distrib_id}/".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_ipa_name' ).".ipa";
		if( !file_exists( $filepath ) ) {
			show_error( "HTTP 404: ipaファイルが見つかりません", 404, "Error" );
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
		$this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		$distrib_id = @$_GET['dstid'] ?: 0;
		if( ( $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $distrib_id ) ) == null ) {
			show_error( "HTTP 400: 不正なリクエストです。", 400, "Error" );
			return;
		}

		$filepath = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$distrib_id}/manifest.plist";
		if( !file_exists( $filepath ) ) {
			show_error( "HTTP 404: ipaファイルが見つかりません", 404, "Error" );
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
        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

		if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

		if( !isset( $_FILES['ipa_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: ipaファイルが指定されていません。";
		}
		else if( !isset( $_POST['app_version'] ) || empty( $_POST['app_version'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: バージョンが指定されていません。";
		}
		else if( !is_uploaded_file( $_FILES['ipa_file']['tmp_name'] ) ) {
			$res['error'] = true;
			$res['message'] = "エラー: ipaファイルのアップロードに失敗しました。";
		}
		else {
			$new_distrib_id = intval( $this->appdatalist->get_latest_ditrib_id( static::PLATFORM, static::ENVIRONMENT ) ) + 1;
			$upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$new_distrib_id}";
			if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
				!move_uploaded_file( $_FILES['ipa_file']['tmp_name'], $upload_dest_path."/".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_ipa_name' ).".ipa" ) ) {
				$res['error'] = true;
				$res['message'] = "エラー: ipaファイルのアップロードに失敗しました。";
			}
			else {
				$app_ver = $_POST['app_version'];
				$this->generate_ota_plist( $new_distrib_id, $app_ver, $upload_dest_path );

				if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver ) ) {
					$res['error'] = true;
					$res['message'] = "エラー: ipaファイルのアップロードに失敗しました。";
				}
				else {
					$this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $_SESSION['login_user_data']['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$new_distrib_id} をアップロードしました。" );
					$res['error'] = false;
					$res['message'] = "ipaファイルをアップロードしました。";
				}
			}
		}

		$this->output->set_content_type( "application/json" )
					->set_output( json_encode( $res ) );
    }
    
    private function generate_ota_plist( $new_distrib_id, $app_version, $upload_dest_path ) {
        $base_url = base_url();
        $platform = static::PLATFORM;
        $environment = static::ENVIRONMENT;

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
                    <string>{$base_url}apps/{$platform}/{$environment}/download-ipa?dstid={$new_distrib_id}</string>
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
        $this->redirect_if_not_login( "apps/".static::PLATFORM."/".static::ENVIRONMENT );

        if( !UserModel::is_manager() ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }

        $distrib_id = $this->input->post( "id" );
		if( !isset( $distrib_id ) || ( $app_data = $this->appdatalist->get_app_data( self::PLATFORM, self::ENVIRONMENT, $distrib_id ) ) == null ) {
            $res['error'] = true;
            $res['message'] = "エラー: 不正なリクエストです。";
        }
        else if( $this->appdatalist->delete_app_data( self::PLATFORM, self::ENVIRONMENT, $distrib_id ) ) {
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

?>