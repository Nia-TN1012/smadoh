<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * フィードのモデル
 */
class FeedModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * フィードリストを取得します。
     * 
     * @param int       num         取得する数
     * @param int       offset      オフセット
     * @param string    category    カテゴリ名（nullを指定した場合、全て対象）
     * 
     * @return mixed    成功時: フィードデータの配列 / 失敗時: false
     */
    public function get_feed_list( $num, $offset = 0, $category = null ) {
        $this->db->select( 'id, category, content, create_time' )
                ->from( 'feed' )
                ->order_by( 'create_time', "DESC" )
                ->limit( $num, $offset );
                
        if( !is_null( $category ) ) {
            $this->db->where( 'category', $category );
        }
		
		return $this->db->get()->result_array();
    }

    /**
     * フィードデータを追加します。
     * 
     * @param string    category    カテゴリ名
     * @param string    content     コンテンツ
     * 
     * @return bool     成功時: true / 失敗時: false
     */
    public function add_feed( $category, $content ) {
        $data = [
            'category' => $category,
            'content' => $content,
			'create_time' => date( "Y-m-d H:i:s" )
		];
		return $this->db->insert( 'feed', $data );
    }

}