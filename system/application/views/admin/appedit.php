<div id="content" class="container_16 clearfix">
	<script type="text/javascript">
	function setError(fieldname) {
		$("." + fieldname + "class").addClass("field-error");
	}
	function validate()	{
		var next = true;
		if(document.upload_form.app_name.value == "") {
			setError('name');
			next = false;
		}
		if(document.upload_form.vendor.value == "") {
			setError('vendor');
			next = false;
		}
		if(document.upload_form.vendor_site.value == "") {
			setError('vendor_site');
			next = false;
		}
		if($("#listcategory").val() == "none") {
			setError('category');
			next = false;
		}
		if(next) return true;
		alert("Điền đầy đủ thông tin cho ứng dụng"); 
		document.upload_form.app_name.focus();
		return false; 
	}

	function crawler() {
		var crawler_link = $("#crawler_link").val();
		
		$.ajax({
			type: "POST",
			data: "crawler_link=" + crawler_link,
			url: "<?php echo site_url('admin/upload/crawleredit')?>",
			beforeSend: function() {
				$("#crawler_result").html("<font color='green'>please wait ...</font>");
			},
			success: function(response) {
				$("#crawler_result").html(response);
			}
		});
	}	
    
    function deleteTag(id, app_id) {
        var confirm = window.confirm('Bạn có chắc chắn muốn xóa tag này???');
        if(!confirm) return;
        $.ajax({
            type: "POST",
            data: "tag_id=" + id + "&app_id=" +app_id,
            url: "<?php echo site_url('admin/managerapp/deleteTag')?>",
            beforeSend: function() {
                //$("#crawler_result").html("<font color='green'>please wait ...</font>");
            },
            success: function(response) {
                $("#tag"+id).hide();
            }
        });
	}
