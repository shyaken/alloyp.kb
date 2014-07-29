<div id="content" class="container_16 clearfix">
	<script type="text/javascript">
		function setError(fieldname) {
			$("." + fieldname + "class").addClass("field-error");
		}	
		function validate()
		{
			var next = true;
			if(document.surveyadd_form.name.value == "") {
				setError("name");
				next = false;
			}
			if(document.surveyadd_form.question.value == "") {
				setError("question");
				next = false;
			}
			if(document.surveyadd_form.option1.value == "") {
				setError("option1");
				next = false;
			}
			if(document.surveyadd_form.option2.value == "") {
				setError("option2");
				next = false;
			}
	
			if(next) return true;
			else { alert("Vui lòng điền đầy đủ thông tin"); return false;}
		}
	</script>
	<form name="actionedit_form" method="post" action="<?php echo site_url('admin/actionreward/edit/' . $actionreward->id);?>" enctype="multipart/form-data">
	<div class="grid_16">
		<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
		<?php endif;?>
		<h2>Chỉnh sửa action '<?=$actionreward->name?>'</h2>
	</div>

	<div class="grid_2">
		<p class="nameclass">
			<label for="title">Name</label>
			<input type="text" name="name" value="<?=$actionreward->name?>" disabled="disabled" />
		</p>
	</div>
	<div class="grid_2">
		<p class="nameclass">
			<label for="title">Bật</label>
			<select name="enable">
				<option value="1" <?php if($actionreward->enable) echo 'selected="selected"'?>>Bật</option>
				<option value="0" <?php if(!$actionreward->enable) echo 'selected="selected"'?>>Tắt</option>
			</select>
		</p>
	</div>

	<div class="grid_2">
		<p class="questionclass">
			<label for="title">Tym đỏ(t1)</label>
			<input type="text" name="t1" value="<?=$actionreward->t1?>" />
		</p>
	</div>
	<div class="grid_2">
		<p class="option1class">
			<label>Tym tím(t2)</label>
			<input type="text" name="t2" value="<?=$actionreward->t2?>" />
		</p>
	</div>
	<div class="grid_3">
		<p class="option2class">
			<label>Tym xanh(t3)</label>
			<input type="text" name="t3" value="<?=$actionreward->t3?>" />
		</p>
	</div>
	<div class="grid_3">
		<p>
			<label>Tym vàng(t4)</label>
			<input type="text" name="t4" value="<?=$actionreward->t4?>" />
		</p>
	</div>

	<div class="grid_2">
		<label>Hành động</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="edit" value="Edit" />
		</p>
	</div>
	</form>
</div>