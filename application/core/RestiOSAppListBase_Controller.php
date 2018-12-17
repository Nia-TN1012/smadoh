<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestiOSAppListBase_Controller extends RestAppListBase_Controller {

    const PLATFORM = "ios";
	const ENVIRONMENT = "develop";

    public function register() {
        if( !$this->config->item( static::PLATFORM.'_use' ) || !$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_use' ) ) {
			$res['status_code'] = 404;
            $res['response'] = "Error: End-point not found (disabled by app_config).";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
			return;
        }

        $header = $this->input->request_headers();
        $token = $header['token'];

        if( !isset( $token ) ) {
            $res['status_code'] = 401;
            $res['response'] = "Error: No API token specified.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }
        else if( ( $user_id = $this->usertokenmodel->get_user_id_by_token( $token ) ) === null ) {
            $res['status_code'] = 401;
            $res['response'] = "Error: Invalid API token.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }

        $user_data = $this->usermodel->get_user_data_by_user_id( $user_id );
        if( is_null( $user_data ) || $user_data['role'] > UserModel::ROLE_MANAGER ) {
            $res['status_code'] = 403;
            $res['response'] = "Error: You must have at least the authority of the application manager ( ROLE_MANAGER ) to execute this request.";
            $this->output->set_content_type( "application/json" )
					    ->set_output( json_encode( $res ) );
            return;
        }

        if( !isset( $_FILES['ipa_file']['tmp_name'] ) ) {
			$res['status_code'] = 400;
			$res['response'] = "Error: No ipa file.";
        }
        else if( !isset( $header['app_ver'] ) || empty( $header['app_ver'] ) ) {
			$res['status_code'] = 400;
			$res['message'] = "Error: No app version.";
		}
		else if( !is_uploaded_file( $_FILES['ipa_file']['tmp_name'] ) ) {
			$res['status_code'] = 500;
			$res['response'] = "Error: Failed to upload ipa file.";
		}
		else {
			$new_distrib_id = intval( $this->appdatalist->get_latest_ditrib_id( static::PLATFORM, static::ENVIRONMENT ) ) + 1;
            $upload_dest_path = "uploads/artifacts/".static::PLATFORM."/".static::ENVIRONMENT."/{$new_distrib_id}";
			if( !is_dir( $upload_dest_path ) && !mkdir( $upload_dest_path, 0755, true ) ||
				!move_uploaded_file( $_FILES['ipa_file']['tmp_name'], $upload_dest_path."/".basename( $this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_ipa_name' ).".ipa" ) ) ) {
                    $res['status_code'] = 500;
                    $res['response'] = "Error: Failed to upload ipa file.";
			}
			else {
                $app_ver = $header['app_ver'];
				$this->generate_ota_plist( $new_distrib_id, $app_ver, $upload_dest_path );

                if( !$this->appdatalist->add_app_data( static::PLATFORM, static::ENVIRONMENT, $app_ver ) ) {
                    $res['status_code'] = 500;
                    $res['response'] = "Error: Failed to upload ipa file.";
                }
                else {
                    $app_data = $this->appdatalist->get_app_data( static::PLATFORM, static::ENVIRONMENT, $new_distrib_id );
                    $this->feedmodel->add_feed( static::PLATFORM.'_'.static::ENVIRONMENT.'_name', $user_data['display_user_name']." さんが、".$this->config->item( static::PLATFORM.'_'.static::ENVIRONMENT.'_name' )." #{$new_distrib_id} をアップロードしました。" );
                    $res['status_code'] = 200;
                    $res['response'] = $app_data;
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
                <string>{$this->config->item( 'ios_dev_bundle_id' )}</string>
                <key>bundle-version</key>
                <string>{$app_version}</string>
                <key>kind</key>
                <string>software</string>
                <key>title</key>
                <string>{$this->config->item( 'ios_dev_app_name' )}</string>
            </dict>
        </dict>
    </array>
</dict>
</plist>
XML;
		$ota_plist = new SimpleXMLElement( $ota_plist_template );
		$ota_plist->asXML( "{$upload_dest_path}/manifest.plist" );
	}
}
?>