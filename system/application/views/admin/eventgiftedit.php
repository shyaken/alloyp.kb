<?php 
$type = array('t1','t2','t3','t4','giftcode','card', 'text', 'mp3', 'img');
?>
<div id="content" class="container_16 clearfix">
	<form name="upload_form" method="post" action="<?php echo site_url('admin/event/editgiftbox/'.$giftboxId);?>" enctype="multipart/form-data">
	<div class="grid_16">
		<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
		<?php endif;?>	
		<?php if(isset($status)):?>
			<?php foreach($status as $value):?>
			<p class="success"><?=$value?></p>
			<?php endforeach;?>
		<?php endif;?>	
		<h2>Chỉnh sửa hộp quà</h2>
	</div>
        
    <!-- chi tiết hộp quà -->    
    <div class="grid_2">
        <p>
            <label>Name</label>
            <input type="text" name="name" value="<?=$giftbox->name?>" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>TYM type</label>
            <input type="text" name="tym_type" value="<?=$giftbox->tym_type?>" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>Input TYM</label>
            <input type="text" name="input_tym" value="<?=$giftbox->input_tym?>" />
        </p>
    </div>
    <div class="grid_3">
        <p>
            <label>Image</label>
            <input type="file" name="image" size="5" />
        </p>
    </div>
    <div class="grid_3">
        <p>
            <label>Số ngẫu nhiên</label>
            <input type="text" name="random" value="<?=$giftbox->random?>" />
        </p>
    </div>
    <div class="grid_4">
        <p>
            <label>Text trả về</label>
            <input type="text" name="return_text" value="<?=(!$giftbox->return_text)?"Chúc bạn may mắn lần sau":$giftbox->return_text?>" />
        </p>
    </div>
    <!-- /chi tiết hộp quà -->
	
	<div class="grid_16">
		<h2>Thêm mới/chỉnh sửa quà cho hộp quà
		<a href='javascript:;' onclick='addApp();'>+</a>
		<a href='javascript:;' onclick='removeApp();'>-</a>
		</h2>
	</div>
	
    <!-- thêm mới quà trong hộp quà -->
	<div style="clear:both;">
	<table id="version-table">
	<tr>
        <td width="10%"></td>
		<td width="10%"><label>Tên quà</label></td>
		<td width="10%"><label>Loại quà</label></td>
        <td width="10%"><label>Giá trị</label></td>
        <td width="15%"><label>Text đi kèm</label></td>
        <td width="10%"><label>Số lượng</label></td>
        <td width="10%"><label>Xác suất trúng</label>
        <td width="10%"><label>Ảnh</label>
        </td>
	</tr>
    <?php if($gifts):$i=0;foreach($gifts as $gift):?>
	<tr id="tr-version-<?=$i?>">
        <td width="10%">
            <select name="chose<?=$i?>">
                <option value="edit">Sửa</option>
                <option value="delete">Xóa</option>
            </select>
        </td>
		<td width="10%">
			<input type='text' name='name<?=$i?>' value="<?=$gift->name?>" size='10' />
		</td>
		<td width="10%" id="td-link-<?=$i?>">
            <select name="type<?=$i?>">
                <?php foreach($type as $value):?>
                <option value="<?=$value?>" <?php if($value == $gift->type) echo 'selected="selected"'; ?>><?=$value?></option>
                <?php endforeach?>
            </select>
		</td>
        <td width="10%">
            <input type='text' name='value<?=$i?>' value="<?=$gift->value?>"  size='10' />
        </td>
        <td width="15%">
            <input type='text' name='more_text<?=$i?>' value="<?=$gift->more_text?>"  size='15' />
        </td>
        <td width="10%">
            <input type='text' name='quantity<?=$i?>' value="<?=$gift->quantity?>"  size='10' />
        </td>
        <td width="10%">
            <input type='text' name='xacsuat<?=$i?>' value="<?=$gift->xacsuat?>"  size='10' />
        </td>
        <td width="10%">
            <input type='file' name='anh<?=$i?>' size='8' />
        </td>
	</tr>
    <input type="hidden" name="gift_id<?=$i?>" value="<?=$gift->gift_id?>" />
    <?php $i++;endforeach;endif;?>
	</table>
	</div>
    <!-- /thêm mới quà trong hộp quà -->
	
	<div class="grid_4">
		<label>Hành động</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="update" value="Chỉnh sửa" onclick="return validate();" />
		</p>
	</div>
    <?php $total = 0; if($gifts) $total = count($gifts)-1; ?>
	<input type='hidden' name='currentGift' id='currentApp' value='<?=$total?>' />				
	</form>
    
	<script type="text/javascript">
	var currentApp = <?=$total?>;

	function addApp() {
		currentApp++;
		var tr_id = "tr-version-" + currentApp;
		var ver_name = "name" + currentApp;
		var link = "type" + currentApp;
        var price = "value" + currentApp;
        var more_text = "more_text" + currentApp;
		var file = "quantity" + currentApp;
        var xs = "xacsuat" + currentApp;
        var anh = "anh" + currentApp;
        var type = "";
        type += '<select name="' + link + '">"';
        <?php foreach($type as $value): ?>
            type += '<option value="<?=$value?>"><?=$value?></option>';
        <?php endforeach?>		
            type += '</select>';
		var txt = "<tr id='" + tr_id + "'>";
        txt += "<td width='10%'><select name='chose" + currentApp + "'>";
            txt += "<option value='new'>Mới</option>";
            txt += "</select></td>";
		txt += "<td width='10%'><input type='text' name='" + ver_name + "' size='10' /></td>";
		txt += "<td width='10%'>" + type + "</td>";
		txt += "<td width='10%'><input type='text' name='" + price + "' size='10' /></td>";
        txt += "<td width='15%'><input type='text' name='" + more_text + "' size='15' /></td>";
        txt += "<td width='10%'><input type='text' name='" + file + "' size='10' /></td>";
        txt += "<td width='10%'><input type='text' name='" + xs + "' size='10' /></td>";
        txt += "<td width='10%'><input type='file' name='" + anh + "' size='8' /></td>";
        txt += "</tr>";
		$("#version-table").append(txt);
		$("#currentApp").val(currentApp);
	}

	function removeApp() {
		if(currentApp > <?=$total?>-1) {
			$("#version-table tbody>tr:last").remove();
			currentApp--;
			$("#currentApp").val(currentApp);
		}
	}
	</script>
</div>