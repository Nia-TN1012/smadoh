        <br class="container mt-5" />
        <script src="<?= site_url( "js/bootstrap.bundle.min.js" ) ?>"></script>
        <footer class="p-1 bg-light text-secondary">
            <?php if( UserModel::is_admin() ): ?>
            <div class="ml-2 float-left"><a class="text-secondary" href="/about"><i class="fas fa-info-circle"></i> システム情報</a></div>
            <?php endif ?>
            <div class="mr-2 text-right">powerd by <a class="text-secondary" href="https://github.com/Nia-TN1012/smadoh" target="_blank"><?= $this->config->item( 'app_name' ) ?></a> | version <?= $this->config->item( 'app_version' ) ?></div>
        </footer>
    </body>
</html>