<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
<div id="content" class="container_16 clearfix">
	<div class="grid_16">
		<h2>Thống kê giao dịch ( SMS & CARD & PAYPAL )</h2>
	</div>
	<form name="filter_app" method="post">
	<div class="grid_2">
		<p>
			Username
			<input type="text" name="username" value="<?php if(isset($username) && $username != '0') echo $username?>" />
		</p>
	</div>
	<div class="grid_2">
		<p>
			Nạp bằng
			<select name="method" onchange="changeMethod(this);">
				<option value="">All</option>
				<option value="sms" <?php if($method == 'sms') echo 'selected="selected"';?>>SMS</option>
				<option value="card" <?php if($method == 'card') echo 'selected="selected"';?>>CARD</option>
                <option value="paypal" <?php if($method == 'paypal') echo 'selected="selected"';?>>PAYPAL</option>
                <option value="bank" <?php if($method == 'bank') echo 'selected="selected"';?>>BANK</option>
			</select>
		</p>
	</div>
	<div class="grid_2">
		<p>
            <span id="user_input" style="color:red;" value="<?php if(isset($user_input) && $user_input != '') echo $status?>">Số ĐT | Mã thẻ</span>
			<input type="text" name="user_input" />
		</p>
	</div>
	<div class="grid_2">
		<p>
            Mệnh giá thẻ
            <select name="card_value">
            <option value="">Tất cả</option>
            <?php foreach($cards as $card): ?>
            <option value="<?=$card?>" <?php if($card_value == $card) echo 'selected="selected"'; ?>><?=$card?></option>    
            <?php endforeach ?>
            </select>
		</p>
	</div>          
	<div class="grid_2">
		<p>
			Trạng thái
			<select name="status">
				<option value="">Tất cả</option>
				<option value="success" <?php if($status == 'success') echo 'selected="selected"';?>>Thành công</option>
				<option value="error" <?php if($status == 'error') echo 'selected="selected"';?>>Thất bại</option>
			</select>
		</p>
	</div>        
	<div class="grid_2" id="search_form">
		<p>
			Bắt đầu
			<input type="text" id="start_datepicker" style="font-size: 14px;" />
		</p>
	</div>
	<div class="grid_2">
		<p>
			Kết thúc
			<input type="text" id="end_datepicker" style="font-size: 14px;" />
		</p>	
	</div>
	<div class="grid_2">
		<p>
			Lọc!!!<br />
			<input type="submit" value="Search" onclick="startFilter(); return false;" />
		</p>
	</div>
    <div class="grid_2">    
        <select name="sms_provider">
            <option value="">Đầu số SMS</option>
            <?php for($i=0; $i<8; $i++) { ?>
            <option value="<?=$i?>" <?php if($sms_provider == $i) echo 'selected="selected"';?>>x<?=$i?>xx</option>
            <?php } ?>
        </select>
    </div>
	</form>
	
	<style>
		#tr-even{border:1px silver solid;color:blue;}
		#tr-odd{color:green;}
		tr{border:1px solid silver;}
	</style>
	
	<div class="grid_16">
		<h2><?=$info?></h2>
	</div>
	<?php if($is_admin == 'yes'):?>
	<div class="grid_16">
		<table style="font-weight:bold;">
		<tr id="tr-odd">
			<td>Tổng số giao dịch đã thực hiện</td>
			<td><?=$totalTransaction?></td>
		</tr>
		<tr id="tr-even">
			<td>Tổng số giao dịch đã thực hiện bởi SMS</td>
			<td><?=$totalTransactionBySMS?></td>
		</tr>
		<tr id="tr-odd">
			<td>Tổng số giao dịch đã thực hiện bởi CARD</td>
			<td><?=$totalTransactionByCARD?></td>
		</tr>
		<tr id="tr-even">
			<td>Tổng số Tym đỏ đã nạp vào tài khoản người dùng</td>
			<td><?=$totalTym?></td>
		</tr>
		<tr id="tr-odd">
			<td>Tổng số Tym đỏ đã nạp vào tài khoản người dùng thông qua SMS</td>
			<td><?=$totalTymBySMS?></td>
		</tr>
		<tr id="tr-even">
			<td>Tổng số Tym đỏ đã nạp vào tài khoản người dùng thông qua CARD</td>
			<td><?=$totalTymByCARD?></td>
		</tr>
        <tr id="tr-odd">
			<td>Tổng số tym đỏ còn lại của tất cả người dùng</td>
			<td><?=$remainT1?></td>
		</tr>
		<tr id="tr-even">
			<td>Tổng số tym tím còn lại của tất cả người dùng</td>
			<td><?=$remainT2?></td>
		</tr>
        <tr id="tr-odd">
			<td>Tổng số tym xanh còn lại của tất cả người dùng</td>
			<td><?=$remainT3?></td>
		</tr>
		<tr id="tr-even">
			<td>Tổng số tym vàng còn lại của tất cả người dùng</td>
			<td><?=$remainT4?></td>
		</tr>
		</table>
	</div>	
	<?php endif?>
	<div class="grid_16">
		<table>
			<thead>
				<tr>
					<th>Username</th>
					<th>Tym đỏ</th>
					<th>Phương thức</th>
					<th>Nội dung</th>
					<th>Trạng thái</th>
					<th>Thời gian</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6" class="pagination">
						<?php echo $this->pagination->create_links();?>
					</td>
				</tr>
			</tfoot>
			<tbody>
                <?php
                    $CI =& get_instance();
                    $CI->load->model('user_model');
                ?>
				<?php if($transactions):?>
				<?php foreach($transactions as $tr):?>
				<tr>
					<td>
                        <?php
                            $user = $CI->user_model->getUserByUsername($tr->username);
                            if($user) {
                                $txt = '<a href="' . site_url('admin/user/edit/'.$user->user_id) . '" target="_blank">' . $tr->username . '</a>';
                                echo $txt;
                            } else {
                                echo $tr->username;
                            }
                        ?>
                    </td>
					<td><?=$tr->t1?></td>
					<td><?=$tr->method?></td>
					<td><a href="#" onclick="detailTransaction(<?=$tr->id?>);return false;"><?=substr($tr->comment, 0, 60) . ' ... '?></a></td>
					<td><?=$tr->status?></td>
					<td><?=date('Y-m-d H:i:s', $tr->time)?></td>
				</tr>
				<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
				<?php if(!$transactions):?>
					<center>Không tìm thấy dữ liệu</center>
				<?php endif;?>
	</div>
	
	<div style="display:none;">
		<div id="detail_transaction">loading</div>
	</div>
	<style>#detail_transaction{width:600px;float:none;margin:0 auto;text-align:center;}</style>
	
