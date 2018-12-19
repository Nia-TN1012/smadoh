<?php

class Migration_User_Token extends CI_Migration {

    public function up() {   
        $fields = [
            // ID
            'id' => [
                'type' => "INT",
                'constraint' => 10,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            // ユーザーID
            'user_id' => [
                'type' => "VARCHAR",
                'constraint' => 255,
            ],
            // トークン
            'token' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'unique' => TRUE
            ],
            // 発行日時
            'create_time' => [
                'type' => "DATETIME"
            ],
            // 有効期限
            'expire_time' => [
                'type' => "DATETIME"
            ]
        ];

        $this->dbforge->add_field( $fields );
        $this->dbforge->add_key( 'id', TRUE );
        $this->dbforge->create_table( "user_token" );
    }   

    public function down() {   
        $this->dbforge->drop_table( 'user_token', TRUE );
    }
}