<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<div id="content" class="container_16 clearfix">
	<?php if(isset($error)):?>
		<p class="error"><?=$error?></p>
	<?php endif;?>	
	<?php if(isset($success)):?>
		<p class="success"><?=$success?></p>
	<?php endif;?>
	<div class="grid_16">
		<h2>Chỉnh sửa sự kiện</h2>
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
			else { alert("Điền đầy đủ thông tin về sự kiện"); return false;}
		}
	</script>
	<form name="category_form" method="post" action="<?php echo site_url('admin/event/edit/'.$event->event_id);?>" enctype="multipart/form-data">
	<div style="float:left; width: 50%;"><label class="category_nameclass">Tên sự kiện</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="name" value="<?=$event->name?>" size="50" />
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Loại sự kiện</label></div>	
	<div style="float:left; width: 50%;">
        <select name="type_id">
            <?php
                $types = $this->event_model->allEventType();
                foreach($types as $type):?>
            ?>
            <option value="<?=$type->type_id?>"<?php if($event->type_id == $type->type_id){echo ' selected="selected"';}?>><?=$type->name?></option>
            <?php endforeach;?>
        </select>
	</div>
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
    
    <div style="float:left; width: 50%;"><label>Mô tả</label></div>	
	<div style="float:left; width: 50%;">
        <textarea name="desc" cols="30" rows="5"><?=$event->desc?></textarea>
	</div>
	
    <div style="clear: both; margin-top: 10px; height: 10px;"></div>
    
    <div style="float:left; width: 50%;"><label class="category_nameclass">Nhà tài trợ</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="sponsor" value="<?=$event->sponsor?>" size="50" />
	</div>
    
    <div style="clear: both; margin-top: 10px; height: 10px;"></div>
    
	<div style="float:left; width: 50%;"><label>Ảnh đại diện</label></div>	
	<div style="float:left; width: 50%;">
		<input type="file" name="thumbnail" size="35" />
	</div>
    
    <div style="clear: both; margin-top: 10px; height: 10px;"></div>
    
	<div style="float:left; width: 50%;"><label>Thời hạn</label></div>	
	<div style="float:left; width: 50%;">
		<input type="text" name="expired_time" id="expired_time" value="<?=date('Ymd', $event->expired_time)?>" />
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	
	<div style="float:left; width: 50%;"><label>Bật</label></div>	
	<div style="float:left; width: 50%;">
		<input type="radio" name="active" value="1" <?php if($event->active) echo 'checked="checked"'; ?> />Có
		&nbsp;&nbsp;
		<input type="radio" name="active" value="0" <?php if(!$event->active) echo 'checked="checked"'; ?> />Không
	</div>	
	
	<div style="clear: both; margin-top: 10px; height: 10px;"></div>
	<div>
		<input type="reset" value="Nhập lại" />
		<input type="submit" value="Cập nhật" name="update" onclick="return validate();" />
	</div>
	</form>
</div>

<script>
$(function() {
    $( "#expired_time" ).datepicker({ dateFormat: 'yymmdd' });
});
</script>