<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FeedModel extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
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

    public function add_feed( $category, $content ) {
        $data = [
            'category' => $category,
            'content' => $content,
			'create_time' => date( "Y-m-d H:i:s" )
		];
		return $this->db->insert( 'feed', $data );
    }

}