<style>
.tr0{color:blue;}
.tr1{color:blue;border:1px solid silver;}
.tr1:hover{border:1px solid black;}
#controller-note{color:red;font-weight:bold;}
</style>

<div id="content" class="container_16 clearfix">
<?php if(isset($error)):?>
	<p class="error"><?=$error?></p>
<?php endif;?>	
<?php if(isset($success)):?>
	<p class="success"><?=$success?></p>
<?php endif;?>
<div class="grid_16">
	<h2>Chỉnh sửa nhóm Admin [<a href="<?php echo site_url('admin/manageradmin/group')?>">Admin Group</a>]</h2>
</div>

<form action="<?php echo site_url('admin/manageradmin/editGroup/' . $group->group_id)?>" method="post">
<div class="grid_8">
	<label>Tên nhóm</label>
	<p>
	<input type="text" name="group_name" value="<?=$group->group_name?>" />
	</p>
</div>
<div class="grid_8">
	<label>Ghi chú nhóm</label>
	<p>
	<input type="text" name="comment" value="<?=$group->comment?>" />
	</p>
</div>

<div class="grid_16">
	<label>Danh sách quyền truy cập</label>
	<table id="list-controller">
	<tr style="color:green;font-weight:bold;">
		<td width="25%">Controller Name</td>
		<td width="60%">Chức năng</td>
		<td width="20%">Cho phép quản lý</td>
	</tr>
	<?php $i=0; foreach($controllers as $key=>$value):?>
	<tr class="tr<?=$i%2?>">
		<td><?=$key?></td>
		<td><?=$value?></td>
		<td><input type="checkbox" name="<?=$key?>" <?php if(in_array($key, $permissions)){echo 'checked="checked"';}?> /></td>
	</tr>		
	<?php $i++; endforeach;?>
	<tr>
		<td colspan="2"><span id="controller-note">*</span> - Chỉ có root Admin mới truy cập được, không phải set quyền vô ích</td>
		<td><input type="submit" value="Lưu lại thiết lập" name="edit" /></td>
	</tr>
	</table>
</div>
</form>

</div>