<script type="text/javascript">

$(function() {
	$( "#start_datepicker" ).datepicker();
	$( "#end_datepicker" ).datepicker();
});

function startFilter() {
	var starttime = $("#start_datepicker").val();
	var endtime = $("#end_datepicker").val();
	if(starttime == "" || endtime == "") {
		starttime = "0";
		endtime = "0";
	}

	var start = starttime.replace(/\//g, "_");
	var end = endtime.replace(/\//g, "_");

	var url = "<?php echo site_url('admin/usertransaction/viewAll/' . $sort . '/' . $order)?>/";
	
	var username = $('input[name=username]').val();
		username = (username=='')?0:username;
		url += encodeURIComponent(username);
	var method = $('select[name=method]').val();
		method = (method=='')?0:method;
		url += '/' + encodeURIComponent(method);
    var user_input = $('input[name=user_input]').val();
        user_input = (user_input == '')?-1:user_input;
        url += '/' + encodeURIComponent(user_input);
    var sms_provider = $('select[name=sms_provider]').val();
        sms_provider = (sms_provider == '')?-1:sms_provider;
        url += '/' + encodeURIComponent(sms_provider);
    var card_value = $('select[name=card_value]').val();
        card_value = (card_value == '')?0:card_value;
        url += '/' + encodeURIComponent(card_value);
 	var status = $('select[name=status]').val();
		status = (status=='')?0:status;
		url += '/' + encodeURIComponent(status);       
	url += '/' + start + '/' + end + '/<?=$limit?>/<?=$start?>';	
	window.location.href = url;
}

function detailTransaction(transId) {
	$.ajax({
		url: "<?php echo site_url('admin/usertransaction/detail')?>/" + transId,
		beforeSend: function(){
			$('#detail_transaction').html('loading ...');
		},
		success:function(data) {
			$('#detail_transaction').html(data);
		}
	})
	$.fn.colorbox({width:"100%", height:"500px", inline:true, href:"#detail_transaction"});
}

function changeMethod(obj) {
    var method = obj.options[obj.selectedIndex].value;
    if(method == "card") {
        $('#user_input').html('Mã thẻ cào');
    } else if(method == 'sms') {
        $('#user_input').html('Số điện thoại')
    } else {
        $('#user_input').html('Số ĐT | Mã thẻ');
    }
}
</script>			
</div>
