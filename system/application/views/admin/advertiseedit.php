<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>

<div id="content" class="container_16 clearfix">
	<script type="text/javascript">
		function setError(fieldname) {
			$("." + fieldname + "class").addClass("field-error");
		}	
		function validate()
		{
			var next = true;
			if(document.upload_form.name.value == "") {
				setError("name");
				next = false;
			}
			if(document.upload_form.url.value == "") {
				setError("url");
				next false;
			} 

			if(next) return true;
			else { alert("Vui lòng nhập đủ thông tin"); return false;}
		}
	</script>
		<?php if(isset($error)):?>
			<p class="error"><?=$error?></p>
		<?php endif;?>	
		<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
		<?php endif;?>	
	<h2>Chỉnh sửa quảng cáo</h2>		
	<form name="upload_form" method="post" action="<?php echo site_url('admin/ad/edit/' . $ad->id);?>" enctype="multipart/form-data">
	<div style="float:left;width:500px;">
	<table align="center">
		<tr>
			<td><h6 class="nameclass">Name</h6></td>
			<td><input type="text" name="name" size="40" value="<?=$ad->name?>" /></td>
		</tr>
		<tr>
			<td><h6 class="urlclass">Url đến</h6></td>
			<td><input type="text" name="url" size="40" value="<?=$ad->url?>" /></td>
		</tr>
                <tr>
                        <td><h6>Lựa chọn</h6></td>
                        <td>
                                <input type="radio" name="optionad" value="image" <?php if($ad->image) echo 'checked="checked"'; ?> />ảnh
                                <input type="radio" name="optionad" value="code" <?php if($ad->code) echo 'checked="checked"'; ?> />đoạn mã
                        </td>
                </tr> 
		<tr>
			<td><h6>Upload ảnh</h6></td>
			<td>
				<input type="file" name="image" size="30" /><br />
			</td>
		</tr>	
		<tr>
			<td><h6>Đoạn mã</h6></td>
			<td><textarea name="code" rows="6" cols="38"><?=$ad->code?></textarea></td>
		</tr>		
		<tr>
			<td><h6>Khu vực</h6></td>
			<td>
				<select name="section" style="width: 280px;">
					<option value="header" <?php if($ad->section=="header") echo "selected='selected'";?>>Header</option>
                                        <option value="header1" <?php if($ad->section=="header1") echo "selected='selected'";?>>Header 1</option>
					<option value="footer" <?php if($ad->section=="footer") echo "selected='selected'";?>>Footer</option>
				</select>
			</td>
		</tr>		
		<tr>
			<td><h6>Ô bắt đầu</h6></td>
			<td>
				<select name="start" style="width: 280px;">
					<option value="1" <?php if($ad->start == 1) echo "selected='selected'";?>>1</option>
					<option value="2" <?php if($ad->start == 2) echo "selected='selected'";?>>2</option>
					<option value="3" <?php if($ad->start == 3) echo "selected='selected'";?>>3</option>
					<option value="4" <?php if($ad->start == 4) echo "selected='selected'";?>>4</option>
				</select>
			</td>
		</tr>	
		<tr>
			<td><h6>Số ô cần</h6></td>
			<td>
				<select name="unit" style="width: 280px;">
					<option value="1" <?php if($ad->unit == 1) echo "selected='selected'";?>>1</option>
					<option value="2" <?php if($ad->unit == 2) echo "selected='selected'";?>>2</option>
					<option value="4" <?php if($ad->unit == 4) echo "selected='selected'";?>>4</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><h6>Kiểu sắp xếp</h6></td>
			<td>
				<select name="type" style="width: 280px;">
					<option value="ngang" <?php if($ad->type == "ngang") echo "selected='selected'";?>>Ngang</option>
					<!--  <option value="doc" <?php if($ad->type == "doc") echo "selected='selected'";?>>Dọc</option> -->
					<option value="all" <?php if($ad->type == "all") echo "selected='selected'";?>>Toàn bộ</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><h6>Publish</h6></td>
			<td>
				<select name="publish" style="width: 280px;">
					<option value="1" <?php if($ad->publish == 1) echo "selected='selected'";?>>Có</option>
					<option value="0" <?php if($ad->publish == 0) echo "selected='selected'";?>>Không</option>
				</select>
			</td>
		</tr>
                <tr>
                    <td><h6>Start Date</h6></td>
                    <td>
                        <input type="text" name="start_date" id="start_datepicker" value ="<?=date("m/d/Y",$ad->start_date)?>"/>
                    </td>
                </tr>
                <tr>
                    <td><h6>End Date</h6></td>
                    <td>
                        <input type="text" name="end_date" id="end_datepicker" value="<?=date("m/d/Y",$ad->end_date)?>"/>
                    </td>
                </tr>
		<tr>
			<td><h6>Hành động</h6></td>
			<td>
				<input type="button" value="Xem trước" onclick="preview();return false;" />
				<input type="submit" value="Chỉnh sửa" name="edit" onclick="return validate();" />
			</td>
		</tr>		
	</table>
	</div>
	<div style="float:left;width: 400px;">
		<script>
			var unitHeader = new Array(false, false, false, false);
			var unitFooter = new Array(false, false, false, false);
			function unitUsed() {
                                if(1 == <?=$checkHeader1?>) 
                                    $('td#headerno1').addClass('used');
                            
				for(var i=1; i<=4; i++) {
					$('td#header' + i).removeClass('selected');
					$('td#footer' + i).removeClass('selected');
				}
			<?php 
				foreach($unitHeader as $i) {
					echo "unitHeader[$i-1] = true;";
					echo "$('td#header$i').addClass('used');";
				}
				foreach($unitFooter as $i) {
					echo "unitFooter[$i-1] = true;";
					//echo "$('td#footer$i').removeClass('normal');";
					echo "$('td#footer$i').addClass('used');";
				}
			?>
			}
                        $(function(){
                            $("#start_datepicker").datepicker();
                        });
                        $(function(){
                            $("#end_datepicker").datepicker();
                        })

			function in_array(xArray, where) {
				for(var i=0; i<xArray.length; i++)
					if(xArray[i] == where)
						return true;
				return false;
			}
			
			function currentAd() {
				var type = '<?=$ad->type?>';
				var start = <?=$ad->start?>;
				var unit = <?=$ad->unit?>;
				var section = '<?=$ad->section?>';

				if(type == 'all') {
					var listID = [1, 2, 3, 4];
					return listID;
				}
				if(unit == 1) {
					var listID = [start];
					return listID;
				}
				if(unit == 2) {
					if(type == 'ngang') {
						var next = start;next++;
						var listID = [start, next];
						return listID;
					} 
					/*
					 else {
						var next = start;next++;next++;
						var listID = [start, next];
						return listID;
					}
					*/
				}
			}

			function addSelectedClass(listID, section) {
				for(var i=0; i<listID.length; i++)
					$('td#' + section + listID[i]).addClass('selected');
			}

			function preview() {
				var curAd = currentAd();
				unitUsed();
				var curSection = '<?=$ad->section?>';
				var section = $('select[name=\'section\']').attr('value');
				var type = $('select[name=\'type\']').attr('value');
				var start = $('select[name=\'start\']').attr('value');
				var unit = $('select[name=\'unit\']').attr('value');
                                
                                if(section == 'header1') {
                                    if(0 == <?=$checkHeader1?> ||section == "<?=$ad->section?>") 
                                        $('td#headerno1').addClass('selected');
                                    else
                                        alert('Vùng header1 đã được dùng');
                                    exit;
                                }
					
				var units = unitHeader;
				if(section == 'footer') units = unitFooter;

				if(type == 'all') {
					var all = true;
					for(var i=0; i<4; i++) {
						if(units[i] && (!in_array(curAd, i+1) || curSection != section)) {
							all = false; 
						}
					}
					if(!all || start != 1) {
						alert('Không đặt toàn bộ được');
					} else {
						for(var i=1; i<5; i++) 
							$('td#' + section + i).addClass('selected');
					} 
				} else {
					if(units[start-1] && (!in_array(curAd, start) || curSection != section)) {
						alert(section + ': ô ' + start + ' đã được sử dụng');
					} else {
						var next = start;
						if(unit == 2) {
							next++;
							//if(type == 'doc') next++;
							if(next>4) alert('Vui lòng chọn lại');
							else if(units[next-1] && !in_array(curAd, next) && curSection != section) alert(section + ': ô trong vùng chọn đã được sử dụng');
							else if(type == 'ngang' && start ==2 && unit == 2) alert('Vui lòng chọn lại 1');
							else {
								var listID = new Array(start, next);
								addSelectedClass(listID, section);
							}							
						} else if(unit == 1) {
							var listID = new Array(start);
							addSelectedClass(listID, section);
						} else {
							alert('Không thể chọn số ô cần là 4 trong trường hợp này');
						}
					}
				}
			}
		</script>
		<style>
			td.normal {
				background-color: silver;	
				height: 30px;
				line-height: 30px;
			}
			td.used {
				background-color: #434A48;	
				height: 30px;
				line-height: 30px;	
			}
			td.selected {
				background-color: blue;	
				height: 30px;
				line-height: 30px;	
			}
		</style>
		<h6>Khung xem trước</h6>
		<table>
			<tr>
				<td colspan="3" style="color:red; font-size: 16px;">
				Mỗi ô có kích thước <b>ngang/cao</b> là <b>145/120 px</b> pixel
				</td>
			</tr>
			<tr>
				<td class='normal'><font color='red'>Chưa đặt</font></td>
				<td class='used'><font color='red'>Đã đặt</font></td>
				<td class='selected'><font color='red'>Dự định đặt</font></td>
			</tr>
		</table>		
		<table cellspacing='1' cellpadding='1'>
			<tr><td colspan="2"><h6>Header</h6></td>
			<tr>
				<td class='normal' id='header1'>1</td>
				<td class='normal' id='header2'>2</td>
			</tr>
			<tr>
				<td class='normal' id='header3'>3</td>
				<td class='normal' id='header4'>4</td>
			</tr>
		</table>
                
                <table cellspacing='1' cellpadding='1'>
			<tr><td colspan="2"><h6>Header 1</h6></td>
			<tr>
				<td colspan='2' class='normal' id='headerno1'>1</td>
			</tr>
		</table>
		
		<table>
			<tr><td colspan="2"><h6>Footer</h6></td>
			<tr>
				<td class='normal' id='footer1'>1</td>
				<td class='normal' id='footer2'>2</td>
			</tr>
			<tr>
				<td class='normal' id='footer3'>3</td>
				<td class='normal' id='footer4'>4</td>
			</tr>
		</table>
	</div>
	</form>
	<script>
		unitUsed();
	</script>
</div>