<?php

class Migration_User extends CI_Migration {
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
                'auto_increment' => TRUE,
                'unique' => TRUE
            ],
            // ユーザーID
            'user_id' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'unique' => TRUE
            ],
            // ユーザー名（表示名）
            'display_user_name' => [
                'type' => "NVARCHAR",
                'constraint' => 255
            ],
            // Eメール
            'email' => [
                'type' => "VARCHAR",
                'constraint' => 255
            ],
            // パスワード（ NOTE: パスワードハッシュを通して保存すること ）
            'password' => [
                'type' => "VARCHAR",
                'constraint' => 255
            ],
            // 権限（ 1: システム管理者, 2: アプリ管理者, 3: ユーザー ）
            'role' => [
                'type' => "INT",
                'constraint' => 1,
                'unsigned' => TRUE,
                'default' => 1
            ],
            'register_time' => [
                'type' => "DATETIME"
            ],
            'update_time' => [
                'type' => "DATETIME"
            ]
        ];
        $this->dbforge->add_field( $fields );
        $this->dbforge->add_key( 'id', TRUE );
        $this->dbforge->create_table( "user" );

        // Adminユーザー
        $gen_date = date( "Y-m-d H:i:s" );
        $data = [
            'user_id'               => "admin",
            'display_user_name'     => "Admin",
            'email'                 => "",
            'password'              => password_hash( "0000", PASSWORD_BCRYPT ),
            'role'                  => 1,
            'register_time'         => $gen_date,
            'update_time'           => $gen_date
        ];
        $this->db->insert( 'user', $data );
    }

    // ロールバック処理
    public function down()
    {   
        $this->dbforge->drop_table( 'user', TRUE );
    }
}