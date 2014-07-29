<div id="content" class="container_16 clearfix">
			<?php if(isset($error)):?>
				<p class="error"><?=$error?></p>
			<?php endif;?>	
			<?php if(isset($success)):?>
				<p class="success"><?=$success?></p>
			<?php endif;?>
			<div class="grid_10">
				<h2>Danh sách category</h2>
			</div>
			<div class="grid_6" style="text-align: right;">
				<h2>
					<a href="<?php echo site_url('admin/category/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="27px" /></a>
					<a href="javascript:deletecat();"><img src="<?=base_url()?>style/admin/Delete.png" height="27px" /></a>
					<a href="javascript:publishcat();"><img src="<?=base_url()?>style/admin/Open.gif" height="27px" /></a>
					<a href="javascript:unpublishcat();"><img src="<?=base_url()?>style/admin/Closed.png" height="27px" /></a>
					<a href="javascript:saveorder();"><img src="<?=base_url()?>style/admin/Order.jpg" height="27px" /></a>
				</h2>
			</div>
			<script type="text/javascript">
			function deletecat() {
				var confirm = window.confirm('Bạn có chắc chắn xóa category (s) này không???');
				if(!confirm) return ;
				$('#list_cat').attr('action', '<?php echo site_url('admin/category/delete')?>');
				$('#list_cat').submit();
			}
			function publishcat() {
				//var confirm = window.confirm('Bạn có chắc chắn bật ứng dụng (s) này không???');
				//if(!confirm) return false;
				$('#list_cat').attr('action', '<?php echo site_url('admin/category/publish')?>');
				$('#list_cat').submit();
			}	
			function unpublishcat() {
				//var confirm = window.confirm('Bạn có chắc chắn tắt ứng dụng (s) này không???');
				//if(!confirm) return false;
				$('#list_cat').attr('action', '<?php echo site_url('admin/category/unpublish')?>');
				$('#list_cat').submit();
			}		
			function saveorder() {
				$('#list_cat').attr('action', '<?php echo site_url('admin/category/saveorder')?>');
				$('#list_cat').submit();
			}	

			// publish process
			function publishID(category_id, value) {
				$.ajax({
					type: "POST",
					data: "category_id=" + category_id + "&value=" + value,
					url: "<?php echo site_url('admin/category/publishID')?>",
					beforeSend: function() {
						$("#publish" + category_id).html("working");
					},
					success: function(response) {
						$("#publish" + category_id).html(response);
					} 
				});
			}
			</script>
			
			<form id="list_cat" method="post">
			<div class="grid_16">
				<table>
					<thead>
						<tr>
							<th width="1">
								<input style="width: 5px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
							</th>
							<th>Order</th>
							<th>Image</th>
							<th>Name</th>
							<th>Method</th>
							<th>Price</th>
							<th>Publish</th>
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if($cats):?>
						<?php foreach($cats as $cat):?>
						<tr>
							<td><input style="width: 20px;" type="checkbox" name="selected[]" value="<?=$cat->category_id;?>" />
							<td>
								<input type="hidden" name="id[]" value="<?=$cat->category_id?>" />
								<input type="text" name="order[]" value="<?=$cat->order?>" style="width: 30px;" />
							</td>
							<td valign="middle">
								<img src='<?php echo base_url() . $cat->image?>' width='60px' height='60px' style='vertical-align: middle;' />
							</td>
							<td><?=$cat->category_name?></td>
							<td><?=$cat->method?></td>
							<td><?=$cat->price?></td>
							<td id="publish<?=$cat->category_id?>">
								<?php 
										if($cat->publish == 1) {
											echo '<a href="javascript:;" onclick="publishID(' . $cat->category_id . ',0);">Bật</a>';
										} else {
											echo '<a href="javascript:;" onclick="publishID(' . $cat->category_id . ',1);">Tắt</a>';
										}
									?>
							</td>
							<td>
								<a href="<?php echo site_url('admin/category/edit/' . $cat->category_id)?>" class="edit">Edit</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
						<?php if(!$cats):?>
							<center>Không tìm thấy dữ liệu</center>
						<?php endif;?>
				</div>
				</form>
</div>