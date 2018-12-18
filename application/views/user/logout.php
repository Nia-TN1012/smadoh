<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2 text-white" style="background-color:#4B64A1">ログアウト</h1>
    <br class="container mt-5" />
	<div id="panel" class="m-2 alert alert-success" role="alert">ログアウトしました。5秒後にトップへ戻ります。</div>
</div>

<script type="text/javascript">
    $( document).ready( function() {
        setTimeout( () => {
            window.location.href = "<?= base_url() ?>";
        }, 5000 );
    });
</script>