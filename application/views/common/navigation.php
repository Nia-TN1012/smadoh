        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <a class="navbar-brand" href="/"><i class="fab fa-connectdevelop"></i> <?= $this->config->item( 'home_title' ) ?></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar"
                    aria-extends="false" aria-label="ナビゲーション切り替え">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <?php if( UserModel::is_login() ): ?>
                    <?php if( $this->config->item( 'ios_use' ) ): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fab fa-apple"></i> <?= $this->config->item( 'ios_root_name' ) ?></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown">
                            <?php if( $this->config->item( 'ios_develop_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/ios/develop"><i class="fab fa-app-store-ios"></i> <?= $this->config->item( 'ios_develop_name' ) ?></a>
                            <?php endif ?>
                            <?php if( $this->config->item( 'ios_staging_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/ios/staging"><i class="fab fa-app-store-ios"></i> <?= $this->config->item( 'ios_staging_name' ) ?></a>
                            <?php endif ?>
                            <?php if( $this->config->item( 'ios_production_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/ios/production"><i class="fab fa-app-store-ios"></i> <?= $this->config->item( 'ios_production_name' ) ?></a>
                            <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php if( $this->config->item( 'android_use' ) ): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fab fa-android"></i> <?= $this->config->item( 'android_root_name' ) ?></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown">
                            <?php if( $this->config->item( 'android_develop_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/android/develop"><i class="fab fa-google-play"></i> <?= $this->config->item( 'android_develop_name' ) ?></a>
                            <?php endif ?>
                            <?php if( $this->config->item( 'android_staging_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/android/staging"><i class="fab fa-google-play"></i> <?= $this->config->item( 'android_staging_name' ) ?></a>
                            <?php endif ?>
                            <?php if( $this->config->item( 'android_production_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/android/production"><i class="fab fa-google-play"></i> <?= $this->config->item( 'android_production_name' ) ?></a>
                            <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php if( $this->config->item( 'uwp_use' ) ): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fab fa-windows"></i> <?= $this->config->item( 'uwp_root_name' ) ?></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown">
                            <?php if( $this->config->item( 'uwp_develop_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/uwp/develop"><i class="fab fa-windows"></i> <?= $this->config->item( 'uwp_develop_name' ) ?></a>
                            <?php endif ?>
                            <?php if( $this->config->item( 'uwp_staging_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/uwp/staging"><i class="fab fa-windows"></i> <?= $this->config->item( 'uwp_staging_name' ) ?></a>
                            <?php endif ?>
                            <?php if( $this->config->item( 'uwp_production_use' ) ): ?>
                            <a class="dropdown-item" href="/apps/uwp/production"><i class="fab fa-windows"></i> <?= $this->config->item( 'uwp_production_name' ) ?></a>
                            <?php endif ?>
                            <?php if( UserModel::is_manager() ): ?>
                            <a class="dropdown-item" href="/apps/uwp/manage-certificate"><i class="far fa-address-card"></i> サイドロードアプリ用証明書の管理</a>
                            <?php endif ?>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php endif ?>
                </ul>
                <?php if( strpos( $_SERVER['REQUEST_URI'], "/login" ) !== 0 ): ?>
                <ul class="navbar-nav ml-auto">
                    <?php if( UserModel::is_login() ): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                            <img src="https://www.gravatar.com/avatar/<?= md5( strtolower( trim( !empty( $_SESSION['login_user_data']['email'] ) ? $_SESSION['login_user_data']['email'] : $_SESSION['login_user_data']['user_id']."-".$this->config->item( 'home_title' ) ) ) ) ?>?d=identicon&s=24" />
                            &nbsp;<?= h( $_SESSION['login_user_data']['display_user_name'] ) ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
                            <a class="dropdown-item" href="/user/edit"><i class="fas fa-user-cog"></i> ユーザー設定</a>
                            <a class="dropdown-item" href="/user/token"><i class="fas fa-code"></i> APIトークンの管理</a>
                            <a class="dropdown-item" href="/user/manage"><i class="fas fa-users"></i> ユーザー一覧・管理</a>
                            <a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> ログアウト</a>
                        </div>
                    </li>
                    <?php endif ?>

                    <li class="nav-item">
                        <?php if( !UserModel::is_login() ): ?>
                        <button tyep="button" class="btn btn-info" onClick="window.location.href='/login';">ログイン <i class="fas fa-sign-in-alt"></i></button>
                        <?php endif ?>
                    </li>
                </ul>
                <?php endif ?>
            </div>
        </nav>