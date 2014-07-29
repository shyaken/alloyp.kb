<?php 
$CI =& get_instance();
?>
<link rel="stylesheet" href="<?=base_url()?>style/datepicker/themes/base/jquery.ui.all.css" type="text/css" media="screen" charset="utf-8" />
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.core.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.widget.js"></script>
<script src="<?php echo base_url()?>/style/datepicker/ui/jquery.ui.datepicker.js"></script>
<style>
#hover:hover{color:green;font-weight:bold;}
</style>
<div id="content" class="container_16 clearfix">
				<?php if(isset($error)):?>
					<p class="error"><?=$error?></p>
				<?php endif;?>	
				<?php if(isset($success)):?>
					<p class="success"><?=$success?></p>
				<?php endif;?>
				<div class="grid_16">
					<h2>Danh sách logs quy đổi tym</h2>
				</div>
				
				<form name="filter_actionlog" method="post">
				<div class="grid_2">
					<p>
						<label>User ID</label>
						<input type="text" name="userid" value="<?php if(isset($userid) && $userid != '-1') echo $userid?>" />
					</p>
				</div>
                <div class="grid_3">
					<p>
						<label>Username</label>
						<input type="text" name="username" value="<?php if(isset($username) && $username != '-1') echo $username?>" />
					</p>
				</div>
				<div class="grid_4">
					<p>
						<label>Kiểu tym nhận được</label>
						<select name="tx_type">
                            <option value="t2" <?php if(isset($tx_type) && $tx_type == 't2') echo 'selected="selected"'; ?>>t2</option>
                            <option value="t3" <?php if(isset($tx_type) && $tx_type == 't3') echo 'selected="selected"'; ?>>t3</option>
                            <option value="t4" <?php if(isset($tx_type) && $tx_type == 't4') echo 'selected="selected"'; ?>>t4</option>
                        </select>
					</p>
				</div>
				<div class="grid_3">
					<p>
						<label>Start</label>
					<input type="text" id="start_datepicker" style="font-size: 14px;" />
					</p>
				</div>
				<div class="grid_3">
					<p>
						<label>End</label>
					<input type="text" id="end_datepicker" style="font-size: 14px;" />
					</p>
				</div>				
				<div class="grid_1">
					<p>
						<label>&nbsp;</label>
						<input type="submit" value="Lọc" onclick="filter(); return false;" />
					</p>
				</div>
				</form>
				
				<form id="list_app" method="post">
				<div class="grid_16">
					<table>
						<thead>
							<tr>
                                 <td colspan="3">
                                      <?php echo $this->pagination->create_links();?>
                                 </td>
					
                                 <td colspan="1">
                                      <select id="limit" style="width:60px;" onchange="loadLogs();">
                                           <option value="10" <?php if($url_2 == 10) echo 'selected="selected"';?>>10</option>
                                           <option value="25" <?php if($url_2 == 25) echo 'selected="selected"';?>>25</option>
                                           <option value="50" <?php if($url_2 == 50) echo 'selected="selected"';?>>50</option>
                                           <option value="100" <?php if($url_2 == 100) echo 'selected="selected"';?>>100</option>
                                           <option value="500" <?php if($url_2 == 500) echo 'selected="selected"';?>>500</option>
                                      </select>
                                 </td>
                                 	 <td colspan="4" align="left"><b>Tổng số Logs = <?=$totalLogs?></b> <?=$info?></td>
                                 </tr>
							<tr>
								<th width="50px">
									<?php 
										$order_ = '';
										if($order == 'ASC') $order_ = 'DESC';
										else $order_ = 'ASC';
										$href = site_url("admin/exchangelog/viewall/id/$order_/$startdate/$enddate/$userid/$username/$tx_type/$limit/$start");
										echo "<a href='" . $href . "'>ID</a>";
									?>
								</th>
								<th width="60px">User ID</th>
								<th width="120px">Username</th>
                                <th width="80px">Tx_type</th>
                                <th width="100px">T1 dùng</th>
								<th width="100px">Tỷ lệ quy đổi</th>
                                <th width="100px">Tx nhận được</th>
								<th width="150px">Thời gian</th>
                                <th width="80px">Chi tiết</th>
							</tr>
						</thead>
						<tbody>
							<?php if($logs):?>
							<?php foreach($logs as $log):?>
							<tr id='hover'>
								<td><?=$log->id?></td>
								<td><?=$log->user_id?></td>
								<td><?=$log->username?></td>
                                <td><?=$log->tx_type?></td>
                                <td><?=$log->t1_used?></td>
								<td><?=$log->rate?></td>
                                <td><?=$log->tx_receive?></td>
								<td><?=date('d/m/Y H:i:s', $log->time)?></td>
                                <td><a href="<?=site_url('admin/exchangelog/detail/'.$log->id)?>" target="_blank">Chi tiết</a></td>
							</tr>
							<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>
							<?php if(!$logs):?>
								<center>Không tìm thấy dữ liệu</center>
							<?php endif;?>
					</div>
					</form>
				<script type="text/javascript">
				    function filter() {
				    	var starttime = $("#start_datepicker").val();
				    	var endtime = $("#end_datepicker").val();
				    	if(starttime == "" || endtime == "") {
				    		starttime = "0";
				    		endtime = "0";
				    	}

				    	var start = starttime.replace(/\//g, "_");
				    	var end = endtime.replace(/\//g, "_");
					    
				    	url = '<?=site_url('admin/exchangelog/viewall')?>' + '/<?php echo "$sort/$order/";?>' ;

				    	url += encodeURIComponent(start);
				    	url += '/' + encodeURIComponent(end) + '/';
						
				    	var userid = $('input[name=\'userid\']').attr('value');
				    	userid = (userid=='')?-1:userid;
				        url += encodeURIComponent(userid) + '/';
                        
                        var username = $('input[name=\'username\']').attr('value');
				    	username = (username=='')?-1:username;
				        url += encodeURIComponent(username);
				    				
				    	var tx_type = $('select[name=\'tx_type\']').attr('value');
				    	tx_type = (tx_type=='')?-1:tx_type;
				    	url += '/' + encodeURIComponent(tx_type) + '<?php echo "/" . $url_2 . "/0"?>';
				    	//alert(url);
                        window.location.href = url;
				    }  

					 // load logs
                    function loadLogs()
                    {
                        var limit = $("#limit").val();
                        var url = "<?=$url_1;?>" + "/" + limit + "/" + "<?=$url_3;?>";
                        window.location.href = url;
                    }

                    $(function() {
                    	$( "#start_datepicker" ).datepicker();
                    	$( "#end_datepicker" ).datepicker();
                    });  
				 </script>				
			</div>