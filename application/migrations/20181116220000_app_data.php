<?php

class Migration_App_data extends CI_Migration {
    public function __construct()
    {   
        parent::__construct();
    }

    // アップデート処理
    public function up()
    {   
        $this->create_table_add_field( 'ios_develop' );
        $this->create_table_add_field( 'ios_staging' );
        $this->create_table_add_field( 'ios_production' );
        $this->create_table_add_field( 'android_develop' );
        $this->create_table_add_field( 'android_staging' );
        $this->create_table_add_field( 'android_production' );
        $this->create_table_add_field( 'uwp_develop' );
        $this->create_table_add_field( 'uwp_staging' );
        $this->create_table_add_field( 'uwp_production' );
    }

    private function create_table_add_field( $table )
    {
        $fields = [
            // 配布ID
            'distrib_id' => [
                'type' => "INT",
                'constraint' => 10,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            // アプリバージョン
            'app_version' => [
                'type' => "VARCHAR",
                'constraint' => 100
            ],
            // ディレクトリ識別用ハッシュ（SHA-256）
            'dir_hash' => [
                'type' => "VARCHAR",
                'constraint' => 255,
                'unique' => TRUE
            ],
            // アップロード日
            'upload_time' => [
                'type' => "DATETIME"
            ]
        ];
        $this->dbforge->add_field( $fields );
        $this->dbforge->add_key( 'distrib_id', TRUE );
        $this->dbforge->create_table( $table );
    }

    // ロールバック処理
    public function down()
    {   
        $this->dbforge->drop_table( 'ios_develop', TRUE );
        $this->dbforge->drop_table( 'ios_staging', TRUE );
        $this->dbforge->drop_table( 'ios_production', TRUE );
        $this->dbforge->drop_table( 'android_develop', TRUE );
        $this->dbforge->drop_table( 'android_staging', TRUE );
        $this->dbforge->drop_table( 'android_production', TRUE );
        $this->dbforge->drop_table( 'uwp_develop', TRUE );
        $this->dbforge->drop_table( 'uwp_staging', TRUE );
        $this->dbforge->drop_table( 'uwp_production', TRUE );
    }
}