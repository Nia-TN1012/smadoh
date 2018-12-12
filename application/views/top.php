<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
	<h1 class="p-2" style="background-color:#4B64A1;color:#fff"><?= $this->config->item( 'home_title' ) ?> トップページ</h1>
	<?php if( UserModel::is_login() ): ?>
	<br class="container mt-5" />
	<div class="card">
        <div class="card-header bg-info text-white">
            <i class="fas fa-info-circle"></i> TOPICS
        </div>
        <div class="card-body">
			<?php if( !empty( $feed_data ) ): ?>
			<table class="table table-borderless table-sm table-responsive">
				<tbody>
					<?php foreach( $feed_data as $feed ): ?>
						<tr>
							<td>&middot;</td>
							<td><?= $feed['create_time'] ?></td>
							<td><span class="badge badge-info"><?= $this->config->item( $feed['category'] ) ?></span></td>
							<td><?= h( $feed['content'] ) ?></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
			<?php else: ?>
				<p>最近のアクションはありません。</p>
			<?php endif ?>
		</div>
	</div>
	<?php endif ?>
</div>