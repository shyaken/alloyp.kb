<div id="content" class="container_16 clearfix">
	
	<form method="post" enctype="multipart/form-data" name="upload_form" action="<?php echo site_url('admin/upload')?>">
	<div class="grid_16">
		<h2>Thêm ứng dụng mới</h2>
		<?php if(isset($status)):?>
			<?php foreach($status as $value):?>
			<p class="success"><?=$value?></p>
			<?php endforeach;?>
		<?php endif;?>	
	</div>
	
	<div class="grid_10">
		<p>
			<label for="crawler_link">Link for crawler<small>lấy thông tin ứng dụng từ link này</small></label>
			<input type="text" name="crawler_link" id="crawler_link" />
		</p>
	</div>
	<div class="grid_4">
		<p>
			<label>Thể loại</label>
			<select name="optiontype" id="optiontype">
				<option value="app">Application</option>
				<option value="noapp">Ebook || Film</option>
			</select>
		</p>
	</div>
	<div class="grid_2">
		<p>
			<label for="crawler">Crawler Info</label>
			<a href="javascript:;" onclick="show_form();">Crawler</a>
		</p>
	</div>	
	
	<span id="crawler_result" style="text-align:left;"></span>

	<div class="grid_16">
		<h2>Các phiên bản của ứng dụng 
		<a href='javascript:;' onclick='addApp();'>+</a>
		<a href='javascript:;' onclick='removeApp();'>-</a>
		</h2>
	</div>
	
	<div style="clear:both;">
	<table id="version-table">
	<tr>
		<td width="20%"><label>Version</label></td>
		<td width="70%"><label>&nbsp;&nbsp;&nbsp;Link download</label></td>
        <td width="10%"><label>Thu phí</label></td>
	</tr>
	<tr id="tr-version-0">
		<td width="20%">
			<input type='text' name='version0' size='30' />
		</td>
		<td width="70%" id="td-link-0">
			<span id='link00'>
			&nbsp;&nbsp;&nbsp;
			<input type='text' name='link0[]' size='70' />
			<a href="javascript:;" onclick="addLink('td-link-0', '0');">+</a>
			</span>
		</td>
        <td width="10%">
            <input type="checkbox" name="price0" checked="checked" id="pricecheckbox" />
        </td>
	</tr>
	</table>
	</div>
	
	<div class="grid_2">
		<label>Sticky</label>
		<p>
			<select name="is_sticky">
				<option value="0">Không</option>
				<option value="1">Có</option>
			</select>
		</p>
	</div>
	<div class="grid_4">
		<label>Hành động</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="insert" value="Insert" onclick="return validate();" />
		</p>
	</div>
	<input type='hidden' name='currentApp' id='currentApp' value='0' />				
	</form>
	
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
				setError("category");
				next = false;
			}
			if(next) return true;
			alert("Điền đầy đủ thông tin cho ứng dụng");
			document.upload_form.app_name.focus();
			return false;
		}
</script>
		
		<script>
		function show_form() {
			var optiontype = $("#optiontype").val();
			if(optiontype == "app") {
				check_app();
			} else {
				ebook_film();
			}
		}

		function check_app() {
			$("#crawler_result").addClass("grid_16");
			var crawler_link = $("#crawler_link").val();

			$.ajax({
				type: "POST",
				data: "step=check&crawler_link=" + crawler_link,
				url: "<?php echo site_url('admin/upload/crawler')?>",
				beforeSend: function() {
					$("#crawler_result").html("<font color='green'>please wait ...</font>");
				},
				success: function(response) {
					if(response == 0) {
						crawler_app();return;
					} 
					if(response == 1 || response == 2 || response == 3) {
						if(response == 1) {
							var confirm = window.confirm("Hệ thống phát hiện có ứng dụng trùng tên và link! Bạn có muốn tiếp tục???");
						} else if(response == 2) {
							var confirm = window.confirm("Hệ thống phát hiện có ứng dụng trùng tên! Bạn có muốn tiếp tục???");
						} else {
							var confirm = window.confirm("Hệ thống phát hiện có ứng dụng trùng link! Bạn có muốn tiếp tục???");
						}
						
						if(confirm) {
							crawler_app();
						} else {
							window.location.href = "<?php echo site_url('admin/managerapp')?>";
						}
					} else {
						$("#crawler_result").html(response);
					}
				}
			
			});
		}
		
		function crawler_app() {
			$("#crawler_result").addClass("grid_16");
			var crawler_link = $("#crawler_link").val();
			
			$.ajax({
				type: "POST",
				data: "crawler_link=" + crawler_link,
				url: "<?php echo site_url('admin/upload/crawler')?>",
				beforeSend: function() {
					$("#crawler_result").html("<font color='green'>please wait ...</font>");
				},
				success: function(response) {
					$("#crawler_result").removeClass("grid_16");
					$("#crawler_result").html(response);
				}
			});
		}

		function ebook_film() {		
			$.ajax({
				type: "POST",
				url: "<?php echo site_url('admin/upload/ebookfilm')?>",
				beforeSend: function() {
					$("crawler_result").html("<font color='green'>please wait ...</font>");
				},
				success: function(response) {
					$("#crawler_result").html(response);
				}
			});
		}
	</script>	
	
	<script type="text/javascript">
	var currentApp = 0;

	function addApp() {
		currentApp++;
		var tr_id = "tr-version-" + currentApp;
		var ver_name = "version" + currentApp;
		var link = "link" + currentApp + "[]";
        var price = "price" + currentApp;
		var file = "file" + currentApp;
		var td_link_id = "td-link-" + currentApp;
		var remove_link = "&nbsp;&nbsp;&nbsp;&nbsp;";
		var more_link = "&nbsp;<a href=\"javascript:;\" onclick=\"addLink('td-link-" + currentApp + "', '" + currentApp + "');\">+</a>";
		
		var txt = "<tr id='" + tr_id + "'>";
		txt += "<td width='20%'><input type='text' name='" + ver_name + "' size='30' /></td>";
		txt += "<td width='70%' id='" + td_link_id + "'><span>" + remove_link + "<input type='text' name='" + link + "' size='70' />" + more_link + "</span></td>";
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
