<div id="content" class="container_16 clearfix">
			<?php if(isset($error)):?>
				<p class="error"><?=$error?></p>
			<?php endif;?>	
			<?php if(isset($success)):?>
				<p class="success"><?=$success?></p>
			<?php endif;?>
			<div class="grid_10">
				<h2>Danh sách textad</h2>
			</div>
			<div class="grid_6" style="text-align: right;">
				<h2>
					<?php if(count($texts) < 2):?>
					<a href="<?php echo site_url('admin/textad/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="27px" /></a>
					<?php endif;?>
					<a href="<?php echo site_url('admin/textad/delete')?>" onclick="var confirm=window.confirm('Bạn có chắc chắn xóa toàn bộ dữ liệu textad ??? Hãy cẩn thận!');if(confirm){return true;}else{return false;}"><img src="<?=base_url()?>style/admin/Delete.png" height="27px" /></a>
				</h2>
			</div>
			<script type="text/javascript">
			function deletecat() {
				var confirm = window.confirm('Bạn có chắc chắn xóa textad (s) này không???');
				if(!confirm) return ;
				$('#list_cat').attr('action', '<?php echo site_url('admin/textad/delete')?>');
				$('#list_cat').submit();
			}
			</script>
			
			<form id="list_cat" method="post">
			<div class="grid_16">
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Type</th>
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if($texts):?>
						<?php foreach($texts as $text):?>
						<tr>
							<td><?=$text->id?></td>
							<td><?=$text->name?></td>
							<td><?=$text->type?></td>
							<td>
								<a href="<?php echo site_url('admin/textad/edit/')?>" class="edit">Edit</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
						<?php if(!$texts):?>
							<center>Không tìm thấy dữ liệu</center>
						<?php endif;?>
				</div>
				</form>
</div>