<?php 
$type = array('t1','t2','t3','t4','giftcode','card', 'text', 'mp3', 'img');
?>
<div id="content" class="container_16 clearfix">
	<form name="upload_form" method="post" action="<?php echo site_url('admin/event/addgiftbox/'.$eventId);?>" enctype="multipart/form-data">
	<div class="grid_16">
		<?php if(isset($success)):?>
			<p class="success">
                <?=$success?>
                -
                <a href="<?=site_url('admin/event/editgiftbox/'.$giftboxId)?>">
                    Chỉnh sửa hộp quà này
                </a>
            </p>
		<?php endif;?>	
		<?php if(isset($status)):?>
			<?php foreach($status as $value):?>
			<p class="success"><?=$value?></p>
			<?php endforeach;?>
		<?php endif;?>	
		<h2>Thêm mới hộp quà</h2>
	</div>
        
    <!-- chi tiết hộp quà -->    
    <div class="grid_2">
        <p>
            <label>Name</label>
            <input type="text" name="name" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>TYM type</label>
            <input type="text" name="tym_type" value="t1" />
        </p>
    </div>
    <div class="grid_2">
        <p>
            <label>Input TYM</label>
            <input type="text" name="input_tym" />
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
            <input type="text" name="random" />
        </p>
    </div>
    <div class="grid_4">
        <p>
            <label>Text trả về</label>
            <input type="text" name="return_text" value="Chúc bạn may mắn lần sau" />
        </p>
    </div>
    <!-- /chi tiết hộp quà -->
	
	<div class="grid_16">
		<h2>Thêm mới quà cho hộp quà
		<a href='javascript:;' onclick='addApp();'>+</a>
		<a href='javascript:;' onclick='removeApp();'>-</a>
		</h2>
	</div>
	
    <!-- thêm mới quà trong hộp quà -->
	<div style="clear:both;">
	<table id="version-table">
	<tr>
		<td width="10%"><label>Tên quà</label></td>
		<td width="10%"><label>Loại quà</label></td>
        <td width="10%"><label>Giá trị</label></td>
        <td width="20%"><label>Text đi kèm</label></td>
        <td width="10%"><label>Số lượng</label></td>
        <td width="10%"><label>XS trúng</label></td>
        <td width="10%"><label>Ảnh</label></td>
	</tr>
	<tr id="tr-version-0">
		<td width="10%">
			<input type='text' name='name0' size='10' />
		</td>
		<td width="10%" id="td-link-0">
            <select name="type0">
                <?php foreach($type as $value):?>
                <option value="<?=$value?>"><?=$value?></option>
                <?php endforeach?>
            </select>
		</td>
        <td width="10%">
            <input type='text' name='value0' size='10' />
        </td>
        <td width="20%">
            <input type='text' name='more_text0' size='20' />
        </td>
        <td width="10%">
            <input type='text' name='quantity0' size='10' />
        </td>
        <td width="10%">
            <input type='text' name='xacsuat0' size='10' />
        </td>
        <td width="10%">
            <input type='file' name='anh0' size='10' />
        </td>
	</tr>
	</table>
	</div>
    <!-- /thêm mới quà trong hộp quà -->
	
	<div class="grid_4">
		<label>Hành động</label>
		<p class="submit">
			<input type="reset" value="Reset" />
			<input type="submit" name="insert" value="Thêm mới" onclick="return validate();" />
		</p>
	</div>
	<input type='hidden' name='currentGift' id='currentApp' value='0' />				
	</form>
    
    
    
	<script type="text/javascript">
	var currentApp = 0;

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
		txt += "<td width='10%'><input type='text' name='" + ver_name + "' size='10' /></td>";
		txt += "<td width='10%'>" + type + "</td>";
		txt += "<td width='10%'><input type='text' name='" + price + "' size='10' /></td>";
        txt += "<td width='20%'><input type='text' name='" + more_text + "' size='20' /></td>";
        txt += "<td width='10%'><input type='text' name='" + file + "' size='10' /></td>";
        txt += "<td width='15%'><input type='text' name='" + xs + "' size='10' /></td>";
        txt += "<td width='10%'><input type='file' name='" + anh + "' size='10' /></td>";
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
	</script>
</div>