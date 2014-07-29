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
	<form name="surveyadd_form" method="post" action="<?php echo site_url('admin/survey/add');?>" enctype="multipart/form-data">
	<div class="grid_16">
		<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
		<?php endif;?>	
		<h2>Thêm mới thăm dò</h2>		
	</div>

	<div class="grid_8">
		<p class="nameclass">
			<label for="title">Tên thăm dò</label>
			<input type="text" name="name" />
		</p>
	</div>

	<div class="grid_8">
		<p class="questionclass">
			<label for="title">Câu hỏi</label>
			<input type="text" name="question" />
		</p>
	</div>

	<div class="grid_8">
		<p class="option1class">
			<label>Lựa chọn 1</label>
			<input type="text" name="option1" size="90" />
		</p>
	</div>
	<div class="grid_8">
		<p class="option2class">
			<label>Lựa chọn 2</label>
			<input type="text" name="option2" size="90" />
		</p>
	</div>
	<div class="grid_8">
		<p>
			<label>Lựa chọn 3</label>
			<input type="text" name="option3" size="90" />
		</p>
	</div>
	<div class="grid_8">
		<p>
			<label>Lựa chọn 4</label>
			<input type="text" name="option4" size="90" />
		</p>
	</div>	
	<div class="grid_8">
		<p>
			<label>Lựa chọn 5</label>
			<input type="text" name="option5" size="90" />
		</p>
	</div>	
	<div class="grid_5">
		<p>
			<label>Publish</label>
			<select name="publish">
				<option value="true">Yes</option>
				<option value="false">No</option>
			</select>
		</p>
	</div>	
	

	<div class="grid_3">
		<label>Hành động</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="insert" value="Insert" onclick="return validate();" />
		</p>
	</div>
	</form>
</div>