</script>
	<form name="upload_form" method="post" action="<?php echo site_url('admin/managerapp/edit/' . $app->app_id);?>" enctype="multipart/form-data">
	<div class="grid_16">
		<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
		<?php endif;?>	
		<?php if(isset($status)):?>
			<?php foreach($status as $value):?>
			<p class="success"><?=$value?></p>
			<?php endforeach;?>
		<?php endif;?>	
		<h2>Chỉnh sửa ứng dụng <?=$app->app_name?></h2>
	</div>
	
	<?php if($app->applelink != ""):?>
	
	<div class="grid_14">
		<p>
			<label for="crawler_link">Link for crawler<small>lấy thông tin ứng dụng từ link này</small></label>
			<input type="text" name="crawler_link" id="crawler_link" value="<?=$app->applelink?>" />
		</p>
	</div>
	<div class="grid_2">
		<p>
			<label for="crawler">Crawler Info</label>
			<a href="javascript:;" onclick="crawler();">Crawler</a>
		</p>
	</div>	
	
	<?php endif;?>
	
	<!-- bat dau noi dung ung dung -->
	<span id="crawler_result" style="text-align:left;">

	<div class="grid_5">
		<p class="nameclass">
			<label for="title">Tên ứng dụng</label>
			<input type="text" name="app_name" value="<?php  echo $app->app_name?>" />
		</p>
	</div>

	<div class="grid_5">
		<p class="vendorclass">
			<label for="title">Nhà cung cấp</label>
			<input type="text" name="vendor" value="<?php  echo $app->vendor?>" />
		</p>
	</div>

	<div class="grid_6">
		<p>
			<label for="title">Trang chủ của nhà cung câp</label>
			<input type="text" name="vendor_site" value="<?php  echo $app->vendor_site?>" />
		</p>
			
	</div>
	
	<div class="grid_5">
		<p class="vendor_siteclass">
			<label for="category">Category</label>
			<select name="category">
				<?php foreach($categories as $category):?>
				<option value="<?=$category->category_id?>" <?php if($category->category_id == $app->category) echo "selected='selected'";?>>
					<?=$category->category_name?>
				</option>
				<?php endforeach;?>
			</select>
		</p>
	</div>
	<div class="grid_5">
		<p>
			<label>Size</label>
			<input type="text" name="size" value="<?=$app->size?>" />
		</p>
	</div>
	<div class="grid_6">
		<p>
			<label>Requirement</label>
			<input type="text" name="requirement" value="<?=$app->requirement?>" />
		</p>
	</div>
	
	<div class="grid_5">
		<p>
			<label>Publish</label>
			<select name="publish">
				<option value="1" <?php if($app->publish == 1) echo "selected='selected'"?>>Bật</option>
				<option value="0" <?php if($app->publish == 0) echo "selected='selected'"?>>Tắt</option>
			</select>
		</p>
	</div>	
	<div class="grid_6">
		<p>
			<label>Ảnh minh họa cho ứng dụng</label>
			<img src="<?=base_url() . $app->image?>" width="80px" height="80px" />
			<input type="hidden" name="thumbnail_crawler" value="<?=$app->image?>" />
			<input type="file" name="thumbnail" size="30" />
		</p>
	</div>	

    <div class="grid_16">
        <p>
            <label>Chỉnh sửa tag</label>
            <br />
            <b>Danh sách tag hiện tại</b>
            <?php if($tags):?>
            <?php foreach($tags as $tag) {?>
            <span id="tag<?=$tag->tag_id?>" style="margin-left:2px;border:1px solid red;padding:2px;width:150px;">
                <?=$tag->tag_name?> 
                (<a href="javascript:deleteTag(<?=$tag->tag_id?>, <?=$app->app_id?>);">x</a>)
            </span>
            <?php }?>
            <?php endif;?>

            <br />
            <b>Thêm mới tag (mỗi tag 1 dòng):</b>
            <textarea name="tags" cols="20" rows="5"></textarea>
        </p>
    </div>
        
	<div class="grid_16">
		<p class="descriptionclass">
			<label>Mô tả ứng dụng</label>
			<textarea name="description" rows="6"><?php echo $app->description?></textarea>
			<?php echo $editor->replace('description')?>
		</p>
	</div>
	
	</span>
	<!-- ket thuc noi dung ung dung neu crawler -->
		
	<!-- screenshot -->
	<div class="grid_16">
	<h2>Screenshot</h2>
	<table>
		<tr>
			<?php
				$img = explode('@@', $app->screenshot); 
				for($i=0; $i<count($img); $i++) {
			?>
				<td align="center" style="width:100px;">
				<input type="hidden" name="crawler_sc_link[]" value="<?=$img[$i]?>" />
				<?php if($app->screenshot != ""):?>
				<input type="checkbox" name="crawler_sc<?=$i?>" checked="checked" style="width:20px;" /><b>Save</b><br />
				<img src="<?=base_url() . $img[$i]?>" width="100px" height="150px" />
				<?php endif;?>
				</td>
			<?php } ?>
		</tr>
		<tr>
			<td>
				<a href="javascript:;" onclick="addScreenshot();">Thêm</a>
				<a href="javascript:;" onclick="removeScreenshot();">Xóa</a>
			</td>
		</tr>
	</table>
	<table id="screenshots_sc">
			
	</table>
	</div>
	<input type="hidden" name="num_morescreenshot" id="num_morescreenshot" value="0" />
		<script type="text/javascript">
		var curSc = 0;
		function addScreenshot()
		{
			var txt = '<tr><td>';
			txt += '<input type="file" name="upload_sc' + curSc + '" /></td></tr>';
			$("#screenshots_sc").append(txt);	
			curSc ++;
			$("#num_morescreenshot").val(curSc);
		}	
	
		function removeScreenshot()
		{
			if(curSc>0) {
				$("#screenshots_sc tbody>tr:last").remove();
				curSc--;
				$("#num_morescreenshot").val(curSc);
			}	
		}
	</script>
	
	<!-- end screenshot -->
	
	<div class="grid_16">
		<h2>Upload các phiên bản của ứng dụng 
		<a href='javascript:;' onclick='addApp();'>+</a>
		<a href='javascript:;' onclick='removeApp();'>-</a>
		</h2>
	</div>
	
	<div style="clear:both;">
	<table id="version-table">
	<tr>
		<td width="3%"><label></label></td>
		<td width="20%"><label>Version</label></td>
		<td width="65%"><label>Link download</label></td>
        <td width="10%"><label>Thu phí</label></td>
	</tr>
	<?php $i = 0; foreach($versions as $version): ?>
	<tr id="tr-version-<?=$i?>">
		<td width="3%">
			<select name='chose<?=$i?>' style='width: 60px;'>
				<option value='edit' selected='selected'>Sửa</option>
				<option value='delete'>Xóa</option>
			</select>
		</td>
		<td width="20%">
			<input type='hidden' name='app_version_id_<?=$i?>' value='<?=$version->app_version_id?>' />
			<input type='text' name='version<?=$i?>' value='<?=$version->version?>' size='30' />
		</td>
		<td width="65%" id="td-link-<?=$i?>">
			<span id='link<?=$i?>0'>
			<?php 
				$links = explode('@@', $version->link);
			?>
			<input type='text' name='link<?=$i?>[]' size='70' value='<?=$links[0]?>' />
			<a href="javascript:;" onclick="addLink('td-link-<?=$i?>', '<?=$i?>');">+</a>
			<?php 
				for($x=1; $x<count($links); $x++) {
			?>
					<input type='text' name='link<?=$i?>[]' size='70' value='<?=$links[$x]?>' />
			<?php
				}
			?>
			</span>
		</td>
        <td>
            <input type="checkbox" name="price<?=$i?>" <?php if($version->price) echo 'checked="checked"';?>
        </td>

	</tr>
	<?php $i++; endforeach;?>
	</table>
	</div>
	
	<div class="grid_2">
		<p>
			<label>Sticky</label>
			<select name="is_sticky">
				<option value="1" <?php if($app->is_sticky == 1) echo "selected='selected'"?>>Có</option>
				<option value="0" <?php if($app->is_sticky == 0) echo "selected='selected'"?>>Không</option>
			</select>
		</p>
	</div>
	<div class="grid_4">
		<label>Hành động</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="update" value="Cập nhật" onclick="return validate();" />
		</p>
	</div>
	<input type='hidden' name='currentApp' id='currentApp' value='<?=$totalVersion?>' />				
	</form>
	<script type="text/javascript">
	var currentApp = <?=$totalVersion?>;

	function addApp() {
		currentApp++;
		var tr_id = "tr-version-" + currentApp;
		var ver_name = "version" + currentApp;
		var link = "link" + currentApp + "[]";
		var file = "file" + currentApp;
        var price = "price" + currentApp;
		var td_link_id = "td-link-" + currentApp;
		var remove_link = "";
		var more_link = "&nbsp;<a href=\"javascript:;\" onclick=\"addLink('td-link-" + currentApp + "', '" + currentApp + "');\">+</a>";
		
		var txt = "<tr id='" + tr_id + "'><td><select name='chose" + currentApp + "' style='width: 60px;'><option value='insert'>Mới</option></select></td>";
		txt += "<td width='30%'><input type='text' name='" + ver_name + "' size='30' /></td>";
		txt += "<td width='65%' id='" + td_link_id + "'><span>" + remove_link + "<input type='text' name='" + link + "' size='70' />" + more_link + "</span></td>";
		txt += "<td width='10%'><input type='checkbox' name='" + price + "' checked='checked' id='pricecheckbox' /></td>";
        txt += "</tr>";
		$("#version-table").append(txt);
		$("#currentApp").val(currentApp);
	}

	function removeApp() {
		if(currentApp > 0) {
			$("#version-table tbody>tr:last").remove();
			currentApp--;
			$("#currentApp").val(currentApp);
		}
	}

	function addLink(td_id, curTd) {
		var cur_link = $("#" + td_id + " > span").size();
		
		var txt = "<span id='link" + curTd + cur_link + "'><br /><a href=\"javascript:;\" onclick=\"removeLink('link" + curTd + cur_link + "');$(this).remove();\">-</a>&nbsp;";
		txt += "<input type='text' name='link" + curTd + "[]' size='70' /></span>";
		$("#" + td_id).append(txt);
	}

	function removeLink(link_id) {
		$("#" + link_id).html('');
	}
	</script>
</div>