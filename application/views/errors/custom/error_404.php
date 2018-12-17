<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#DD4827;color:#fff">HTTP 404 Not Found</h1>
	<br class="container mt-5" />
	<div class="card">
        <div class="card-header bg-danger text-white">
			<i class="fas fa-times"></i> 指定のページ <?= current_url() ?> は存在しません。
        </div>
        <div class="card-body">
			<?= @$additional_info ?: "URLに誤りがないか、ご確認ください。" ?>
		</div>
	</div>
</div>