<div id="content" class="container_16 clearfix">
	<script type="text/javascript">
		function validate()
		{
			if(document.upload_form.app_name.value == "") {
				alert('app name is blank!');
				return false;
			} else if(document.upload_form.size.value == "") {
				alert('app size is blank!');
				return false;
			} else if(document.upload_form.version.value == "") {
				alert('app version is blank!');
				return false;
			} else if(document.upload_form.vendor.value == "") {
				alert('app vendor is blank!');
				return false;
			} else if(document.upload_form.vendor_site.value == "") {
				alert('app vendor site is blank!');
				return false;
			} else if(document.upload_form.publish_date.value == "") {
				alert('app publish date is blank!');
				return false;
			} else if(document.upload_form.description.value == "") {
				alert('app description is blank!');
				return false;
			} else if(document.upload_form.link.value == "") {
				alert('app link download is blank!');
				return false;
			} else if(document.upload_form.application.value == "") {
				alert('choose file to upload!');
				return false;
			} else {
				return true;
			}
		}
	</script>
	<form name="upload_form" method="post" action="<?php echo site_url('admin/upload');?>" enctype="multipart/form-data">
	<div class="grid_16">
		<?php if(isset($error)):?>
			<p class="error"><?=$error?></p>
		<?php endif;?>	
		<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
		<?php endif;?>
		<h2>Thêm ứng dụng mới</h2>		
	</div>

	<div class="grid_5">
		<p>
			<label for="title">Tên ứng dụng</label>
			<input type="text" name="app_name" value="<?php if($crawler) echo $app->name?>" />
		</p>
	</div>

	<div class="grid_5">
		<p>
			<label for="title">Kích thước <small>Mb</small></label>
			<input type="text" name="size" value="<?php if($crawler) echo $app->size?>" />
		</p>
			
	</div>
	<div class="grid_6">
		<p>
			<label for="title">Phiên bản</label>
			<input type="text" name="version" value="<?php if($crawler) echo $app->version?>" />
		</p>
	</div>
	
	<div class="grid_5">
		<p>
			<label for="title">Nhà cung cấp</label>
			<input type="text" name="vendor" value="<?php if($crawler) echo $app->vendor?>" />
		</p>
	</div>

	<div class="grid_5">
		<p>
			<label for="title">Trang chủ của nhà cung câp</label>
			<input type="text" name="vendor_site" value="<?php if($crawler) echo $app->vendor_site?>" />
		</p>
			
	</div>
	<div class="grid_6">
		<p>
			<label for="title">Publish date<small>yyyy-mm-dd</small></label>
			<input type="text" name="publish_date" value="<?php if($crawler) echo $app->released?>" />
		</p>
	</div>

	<div class="grid_16">
		<p>
			<label>Mô tả ứng dụng</label>
			<textarea name="description" rows="6"><?php if($crawler) echo $app->description?></textarea>
		</p>
	</div>
	<div class="grid_16">
		<p>
			<label for="crawler_link">Link download<small>nhiều link thì ngăn cách bằng @@</small></label>
			<input type="text" name="link" />
		</p>
	</div>
		
	<div class="grid_13">
		<p>
			<label for="crawler_link">Link for crawler<small>lấy thông tin ứng dụng từ link này</small></label>
			<input type="text" name="crawler_link" value="<?php if($crawler) echo $crawler_link;?>" />
		</p>
	</div>
	
	<div class="grid_2">
		<p>
			<label for="crawler">Crawler</label>
			<input type="submit" name="crawler" value="Crawler" size="60" />
		</p>
	</div>
	
	<div class="grid_13">
		<p>
			<label>Tải ứng dụng <small>lựa chọn ứng dụng để tải lên</small></label>
			<input type="file" name="application" size="110" />
		</p>
	</div>	
	
	<div class="grid_3">
		<label>Upload now</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="insert" value="Insert" onclick="return validate();" />
		</p>
	</div>				
	</form>
	
</div>