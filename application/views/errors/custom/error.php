<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
    <h1 class="p-2 text-white" style="background-color:#DD4827">HTTP <?= $error_code ?> <?= $error_code_name ?></h1>
	<br class="container mt-5" />
	<div class="card">
        <div class="card-header bg-danger text-white">
			<i class="fas fa-times"></i> <?= $error_message ?>
        </div>
        <div class="card-body">
            <p>リクエストURL: <?= current_url() ?></p>
            <div class="card">
                <div class="card-header bg-light">
                    <i class="fas fa-question-circle"></i> 追加情報
                </div>
                <div class="card-body">
                    <?= @$additional_info ?: "なし" ?>
                </div>
            </div>
		</div>
	</div>
</div>