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
				<div class="grid_9">
					<h2>
						Danh sách ứng dụng - <a href="<?=site_url('admin/managerapp/flushCache')?>">Xóa Cache</a>
					</h2>
				</div>
				<div class="grid_7" style="text-align: right;" id="button_menu">
					<h2>
						<a href="<?php echo site_url('admin/upload')?>">
							<img src="<?=base_url()?>style/admin/Add.png" height="27px" />
						</a>
						<a href="javascript:;" onclick="deleteApp();">
							<img src="<?=base_url()?>style/admin/Delete.png" height="27px" />
						</a>
						<a href="javascript:;" onclick="publishApp();">
							<img src="<?=base_url()?>style/admin/Open.gif" height="27px" />
						</a>
						<a href="javascript:;" onclick="unpublishApp();">
							<img src="<?=base_url()?>style/admin/Closed.png" height="27px" />
						</a>
						<a href="javascript:;" onclick="stickyApp();">
							<img src="<?=base_url()?>style/admin/Sticky.gif" height="27px" width="70px" />
						</a>
						<a href="javascript:;" onclick="unstickyApp();">
							<img src="<?=base_url()?>style/admin/Unsticky.gif" height="27px" width="70px" />
						</a>
					</h2>
				</div>
				<script type="text/javascript">
				function deleteApp() {
					var confirm = window.confirm('Bạn có chắc chắn xóa ứng dụng (s) này không???');
					if(!confirm) return ;
					$('#list_app').attr('action', '<?php echo site_url('admin/managerapp/delete')?>');
					$('#list_app').submit();
				}
				function publishApp() {
					//var confirm = window.confirm('Bạn có chắc chắn bật ứng dụng (s) này không???');
					//if(!confirm) return false;
					$('#list_app').attr('action', '<?php echo site_url('admin/managerapp/publish')?>');
					$('#list_app').submit();
				}	
				function unpublishApp() {
					//var confirm = window.confirm('Bạn có chắc chắn tắt ứng dụng (s) này không???');
					//if(!confirm) return false;
					$('#list_app').attr('action', '<?php echo site_url('admin/managerapp/unpublish')?>');
					$('#list_app').submit();
				}		
				function stickyApp() {
					$('#list_app').attr('action', '<?php echo site_url('admin/managerapp/sticky')?>');
					$('#list_app').submit();
				}
				function unstickyApp() {
					$('#list_app').attr('action', '<?php echo site_url('admin/managerapp/unsticky')?>');
					$('#list_app').submit();
				}

				// sticky process
				function stickyID(app_id, value) {
					$.ajax({
						type: "POST",
						data: "app_id=" + app_id + "&value=" + value,
						url: "<?php echo site_url('admin/managerapp/stickyID')?>",
						beforeSend: function() {
							$("#sticky" + app_id).html("working");
						},
						success: function(response) {
							$("#sticky" + app_id).html(response);
						} 
					});
				}
				
				// publish process
				function publishID(app_id, value) {
					$.ajax({
						type: "POST",
						data: "app_id=" + app_id + "&value=" + value,
						url: "<?php echo site_url('admin/managerapp/publishID')?>",
						beforeSend: function() {
							$("#publish" + app_id).html("working");
						},
						success: function(response) {
							$("#publish" + app_id).html(response);
						} 
					});
				}
                                
                                // load apps
                                function loadApps()
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
				<form name="filter_app" method="post">
				<div class="grid_3">
					<p>
						<label>Tên ứng dụng</label>
						<input type="text" name="app_name" value="<?php if(isset($app_name) && $app_name != '0') echo $app_name?>" />
					</p>
				</div>
				<div class="grid_3">
					<p>
						<label>Nhà cung cấp</label>
						<input type="text" name="vendor" value="<?php if(isset($vendor) && $vendor != '0') echo $vendor?>" />
					</p>
				</div>
				<div class="grid_3">
					<p>
						<label>Category</label>
						<select name="category">
							<?php foreach($categories as $category):?>
								<option value="<?=$category->category_id?>" <?php if($category_filter == $category->category_id) echo "selected='selected'";?>>
									<?=$category->category_name?>
								</option>
							<?php endforeach;?>
								<option value="0" <?php if($category_filter == "-1" || $category_filter == "0") echo "selected='selected'";?>>All</option>
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
                                 <td colspan="5">
                                      <?php echo $this->pagination->create_links();?>
                                 </td>
					
                                 <td colspan="2">
                                 	  Apps/Page
                                      <select id="limit" style="width:60px;" onchange="loadApps();">
                                           <option value="10" <?php if($url_2 == 10) echo 'selected="selected"';?>>10</option>
                                           <option value="25" <?php if($url_2 == 25) echo 'selected="selected"';?>>25</option>
                                           <option value="50" <?php if($url_2 == 50) echo 'selected="selected"';?>>50</option>
                                           <option value="100" <?php if($url_2 == 100) echo 'selected="selected"';?>>100</option>
                                           <option value="500" <?php if($url_2 == 500) echo 'selected="selected"';?>>500</option>
                                      </select>
                                 </td>
                                 	 <td colspan="5" align="left"><b>Tổng số Apps = <?=$totalApps?></b> <?=$info?></td>
                                 </tr>
							<tr>
								<th width="6">
									<input style="width: 20px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
								</th>
								<th>
									<?php 
										$order_ = '';
										if($order == 'ASC') $order_ = 'DESC';
										else $order_ = 'ASC';
										$href = site_url("admin/managerapp/viewall/app_id/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>ID</a>";
									?>
								</th>
								<th width="40px">Image</th>
								<th>
									<?php 
										$href = site_url("admin/managerapp/viewall/is_sticky/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Sticky</a>";
									?>
								</th>
								<th>
									<?php 
										$href = site_url("admin/managerapp/viewall/app_name/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Name</a>";
									?>
								</th>
								<th>
									<?php 
										$href = site_url("admin/managerapp/viewall/vendor/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Vendor</a>";
									?>
								</th>
								<th>Category</th>
								<th width="15%">Version<br />/ Link</th>
								<th width="15%">
									<?php
										$href = site_url("admin/managerapp/viewall/comment/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Comment</a>";
									?>
									<br />/ 
									<?php
										$href = site_url("admin/managerapp/viewall/report/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Report</a>";
									?>
								</th>
								<th width="15%">
									<?php
										$href = site_url("admin/managerapp/viewall/download/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Download</a>";
									?>
									<br />
									/ 
									<?php
										$href = site_url("admin/managerapp/viewall/view/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>View</a>";
									?>
								</th>
								<th>
									<?php 
										$href = site_url("admin/managerapp/viewall/publish/$order_/$startdate/$enddate/$app_name/$vendor/$category_filter/$limit/$start");
										echo "<a href='" . $href . "'>Publish</a>";
									?>
								</th>
								<th width="10%">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if($apps):?>
							<?php foreach($apps as $app):?>
							<tr>
								<td><input style="width: 20px;" type="checkbox" name="selected[]" value="<?=$app->app_id;?>" />
								<td><?=$app->app_id?></td>
								<td><img src="<?=base_url() . $app->image?>" width="40px" height="40px" style="vertical-align: middle" /></td>
								<td id="sticky<?=$app->app_id?>">
									<?php 
										if($app->is_sticky == 1) {
											echo '<a href="javascript:;" onclick="stickyID(' . $app->app_id . ',0);">Tắt đi</a>';
										} else {
											echo '<a href="javascript:;" onclick="stickyID(' . $app->app_id . ',1);">Bật lên</a>';
										}
									?>
								</td>
								<td><a href="<?php echo site_url('admin/managerapp/detail/' . $app->app_id)?>"><?=$app->app_name?></a></td>
								<td><?=$app->vendor?></td>
								<td>
									<?php 
										$CI =& get_instance();
										$CI->load->model('app_model', 'app');
										$category = $CI->app->getCatInfo($app->category);
										if($category) echo $category->category_name;
									?>
								</td>
								<td>
									<?php echo $CI->app->totalVersionByAppId($app->app_id);?>
									/
									<?php echo $CI->app->totalLinkDownloadByAppId($app->app_id);?>
								</td>
								<td>
									<a href="<?php echo site_url('admin/managerapp/listComment/' . $app->app_id);?>">
									<?php echo $CI->app->totalCommentByAppId($app->app_id);?>
									</a>
									/
									<?=$app->report?>
									
								</td>
								<td>
									<?=$app->download?>
									/
									<?=$app->view?>
								</td>
								<td id="publish<?=$app->app_id?>">
									<?php 
										if($app->publish == 1) {
											echo '<a href="javascript:;" onclick="publishID(' . $app->app_id . ',0);">Tắt đi</a>';
										} else {
											echo '<a href="javascript:;" onclick="publishID(' . $app->app_id . ',1);">Bật lên</a>';
										}
									?>
								</td>
								<td>
									<a href="<?php echo site_url('admin/managerapp/edit/' . $app->app_id)?>" class="edit">Edit</a>
								</td>
							</tr>
							<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>
							<?php if(!$apps):?>
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
					    
				    	url = '<?=site_url('admin/managerapp/viewall')?>' + '/<?php echo "$sort/$order/";?>' ;

				    	url += encodeURIComponent(start);
				    	url += '/' + encodeURIComponent(end) + '/';
						
				    	var app_name = $('input[name=\'app_name\']').attr('value');
				    	app_name = (app_name=='')?0:app_name;
				        url += encodeURIComponent(app_name);
				    				
				    	var vendor = $('input[name=\'vendor\']').attr('value');
				    	vendor = (vendor=='')?0:vendor;
				    	url += '/' + encodeURIComponent(vendor);
				
				    	var category = $('select[name=\'category\']').attr('value');
				    	category = (category=='')?0:category;
				    	url += '/' + encodeURIComponent(category) + '<?php echo "/" . $url_2 . "/0"?>';
				    	window.location.href = url;
				    }    
				 </script>				
			</div>