<div id="content" class="container_16 clearfix">
	<?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>	
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
	<div class="grid_16">
		<h2>Thêm mới Administrator</h2>
	</div>
	<script type="text/javascript">
		function setError(fieldname) {
			$("." + fieldname + "class").addClass("field-error");
		}	
		function validate()
		{
			var next = true;
			if(document.admin_form.username.value == "") {
				setError("username");
				next = false;
			}
	
			if(next) return true;
			else { alert("Điền đầy đủ thông tin về admin"); return false;}
		}

		function replaceHelp(index) {
			$('#detail-group a').attr('href','<?php echo site_url('admin/manageradmin/groupDetail')?>/' + index);
		}
	</script>
	<form name="admin_form" method="post" action="<?php echo site_url('admin/manageradmin/add');?>" enctype="multipart/form-data">
	
	<div style="float:left; width: 50%;"><label class="usernameclass">Username</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="username" size="30" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label class="passwordclass">Password</label></div>	
	<div style="float:left; width: 50%;">
            <input type="password" name="password"  size="30" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Active</label></div>	
	<div style="float:left; width: 50%;">
		<select name="is_active">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>    
	</div>
        
    <div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Is root</label></div>	
	<div style="float:left; width: 50%;">
		<select name="is_root">
                    <option value="1">Yes</option>
                    <option value="0" selected="selected">No</option>
        </select>    
	</div>
	
    <div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Admin Group</label></div>	
	<div style="float:left; width: 50%;">
		<select name="group_id" id="group_id" onchange="replaceHelp(this.options[selectedIndex].value);">
             <?php foreach($groups as $group):?>
             <option value="<?=$group->group_id?>"><?=$group->group_name?></option>
             <?php endforeach;?>
        </select>
        <span id="detail-group"><a href="<?php echo site_url('admin/manageradmin/groupDetail/' . $groups[0]->group_id)?>" target="_blank">Chi tiết</a></span>    
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	<div>
		<input type="reset" value="Nhập lại" />
		<input type="submit" value="Thêm mới" name="insert" onclick="return validate();" />
	</div>
	</form>
</div>