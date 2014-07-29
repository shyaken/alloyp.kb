<div id="content" class="container_16 clearfix">
<div class="grid_16">
    <h2>
        Danh sách logo
        <a href="<?=site_url('admin/logo/add')?>">Thêm mới</a>
    </h2>
</div>
<script type="text/javascript">
function deleteLogo(id) {
    var confirm = window.confirm('Bạn có chắc chắn xóa logo này không???');
    if(!confirm) return ;
    $.ajax({
        type: "POST",
        data: "id=" + id,
        url: "<?php echo site_url('admin/logo/delete')?>",
        beforeSend: function() {
                $("#tr" + id).html("<td colspan='5' align='center'>đang xóa, chờ lát nhá!!!</td>");
        },
        success: function(response) {
            alert(response);
            if(response == '0') {
                $("#tr" + id).hide('slow');
            } else {
                alert('Logo đang được dùng, ko thể xóa');
                window.location.reload();
            }
        } 
    });
}
</script>

<div class="grid_16">
    <table>
            <thead>
                    <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Default</th>
                            <th width="15%">Actions</th>
                    </tr>
            </thead>
            <tbody>
                    <?php if($logos):?>
                    <?php foreach($logos as $logo):?>
                    <tr id="tr<?=$logo->id?>">
                            <td><?=$logo->id?></td>
                            <td><?=$logo->name?></td>
                            <td>
                                <?php if(file_exists('./'.$logo->image)) { ?>
                                <img src="<?=base_url().$logo->image?>" width="200px" height="80px" />
                                <?php } else { ?>
                                Chưa có ảnh
                                <?php }?>
                            </td>
                            <td>
                                    <?php 
                                        if($logo->default == 1) {
                                            echo 'Đang mặc định';
                                        } else {
                                            echo '<a href="'.site_url('admin/logo/setDefault/'.$logo->id).'">Thiết lập mặc định</a>';
                                        }
                                    ?>
                            </td>
                            <td>
                                    <a href="<?php echo site_url('admin/logo/edit/' . $logo->id)?>" class="edit">Edit</a>
                                     | <a href="javascript:deleteLogo(<?=$logo->id?>);">Delete</a>
                            </td>
                    </tr>
                    <?php endforeach;?>
                    <?php endif;?>
            </tbody>
    </table>
                    <?php if(!$logos):?>
                            <center>Không tìm thấy dữ liệu</center>
                    <?php endif;?>
    </div>
</div>
