<?php

class Migration_User_Token extends CI_Migration {
    public function __construct()
    {   
        parent::__construct();
    }

    // アップデート処理
    public function up()
    {   
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
            'create_time' => [
                'type' => "DATETIME"
            ],
            'expire_time' => [
                'type' => "DATETIME"
            ]
        ];

        $this->dbforge->add_field( $fields );
        $this->dbforge->add_key( 'id', TRUE );
        $this->dbforge->create_table( "user_token" );
    }   

    // ロールバック処理
    public function down()
    {   
        $this->dbforge->drop_table( 'user_token', TRUE );
    }
}