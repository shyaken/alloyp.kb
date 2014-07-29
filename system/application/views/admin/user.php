<div id="content" class="container_16 clearfix">
				<?php if(isset($error)):?>
					<p class="error"><?=$error?></p>
				<?php endif;?>	
				<?php if(isset($success)):?>
					<p class="success"><?=$success?></p>
				<?php endif;?>
				<div class="grid_14">
                    <h2>Danh sách người dùng - Tổng cộng có <b><span style="color:red;"><?=$totalUsers?></span></b> user(s) theo bộ lọc</h2>
				</div>
				<div class="grid_2" style="text-align: right;" id="button_menu">
					<h2>
						<a href="<?php echo site_url('admin/user')?>">
							<img src="<?=base_url()?>style/admin/Add.png" height="27px" />
						</a>
						<a href="javascript:;" onclick="deleteUser();">
							<img src="<?=base_url()?>style/admin/Delete.png" height="27px" />
						</a>
					</h2>
				</div>
				<script type="text/javascript">
				function deleteUser() {
					var confirm = window.confirm('Bạn có chắc chắn xóa user (s) này không??? Hãy cẩn thận!');
					if(!confirm) return ;
					$('#list_user').attr('action', '<?php echo site_url('admin/user/delete')?>');
					$('#list_user').submit();
				}
				                                
                // load users
                function loadUsers()
                {
                    var limit = $("#limit").val();
                    var url = "<?=$url_1;?>" + "/" + limit + "/0";
                    window.location.href = url;
                }
				</script>
				<form name="filter_app" method="post">
				<div class="grid_2">
					<p>
						<label>Username</label>
						<input type="text" name="username" value="<?php if(isset($username) && $username != '-1') echo $username?>" />
					</p>
				</div>
				<div class="grid_2">
					<p>
						<label>Email</label>
						<input type="text" name="email" value="<?php if(isset($email) && $email != '-1') echo $email?>" />
					</p>
				</div>
				<div class="grid_2">
					<p>
						<label>Phone</label>
						<input type="text" name="phone" value="<?php if(isset($phone) && $phone != '-1') echo $phone?>" />
					</p>
				</div>  
                <div class="grid_2">
                    <p>
                        <label>Active</label>
                        <select name="active_by">
                            <option value="">Tất cả</option>
                            <option value="email" <?php if($active_by == 'email') echo 'selected="selected"';?>>email</option>
                            <option value="sms" <?php if($active_by == 'sms') echo 'selected="selected"';?>>sms</option>
                            <option value="smsemail" <?php if($active_by == 'smsemail') echo 'selected="selected"';?>>sms & email</option>
                            <option value="inactive" <?php if($active_by == 'inactive') echo 'selected="selected"';?>>inactive</option>
                        </select>
                        
                    </p>
                </div>
                <div class="grid_2">
                    <p>
                        <label>Package</label>
                        <select name="use_package">
							<option value="">All</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        
                    </p>
                </div>    
				<div class="grid_2">
					<p>
						<label>Order by</label>
						<select name="orderby">
                            <option value="user_id" <?php if($sortby == 'user_id') echo 'selected="selected"';?>>user_id</option>
                            <option value="username" <?php if($sortby == 'username') echo 'selected="selected"';?>>username</option>
                            <option value="email" <?php if($sortby == 'email') echo 'selected="selected"';?>>email</option>
                            <option value="phone" <?php if($sortby == 'phone') echo 'selected="selected"';?>>phone</option>    
						</select>
					</p>
				</div>
                                <div class="grid_2">
					<p>
						<label>Thứ tự</label>
						<select name="order">
                            <option value="DESC" <?php if(isset($order) && $order == 'DESC') echo 'selected="selected"';?>>Mới nhất</option>
                            <option value="ASC" <?php if(isset($order) && $order != 'DESC') echo 'selected="selected"';?>>Cũ nhất</option>
						</select>
					</p>
				</div>                                    
				<div class="grid_2">
					<p>
						<label>&nbsp;</label>
						<input type="submit" value="Lọc" onclick="filter(); return false;" />
					</p>
				</div>
				</form>
				
				<form id="list_user" method="post">
				<div class="grid_16">
					<table>
						<thead>
							<tr>
								<th width="5">
									<input style="width: 20px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
								</th>
								<th>UserID</th>
								<th>Username</th>
								<th>Email</th>
                                <th>Phone</th>
								<th>Registered_date</th>
                                <th>Active_by</th>
                                <th>Xem logs</th>
								<th width="10%">Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
                                <td colspan="5" class="pagination" align="left">
                                    <?php echo $this->pagination->create_links();?>
                                </td>

                                <td>
                                        <select id="limit" style="width:60px;" onchange="loadUsers();">
                                            <option value="25" <?php if($url_2 == 25) echo 'selected="selected"';?>>25</option>
                                            <option value="50" <?php if($url_2 == 50) echo 'selected="selected"';?>>50</option>
                                            <option value="100" <?php if($url_2 == 100) echo 'selected="selected"';?>>100</option>
                                        </select>
                                </td>
                            </tr>
                                                      
						</tfoot>
						<tbody>
							<?php if($users):?>
							<?php foreach($users as $user):?>
							<tr>
								<td><input style="width: 20px;" type="checkbox" name="selected[]" value="<?=$user->user_id;?>" />
                                                                <td><?=$user->user_id?></td>    
								<td><a href="<?php echo site_url('admin/user/downloadLog/' . $user->user_id)?>" target="_blank"><?=$user->username?></a></td>
								<td><?=$user->email?></td>
                                <td><?=$user->phone?></td>
								<td><?=$user->registered_date?></td>
                                <td><?=$user->active_by?></td>
                                <td>
                                    <a href="<?php echo site_url('admin/user/downloadLog/' . $user->user_id)?>" target="_blank">Log tải</a>
                                    - 
                                    <a href="<?php echo site_url('admin/user/paymentLog/' . $user->user_id)?>" target="_blank">Log giao dịch</a>
                                </td>
								<td>
									<a href="<?php echo site_url('admin/user/edit/' . $user->user_id)?>" class="edit">Edit</a>
								</td>
							</tr>
							<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>
							<?php if(!$users):?>
								<center>Không tìm thấy dữ liệu</center>
							<?php endif;?>
					</div>
					</form>
				<script type="text/javascript">
				    function filter() {
					    var sortby = $('select[name=orderby]').attr('value');
					    var orderby = $('select[name=order]').attr('value');
				    	
                        var url = '<?=site_url('admin/user/viewall')?>/' + encodeURIComponent(sortby) + '/' + encodeURIComponent(orderby) + '/' ;
				    	
                        var username = $('input[name=username]').attr('value');
    				    	username = (username=='')?-1:username;
        			        url += encodeURIComponent(username);
				
            	    	var email = $('input[name=email]').attr('value');
                	    	email = (email=='')?-1:email;
                	    	url += '/' + encodeURIComponent(email);
                        var phone = $('input[name=phone]').attr('value');
                	    	phone = (phone=='')?-1:phone;
                	    	url += '/' + encodeURIComponent(phone);  
                    	var use_package = $('select[name=use_package]').val();
                    		use_package = (use_package == '')?-1:use_package;  
                    		url += '/' + encodeURIComponent(use_package);              	    	
                        var active_by = $('select[name=active_by]').val();
                	    	active_by = (active_by == '')?0:active_by;
                	    	url += '/' + encodeURIComponent(active_by) + '<?php echo "/25"?>';                                                        
				    	window.location.href = url;
				    }    
				 </script>				
			</div>