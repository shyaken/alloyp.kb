<div id="content" class="container_16 clearfix">
			<?php if(isset($error)):?>
				<p class="error"><?=$error?></p>
			<?php endif;?>	
			<?php if(isset($success)):?>
				<p class="success"><?=$success?></p>
			<?php endif;?>
			<div class="grid_16">
				<h2>Danh sách action reward</h2>
			</div>
			
			<form id="list_action" method="post">
			<div class="grid_16">
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Tim 1</th>
							<th>Tim 2</th>
							<th>Tim 3</th>
							<th>Tim 4</th>
							<th>Bật</th>
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if($actionrewards):?>
						<?php foreach($actionrewards as $actionreward):?>
						<tr>
							<td><?=$actionreward->id?></td>
							<td><?=$actionreward->name?></td>
							<td><?=$actionreward->t1?></td>
							<td><?=$actionreward->t2?></td>
							<td><?=$actionreward->t3?></td>
							<td><?=$actionreward->t4?></td>
							<td><?php if($actionreward->enable) { echo 'Bật';} else {echo 'Tắt';}?></td>
							<td>
								<a href="<?php echo site_url('admin/actionreward/edit/' . $actionreward->id)?>" class="edit">Edit</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
						<?php if(!$actionrewards):?>
							<center>Không tìm thấy dữ liệu</center>
						<?php endif;?>
				</div>
				</form>
</div>