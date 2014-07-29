<?php $limit = 20; ?>
<div id="content" class="container_16 clearfix">
<style>
	#red-tym {color:red;}
	#yellow-tym {color:#7c29c7;}
	#blue-tym {color:green;}
	#green-tym {color:yellow;}
	.rate-input:hover {border-color:black;}
	#rate1_2 {color:#7c29c7;width:80px;text-align:center;}
	#rate1_3 {color:green;width:80px;text-align:center;}
	#rate1_4 {color:yellow;width:80px;text-align:center;}
	.tr0 {border: 1px solid silver;}
	.tr0:hover {border: 1px solid black;}
	#save-button {color:green;width:80px;text-align:center;}
	h2 {font-size:20px !important;}
    #smsxxx{font-weight:bold;color:green;}
</style>
<script>
function getSettingLog(limit) {
    var page = $('input[name=page]').val();
    if(page < 1) page = 1;
	$.ajax({
		url: "<?php echo site_url('admin/setting/loadSettingLog')?>/" + limit + '/' + page,
		beforeSend: function() {
			$('#setting_log').html('<center>loading ...</center>')
		},
		success: function(data) {
			$('#setting_log').html(data);
		}
	});
}

function saveRate(rate_id) {
	var confirm = window.confirm("Bạn có chắc chắn lưu lại tỉ lệ quy đổi???");
	if(!confirm) return;
	var value = $("#" + rate_id).val();
	$.ajax({
		url: "<?php echo site_url('admin/setting/saveRate')?>",
		data: "key=" + rate_id + "&value=" + value,
		type: "POST",
		beforeSend: function() {
			$("#button-" + rate_id).val("Saving");
			$("#button-" + rate_id).css("color","green");
		},
		success: function(data) {
			if(data == 1) {
				$("#button-" + rate_id).val("Saved");
				setTimeout("$('#button-" + rate_id + "').val('Save');", 3000);
				setTimeout("$('#button-" + rate_id + "').css('color','#333333');", 3000);
			}	
		}
	});
}

function savePartnerSMS(key) {
	var confirm = window.confirm("Bạn có chắc chắn lưu lại???");
	if(!confirm) return;
	var value = $("input[name=" + key +"]").val();
	$.ajax({
		url: "<?php echo site_url('admin/setting/savePartnerSMS')?>",
		data: "key=" + key + "&value=" + value,
		type: "POST",
		beforeSend: function() {
			$("#" + key + "_button").val("Saving");
			$("#" + key + "_button").css("color","green");
		},
		success: function(data) {
			if(data == 1) {
				$("#" + key + "_button").val("Saved");
				setTimeout("$('#" + key + "_button').val('Save');", 3000);
				setTimeout("$('#" + key + "_button').css('color','#333333');", 3000);
				getSettingLog(<?=$limit?>, 0);
			}	
		}
	});
}

function saveKey(setting_id) {
	var confirm = window.confirm("Bạn có chắc chắn lưu lại tỉ lệ quy đổi???");
	if(!confirm) return;
	var value = $('input[name=value' + setting_id + ']').val();
	$.ajax({
		url: "<?php echo site_url('admin/setting/saveKey')?>",
		data: "setting_id=" + setting_id + "&value=" + value,
		type: "POST",
		beforeSend: function() {
			$(".save-button-" + setting_id).val("Saving");
			$(".save-button" + setting_id).css("color","green");
		},
		success: function(data) {
			if(data == 1) {
				$(".save-button-" + setting_id).val("Saved");
				setTimeout("$('.save-button-" + setting_id + "').val('Save');", 3000);
				setTimeout("$('.save-button-" + setting_id + "').css('color','green');", 3000);
				getSettingLog(<?=$limit?>, 0);
			}	
		}
	});
}

