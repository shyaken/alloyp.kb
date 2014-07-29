<div id="content" class="container_16 clearfix">
			<div class="grid_10">
				<h2>Danh sách textad</h2>
			</div>
			<div class="grid_6" style="text-align: right;">
				<h2>
					<a href="<?php echo site_url('admin/textnote/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="27px" /></a>
    			</h2>
			</div>
			
			<form id="list_cat" method="post">
			<div class="grid_16">
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Key</th>
                            <th>Group</th>
                            <th>Comment</th>
							<th width="10%">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if($textnotes):?>
						<?php foreach($textnotes as $textnote):?>
						<tr>
							<td><?=$textnote->id?></td>
							<td><?=$textnote->key?></td>
                            <td><?=$textnote->group?></td>
                            <td><?=$textnote->comment?></td>
							<td>
                                <?php 
                                    $ckeditor = 0;
                                    if($textnote->group == 'help') $ckeditor = 1;
                                ?>
								<a href="<?php echo site_url('admin/textnote/edit/' . $textnote->id . '/' . $ckeditor)?>" class="edit">Edit</a>
							</td>
						</tr>
						<?php endforeach;?>
						<?php endif;?>
					</tbody>
				</table>
						<?php if(!$textnotes):?>
							<center>Không tìm thấy dữ liệu</center>
						<?php endif;?>
				</div>
				</form>
</div>