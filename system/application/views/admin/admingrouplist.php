<script>
function deleteGroup(group_id) {
	var confirm = window.confirm('Bạn có chắc chắn muốn xóa nhóm Admin này ko???');
		if(!confirm) return;
	$.ajax({
		url: "<?php echo site_url('admin/manageradmin/deleteGroup')?>/" + group_id,
		beforeSend: function() {
			$('#td' + group_id).html('please wait...');
		},
		success: function() {
			$('#tr' + group_id).hide('slow');
		}
	});	
}
</script>
<div id="content" class="container_16 clearfix">
     <div class="grid_10">
          <h2>Quản lý nhóm Admin</h2>  
     </div>
     <div class="grid_6" style="text-align: right;">
          <h2>
              <a href="<?php echo site_url('admin/manageradmin/addGroup')?>"><img src="<?=base_url()?>style/admin/Add.png" height="26px" /></a>
          </h2>
     </div>	
	
	<div class="grid_16">
	<table id="list-group">
	<tr>
		<td>ID nhóm</td>
		<td>Tên nhóm</td>
		<td>Quyền xử lý</td>
		<td>Ghi chú</td>
		<td>Hành động</td>
	</tr>
	<?php if($groups) { foreach($groups as $group):?>
	<tr id="tr<?=$group->group_id?>">
		<td><?=$group->group_id?></td>
		<td><?=$group->group_name?></td>
		<td><a href="<?php echo site_url('admin/manageradmin/groupDetail/' . $group->group_id)?>" target="_blank">Xem chi tiết</a></td>
		<td><?=$group->comment?></td>
		<td id="td<?=$group->group_id?>">
			<a href="<?php echo site_url('admin/manageradmin/editGroup/' . $group->group_id)?>">Edit</a>
			|
			<a href="javascript:deleteGroup(<?=$group->group_id?>);">Delete</a>
		</td>		
	</tr>
	<?php endforeach; }?>
	</table>
	<?php if(!$groups):?>
		Chưa có nhóm Admin nào được tạo, <a href="<?php echo site_url('admin/manageradmin/addgroup')?>">tạo mới</a>	
	<?php endif;?>
	</div>
</div>