$(document).ready(function(){
	getSettingLog(<?=$limit?>);
});
</script>
	<div class="grid_16">
		<h2 style="color:green;">
		Tỷ lệ quy đổi tym hiện tại là 
		[<a href="<?php echo site_url('admin/setting')?>">Refresh</a>]
		[<a href="<?php echo site_url('admin/setting/category')?>">Giá Category</a>]
		[<a href="<?php echo site_url('admin/setting/app')?>">Giá App</a>]
		</h2>
	</div>	
	
	<div class="grid_14">
		<h2>
		1 tym đỏ <span id="red-tym">♥</span> đổi được 
		<input type="text" size="5" value="<?=$rate1_2?>" id="rate1_2" class="rate-input" />
		 tym tím <span id="yellow-tym">♥</span>
		 </h2>
	</div>
	<div class="grid_2">
		<h2>
			<input type="button" onclick="saveRate('rate1_2');" id="button-rate1_2" value="Save" />
		</h2>
	</div>
	
	<div class="grid_14">
		<h2>
		1 tym đỏ <span id="red-tym">♥</span> đổi được 
		<input type="text" size="5" value="<?=$rate1_3?>" id="rate1_3" class="rate-input" />
		 tym xanh <span id="blue-tym">♥</span>
		 </h2>
	</div>
	<div class="grid_2">
		<h2>
			<input type="button" onclick="saveRate('rate1_3');" id="button-rate1_3" value="Save" />
		</h2>
	</div>
	
	<div class="grid_14">
		<h2>
		1 tym đỏ <span id="red-tym">♥</span> đổi được 
		<input type="text" size="5" value="<?=$rate1_4?>" id="rate1_4" class="rate-input" />
		 tym vàng <span id="green-tym">♥</span>
		 </h2>
	</div>
	<div class="grid_2">
		<h2>
			<input type="button" onclick="saveRate('rate1_4');" id="button-rate1_4" value="Save" />
		</h2>
	</div>

    <div class="grid_16">
        <h2 style="color:green;">Bật tab SMS</h2>
    </div>  
    <div class="grid_14">
		<input type="text" name="enable_sms" value="<?=$enable_sms->value?>" style="width:120px;height:30px;text-align:center;font-size:18px;color:blue;" />
	</div>
	<div class="grid_2">
		<h2>
			<input type="button" onclick="savePartnerSMS('enable_sms');" id="enable_sms_button" value="Save" />
		</h2>
	</div>

    <div class="grid_16">
        <h2 style="color:green;">Thiết lập đầu số SMS</h2>
    </div>
	<div class="grid_14">
		<input type="text" name="partnersms" value="<?=$partnersms->value?>" style="width:120px;height:30px;text-align:center;font-size:18px;color:blue;" />
        <a href="javascript:changeSMS('8x61');">VinaMob( <span id='smsxxx'>8x61</span> )</a> | 
        <a href="javascript:changeSMS('6x86');">VNPTG ( <span id='smsxxx'>6x86</span> )</a>
	</div>
	<div class="grid_2">
		<h2>
			<input type="button" onclick="savePartnerSMS('partnersms');" id="partnersms_button" value="Save" />
		</h2>
	</div>

    <!--
    <div class="grid_16">
	<h2 style="color:green;">Thiết lập số ngẫu nhiên để trúng quà trong sự kiện</h2>
    </div>

	<div class="grid_14">
        <input type="text" name="random_event" value="<?=$event->value?>" style="width:120px;height:30px;text-align:center;font-size:18px;color:blue;" />
        <b>trong khoảng từ (1,số ngẫu nhiên này) chỉ có 1 số là trúng</b>
    </div>
	<div class="grid_2">
		<h2>
			<input type="button" onclick="savePartnerSMS('random_event');" id="random_event_button" value="Save" />
		</h2>
	</div>
    -->
	
	<div class="grid_16">
	<h2 style="color:green;">Thiết lập tym nhận được khi nạp card, sms</h2>
	<table width="100%">
		<tr style="color:green;">
			<td style="width:10px;">setting_id</td>
			<td style="width:20%;">key</td>
			<td style="width:20%;">value</td>
			<td style="width:30%;">comment</td>
			<td style="width:20%;text-align:center;">action</td>
		</tr>
	<?php foreach($keys as $key):?>
		<tr class="tr<?=$key->setting_id%2?>">
			<td><?=$key->setting_id?></td>
			<td><?=$key->key?></td>
			<td><input type="text" value="<?=$key->value?>" name="value<?=$key->setting_id?>" style="width:100px;color:green;" /></td>
			<td><?=$key->comment?></td>
			<td style="text-align:center">
				<input type="button" onclick="saveKey(<?=$key->setting_id?>);" id="save-button" class='save-button-<?=$key->setting_id?>' value="Save" />
			</td>
		</tr>
	<?php endforeach;?>
    <?php foreach($packages as $package):?>
		<tr class="tr<?=$package->setting_id%2?>">
			<td><?=$package->setting_id?></td>
			<td><?=$package->key?></td>
			<td><input type="text" value="<?=$package->value?>" name="value<?=$package->setting_id?>" style="width:100px;color:green;" /></td>
			<td><?=$package->comment?></td>
			<td style="text-align:center">
				<input type="button" onclick="saveKey(<?=$package->setting_id?>);" id="save-button" class='save-button-<?=$package->setting_id?>' value="Save" />
			</td>
		</tr>
	<?php endforeach;?> 
    <?php foreach($popups as $popup):?>    
        <tr class="tr<?=$popup->setting_id%2?>">
            <td><?=$popup->setting_id?></td>
			<td><?=$popup->key?></td>
			<td><input type="text" value="<?=$popup->value?>" name="value<?=$popup->setting_id?>" style="width:100px;color:green;" /></td>
			<td><?=$popup->comment?></td>
			<td style="text-align:center">
				<input type="button" onclick="saveKey(<?=$popup->setting_id?>);" id="save-button" class='save-button-<?=$popup->setting_id?>' value="Save" />
			</td>
        </tr>
    <?php endforeach; ?>    
	</table>
	</div>
	
	<div class="grid_16">
		<h2 style="color:green;">
            Lịch sử thiết lập tỉ lệ quy đổi
            <input type="text" name="page" value="1" style="width:80px;text-align:center;color:red;" />
            <input type="button" style="width:80px;text-align:center;color:green;" value="Xem" onclick="getSettingLog(<?=$limit?>);" />
        </h2>
		<span id="setting_log">
			<center>loading ...</center>
		</span>
	</div>	
</div>
