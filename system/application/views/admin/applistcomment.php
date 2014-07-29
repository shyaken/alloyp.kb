<div id="content" class="container_16 clearfix">
			<?php if(isset($success)):?>
			<p class="success"><?=$success?></p>
			<?php endif;?>
			<div class="grid_10">
					<h2>Danh sách bình luận trong ứng dụng [ <a href="<?php echo site_url('admin/managerapp/commentfilter')?>">Bộ lọc từ</a> ]</h2>
				</div>
				<div class="grid_6" style="text-align: right;">
					<h2>
						<a href="javascript:deleteListComment();">
							<img src="<?=base_url()?>style/admin/Delete.png" height="27px" />
						</a>
					</h2>
				</div>
				<script type="text/javascript">
				function deleteListComment() {
					var confirm = window.confirm('Bạn có chắc chắn xóa bình luận (s) này không???');
					if(!confirm) return false;
					$('#list_comment').attr('action', '<?php echo site_url('admin/managerapp/deleteCommentList/' . $app_id)?>');
					$('#list_comment').submit();
				}
                                function deleteCM(app_id,comment_id) {
                                    var confirm = window.confirm('Bạn có chắc chắn xóa bình luận này không???');
                                    if(!confirm) return false;
                                    $.ajax({
                                        type: "POST",
                                        data: "appid=" + app_id + "&id=" + comment_id,
                                        url: "<?php echo site_url('admin/managerapp/deleteComment')?>",
                                        beforeSend: function() {
                                            $("#comment" + comment_id).html('Working');
                                        },
                                        success: function(){
                                            $("#comment" + comment_id).hide();
                                        }
                                            
                                    })
                                }
				</script>
			<form id="list_comment" method="POST" action="">	
			<div class="grid_16">
				<table>
					<thead>
						<tr>
							<th width='1px'>
								<input style="width: 5px;" type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
							</th>
							<th>Tên ứng dụng</th>
							<th>Người post</th>
							<th>Nội dung</th>
							<th>Thời gian</th>
							<th colspan="2" width="10%">Actions</th>
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
						<?php if($comments):?>
						<?php foreach($comments as $comment):?>
                                            <tr id="comment<?=$comment->comment_id?>">
							<td>
								<input style="width: 5px;" type="checkbox" name="selected[]" value="<?=$comment->comment_id?>" />
							</td>
							<td>
								<?php 
									$CI =& get_instance();
									$CI->load->model('app_model', 'app');
									$CI->load->model('user_model', 'user');
									$user = $CI->user->getUserById($comment->user_id);
                                    if(!$user) $user->username = 'unknow'; 
									echo $app->app_name;
								?>
							</td>
							<td><?=$user->username?></td>
							<td><?=$comment->content?></td>
							<td><?=$comment->post_date?></td>
							<td>
                                                            <a href="javascript:;" onclick="deleteCM('<?=$comment->app_id?>','<?=$comment->comment_id?>')" class="delete">Delete</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
						<?php if(!$comments):?>
							<center>Không tìm thấy dữ liệu</center>
						<?php endif;?>
			</div>
			</form>
		</div>