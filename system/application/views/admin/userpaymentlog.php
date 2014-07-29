<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
<div id="content" class="container_16 clearfix">
	<div class="grid_16">
		<h2>Thống kê giao dịch ( SMS & CARD )</h2>
	</div>
	<form name="filter_app" method="post">
   	<div class="grid_3">
		<p>
			<label>Phương thức nạp</label>
            <select name="method">
                <option value="">Tất cả</option>
                <?php foreach($listMethod as $x): ?>
                <option value="<?=$x?>"><?=$x?></option>
                <?php endforeach;?>
            </select>    
		</p>
	</div>
	<div class="grid_3">
		<p>
			<label>Trạng thái giao dịch</label>
			<select name="status">
				<option value="">All</option>
				<option value="success" <?php if($status == 'success') echo 'selected="selected"';?>>Thành công</option>
				<option value="error" <?php if($status == 'error') echo 'selected="selected"';?>>Thất bại</option>
                <option value="status" <?php if($status == 'start') echo 'selected="selected"';?>>Gửi dữ liệu, chưa trả lời</option>
			</select>
		</p>
	</div>
    
	<div class="grid_3" id="search_form">
		<p>
			<label>Thời gian bắt đầu</label>
			<input type="text" id="start_datepicker" style="font-size: 14px;" />
		</p>
	</div>
	<div class="grid_3">
		<p>
			<label>Thời gian kết thúc</label>
			<input type="text" id="end_datepicker" style="font-size: 14px;" />
		</p>	
	</div>
	<div class="grid_4">
		<p>
			<label>&nbsp;</label>
			<input type="submit" value="Search" onclick="startFilter(); return false;" />
		</p>
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
				<?php if($logs):?>
				<?php foreach($logs as $tr):?>
				<tr>
					<td><?=$tr->username?></td>
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
				<?php if(!$logs):?>
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

	var url = "<?php echo site_url("admin/user/paymentLog/$userid/$sort/$order")?>/";
    
	var start = starttime.replace(/\//g, "_");
	var end = endtime.replace(/\//g, "_");
    
    var method = $('select[name=method]').val();
        method = (method == '')?0:method;
        url += encodeURIComponent(method);
        
    var status = $('select[name=status]').val();
        status = (status == '')?0:status;
        url += '/' + encodeURIComponent(status);
    url += '/' + start + "/" + end;
	
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
</script>			
</div>