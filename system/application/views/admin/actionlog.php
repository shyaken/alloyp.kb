<?php 
$CI =& get_instance();
$CI->load->model('user_model', 'user');
$CI->load->model('useraction_model', 'action');
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
					<h2>Danh sách action logs</h2>
				</div>
				
				<form name="filter_actionlog" method="post">
				<div class="grid_3">
					<p>
						<label>User ID</label>
						<input type="text" name="userid" value="<?php if(isset($userid) && $userid != '-1') echo $userid?>" />
					</p>
				</div>
				<div class="grid_3">
					<p>
						<label>Action</label>
						<select name="action">
							<?php foreach($actions as $action):?>
								<option value="<?=$action->id?>" <?php if($actionid == $action->id) echo "selected='selected'";?>>
									<?=$action->name?>
								</option>
							<?php endforeach;?>
								<option value="-1" <?php if($actionid == "-1") echo "selected='selected'";?>>All</option>
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
								<th>
									<?php 
										$order_ = '';
										if($order == 'ASC') $order_ = 'DESC';
										else $order_ = 'ASC';
										$href = site_url("admin/actionlog/viewall/id/$order_/$startdate/$enddate/$userid/$actionid/$limit/$start");
										echo "<a href='" . $href . "'>ID</a>";
									?>
								</th>
								<th width="200px">User ID</th>
								<th width="100px">Action ID</th>
								<th width="80px">Tim 1</th>
								<th width="80px">Tim 2</th>
								<th width="80px">Tim 3</th>
								<th width="80px">Tim 4</th>
								<th>Time</th>
							</tr>
						</thead>
						<tbody>
							<?php if($logs):?>
							<?php foreach($logs as $log):?>
							<tr id='hover'>
								<td><?=$log->id?></td>
								<td><?php
										$user = $CI->user->getUserById($log->userid);
										echo $log->userid . '( ';
										if($user) echo $user->username;
										else echo 'none';
										echo ' )';	
									?></td>
								<td><?php
										$action_ = $CI->action->getAction($log->actionid);
										echo $log->actionid . '( ' . $action_->name . ' )';
									?></td>
								<td><?=$log->t1?></td>
								<td><?=$log->t2?></td>
								<td><?=$log->t3?></td>
								<td><?=$log->t4?></td>
								<td><?=date('d/m/Y H:i:s', $log->time)?></td>
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
					    
				    	url = '<?=site_url('admin/actionlog/viewall')?>' + '/<?php echo "$sort/$order/";?>' ;

				    	url += encodeURIComponent(start);
				    	url += '/' + encodeURIComponent(end) + '/';
						
				    	var userid = $('input[name=\'userid\']').attr('value');
				    	userid = (userid=='')?-1:userid;
				        url += encodeURIComponent(userid);
				    				
				    	var action = $('select[name=\'action\']').attr('value');
				    	action = (action=='')?-1:action;
				    	url += '/' + encodeURIComponent(action) + '<?php echo "/" . $url_2 . "/0"?>';
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