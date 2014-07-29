<div id="content" class="container_16 clearfix">
				<?php if(isset($error)):?>
					<p class="error"><?=$error?></p>
				<?php endif;?>	
				<?php if(isset($success)):?>
					<p class="success"><?=$success?></p>
				<?php endif;?>
				<div class="grid_16">
					<h2>
						Danh sách Online [
						<a href="<?=site_url('admin/online/f5');?>">Cập nhật</a>
						]				
					</h2>
				</div>
				
				<form name="filter_app" method="post">
				<div class="grid_10">
					<p>
						<label>Tổng quát</label>
						Hiện tại có <?=$totalOnline?> khách đang online trong vòng 15 phút trở lại					
					</p>
				</div>
				<div class="grid_4">
					<p>
						<label>Os Name</label>
						<input type="text" name="user_agent" value="<?php if(isset($agent) && $agent != '0') echo $agent?>" />
					</p>
				</div>
				<div class="grid_2">
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
								<th>Session ID</th>
								<th>User ID</th>
								<th>IP address</th>
								<th width="50%">User Agent</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
                                     <td colspan="3" class="pagination" align="left">
                                     	<?php echo $this->pagination->create_links();?>
                                     </td>
                           </tr>
                                                      
						</tfoot>
						<tbody>
							<?php if($onlines):?>
							<?php foreach($onlines as $online):?>
							<tr>
								<td><?=$online->session_id?></td>
								<td>
									<?php
										$CI =& get_instance();
										$CI->load->model('user_model');
										$user = $CI->user_model->getUserById($online->user_id);
										if($user) {
											echo "<a href='" . site_url('admin/user/edit/' . $user->user_id) . "'>" . $user->user_id . "(" . $user->username . ")";
										} else {
											echo "0(session)";
										}
									?>
								</td>
								<td><?=$online->ip_address;?></td>
								<td><?=$online->user_agent?></td>
							</tr>
							<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>
							<?php if(!$onlines):?>
								<center>Không tìm thấy dữ liệu</center>
							<?php endif;?>
					</div>
					</form>
				<script type="text/javascript">
				    function filter() {
				    	url = '<?=site_url('admin/online/viewall')?>' + '/' ;
				
				    	var agent = $('input[name=\'user_agent\']').attr('value');
				    	agent = (agent=='')?0:agent;
				        url += encodeURIComponent(agent) + '<?php echo "/$limit/0"?>';
					
				    	window.location.href = url;
				    }    
				 </script>				
			</div>