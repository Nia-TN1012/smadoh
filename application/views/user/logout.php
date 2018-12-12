<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<div id="panel" class="m-2 alert alert-success" role="alert">ログアウトしました。5秒後にトップへ戻ります。</div>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        setTimeout( () => {
            window.location.href = "<?= base_url() ?>";
        }, 5000 );
    });
</script>