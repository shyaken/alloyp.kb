<div id="content" class="container_16 clearfix">
			<?php if(isset($success)):?>
				<p class="success"><?=$success?></p>
			<?php endif;?>
			<div class="grid_10">
				<h2>Danh sách thăm dò</h2>
			</div>
			<div class="grid_6" style="text-align: right;">
				<h2>
					<a href="<?php echo site_url('admin/survey/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="26px" /></a>
				</h2>
			</div>
			<div class="grid_16">
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Tên thăm dò</th>
							<th>Câu hỏi</th>
							<th>Ngày tạo</th>
							<th>Publish</th>
							<th colspan="2" width="20%">Actions</th>
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
						<?php if($surveys):?>
						<?php foreach($surveys as $survey):?>
						<tr>
							<td><?=$survey->id?></td>
							<td><?=$survey->name?></td>
							<td><?=$survey->question?></td>
							<td><?=date('Y-m-d H:i:s', $survey->create_date)?></td>
							<td><?php if($survey->publish){echo 'Yes';}else{echo 'No';}?></td>
							<td colspan="2">
								<a href="<?php echo site_url('admin/survey/edit/' . $survey->id)?>" class="delete">Edit</a>
								<a href="<?php echo site_url('admin/survey/delete/' . $survey->id)?>" class="delete" onclick="var x=confirm('Bạn có chắc chắn muốn xóa thăm dò này không???');if(!x){return false;}">Delete</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
						<?php if(!$surveys):?>
							<center>Không tìm thấy dữ liệu</center>
						<?php endif;?>
			</div>
		</div>