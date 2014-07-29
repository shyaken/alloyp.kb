<div id="content" class="container_16 clearfix">
                <div class="grid_10">
                    <h2>Quản lý Administrator [<a href="<?php echo site_url('admin/manageradmin/group')?>">Admin Group</a>]</h2>  
                </div>
                <div class="grid_6" style="text-align: right;">
                        <h2>
                                <a href="<?php echo site_url('admin/manageradmin/add')?>"><img src="<?=base_url()?>style/admin/Add.png" height="26px" /></a>
                        </h2>
                </div>

                <form id="list_cat" method="post">
                <div class="grid_16">
                        <table>
                                <thead>
                                        <tr>
                                                <th>ID</th>
                                                <th>Admin Group</th>
                                                <th>Username</th>
                                                <th>Active</th>
                                                <th>Is root</th>
                                                <th>Last login</th>
                                                <th>Last IP</th>
                                                <th width="15%">Actions</th>
                                        </tr>
                                </thead>
                                <tbody>
                                        <?php if($admins):?>
                                        <?php foreach($admins as $admin):?>
                                        <tr>
                                                <td><?=$admin->id?></td>
                                                <td><?=$admin->group_id?> - <a href="<?php echo site_url('admin/manageradmin/groupDetail/' . $admin->group_id)?>" target="_blank">Xem quyền</a></td>
                                                <td><?=$admin->username?></td>
                                                <td>
                                                    <?php 
                                                        if($admin->is_active) echo "Yes";
                                                        else echo "No";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if($admin->is_root) echo "Yes";
                                                        else echo "No";
                                                    ?>
                                                </td>
                                                <td><?=date('d/m/Y H:i:s', $admin->last_login);?></td>
                                                <td><?=$admin->last_ip;?></td>
                                                <td>
                                                    <a href="<?=site_url('admin/manageradmin/edit/' . $admin->id)?>">Sửa</a>
                                                    <a href="<?=site_url('admin/manageradmin/delete/' . $admin->id)?>" onclick="var x=window.confirm('Bạn có chắc chắn muốn xóa???');if(x){return true;}else{return false;}">Xóa</a>
                                                </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <?php endif;?>
                                </tbody>
                        </table>
                                        <?php if(!$admins):?>
                                                <center>Không tìm thấy dữ liệu</center>
                                        <?php endif;?>
                    </div>
                    </form>
</div>