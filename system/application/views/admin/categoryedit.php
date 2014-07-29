<div id="content" class="container_16 clearfix">
	<?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>	
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
	<div class="grid_16">
		<h2>Chỉnh sửa chuyên mục</h2>
	</div>
	<script type="text/javascript">
		function setError(fieldname) {
			$("." + fieldname + "class").addClass("field-error");
		}	
		function validate()
		{
			var next = true;
			if(document.category_form.category_name.value == "") {
				setError("category_name");
				next = false;
			}
			if(document.category_form.price.value == "") {
				setError("price");
				next = false;
			} 
	
			if(next) return true;
			else { alert("Điền đầy đủ thông tin về chuyên mục"); return false;}
		}
	</script>
	<form name="category_form" method="post" action="<?php echo site_url('admin/category/edit/' . $id);?>" enctype="multipart/form-data">
	
	<div style="float:left; width: 50%;"><label class="category_nameclass">Tên chuyên mục</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="category_name" value="<?=$category->category_name?>" size="50" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Phương thức thanh toán</label></div>	
	<div style="float:left; width: 50%;">
		<input type="radio" name="method" value="hit" <?php if($category->method == 'hit'){echo 'checked="checked"';}?> />Chẵn lẻ
		&nbsp;&nbsp;
		<input type="radio" name="method" value="pack" <?php if($category->method == 'pack'){echo 'checked="checked"';}?> />Theo gói
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Ảnh đại diện</label></div>	
	<div style="float:left; width: 50%;">
		<input type="file" name="thumbnail" size="35" />
	</div>
		
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Bật</label></div>	
	<div style="float:left; width: 50%;">
		<input type="radio" name="publish" value="1" <?php if($category->publish == '1'){echo 'checked="checked"';}?> />Có
		&nbsp;&nbsp;	
		<input type="radio" name="publish" value="0" <?php if($category->publish == '0'){echo 'checked="checked"';}?> />Không
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Thứ tự</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="order" value="<?=$category->order?>" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	<div>
		<input type="reset" value="Nhập lại" />
		<input type="submit" value="Cập nhật" name="update" onclick="return validate();" />
	</div>
	</form>
</div>