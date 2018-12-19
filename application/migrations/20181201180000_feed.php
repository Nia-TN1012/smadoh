<?php

class Migration_Feed extends CI_Migration {

    public function up() {   
        $fields = [
            // ID
            'id' => [
                'type' => "INT",
                'constraint' => 10,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            // カテゴリ
            'category' => [
                'type' => "VARCHAR",
                'constraint' => 255,
            ],
            // 情報
            'content' => [
                'type' => "TEXT"
			],
			// 作成日時
            'create_time' => [
                'type' => "DATETIME"
            ]
        ];

        $this->dbforge->add_field( $fields );
        $this->dbforge->add_key( 'id', TRUE );
        $this->dbforge->create_table( "feed" );
    }   

    public function down() {   
        $this->dbforge->drop_table( 'feed', TRUE );
    }
}