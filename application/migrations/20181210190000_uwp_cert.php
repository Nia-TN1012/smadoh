<?php

class Migration_UWP_Cert extends CI_Migration {
    public function __construct()
    {   
        parent::__construct();
    }

    // アップデート処理
    public function up()
    {   
        $fields = [
            // 種別
            'type_key' => [
                'type' => "VARCHAR",
                'constraint' => 255
            ],
            // ハッシュ値（SHA256）
            'hash_value' => [
                'type' => "NVARCHAR",
                'constraint' => 255
            ],
            // メモ
            'memo' => [
                'type' => "NVARCHAR",
                'constraint' => 255
            ],
            // 作成された日時
            'create_time' => [
                'type' => "DATETIME"
            ],
            // 有効期限
            'expire_time' => [
                'type' => "DATETIME"
            ],
            // アップロードした日時
            'upload_time' => [
                'type' => "DATETIME"
            ],
        ];

        $this->dbforge->add_field( $fields );
        $this->dbforge->add_key( 'type_key', TRUE );
        $this->dbforge->create_table( "uwp_cert" );

        // 初期値
        $data = [
            [
                'type_key'              => "develop",
                'hash_value'            => hash( 'sha256', "develop" ),
                'memo'                  => "開発向けのサイドロード用証明書をアップロードしてください",
                'create_time'           => "1970-01-01 00:00:00",
                'expire_time'           => "1970-01-01 00:00:00",
                'upload_time'           => "1970-01-01 00:00:00"
            ],
            [
                'type_key'              => "staging",
                'hash_value'            => hash( 'sha256', "staging" ),
                'memo'                  => "ステージング向けのサイドロード用の証明書をアップロードしてください",
                'create_time'           => "1970-01-01 00:00:00",
                'expire_time'           => "1970-01-01 00:00:00",
                'upload_time'           => "1970-01-01 00:00:00"
            ],
            [
                'type_key'              => "production",
                'hash_value'            => hash( 'sha256', "production" ),
                'memo'                  => "本番向けのサイドロード用の証明書をアップロードしてください",
                'create_time'           => "1970-01-01 00:00:00",
                'expire_time'           => "1970-01-01 00:00:00",
                'upload_time'           => "1970-01-01 00:00:00"
            ]
        ];
        $this->db->insert_batch( 'uwp_cert', $data );
    }   

    // ロールバック処理
    public function down()
    {   
        $this->dbforge->drop_table( 'uwp_cert', TRUE );
    }
}