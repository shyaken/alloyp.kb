<style>
.tr0{color:blue;}
.tr1{color:blue;border:1px solid silver;}
.tr1:hover{border:1px solid black;}
#controller-note{color:red;font-weight:bold;}
</style>

<div id="content" class="container_16 clearfix">
<div class="grid_16">
	<h2>
        Chi tiết nhóm Admin Group_ID = <?=$group->group_id?>
        <a href="<?php echo site_url('admin/manageradmin/editGroup/' . $group->group_id)?>"> Edit </a>
    </h2>
</div>

<div class="grid_8">
	<label>Tên nhóm</label>
	<p>
	<input type="text" name="group_name" <?=$group->group_name?> disabled="disabled" />
	</p>
</div>
<div class="grid_8">
	<label>Ghi chú nhóm</label>
	<p>
	<input type="text" name="comment" value="<?=$group->comment?>" disabled="disabled" />
	</p>
</div>

<div class="grid_16">
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
		<td><?php if(in_array($key, $permissions)){echo 'Được phép';}?></td>
	</tr>		
	<?php $i++; endforeach;?>
	<tr>
		<td colspan="3"><span id="controller-note">*</span> - Chỉ có root Admin mới truy cập được, dù nhóm Admin được set quyền cũng ko truy cập được</td>
	</tr>
	</table>
</div>

</div>