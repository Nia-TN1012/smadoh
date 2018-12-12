<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['base_url'] = "/";
$config['total_rows'] = 10;
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

?>