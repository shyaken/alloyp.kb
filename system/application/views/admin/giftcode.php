<?php 
$CI =& get_instance();
$CI->load->model('user_model');
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
    <h2>
        Danh sách giftcode
        -
        <a href="<?=site_url('admin/giftcode/add')?>">Thêm mới Giftcode</a>
    </h2>
</div>

<form name="filter_actionlog" method="post">
<div class="grid_2">
    <p>
        <label>giftcode</label>
        <input type="text" name="giftcode" value="<?php if(isset($code) && $code != '0') echo $code?>" />
    </p>
</div>
<div class="grid_2">
    <p>
        <label>Type</label>
        <select name="type">
            <option value="-1">All</option>
            <option value="t1"<?php if($type == 't1'){echo ' selected="selected"';}?>>t1</option>
            <option value="t2"<?php if($type == 't2'){echo ' selected="selected"';}?>>t2</option>
            <option value="app"<?php if($type == 'app'){echo ' selected="selected"';}?>>App</option>
            <option value="film"<?php if($type == 'film'){echo ' selected="selected"';}?>>Film</option>
        </select>
    </p>
</div>
<div class="grid_2">
    <p>
        <label>Sender</label>
        <input type="text" name="sender" />
    </p>
</div>
<div class="grid_2">
    <p>
        <label>Status</label>
        <select name="status">
            <option value="-1">All</option>
            <option value="1" <?php if($status == 1) echo 'selected="selected"'; ?>>Yes</option>
            <option value="0" <?php if(!$status) echo 'selected="selected"'; ?>>No</option>
        </select>
    </p>
</div>   
<div class="grid_2">
    <p>
        <label>Create date</label>
        <input type="text" name="create" id="create_date" />
    </p>
</div>    
<div class="grid_2">
    <p>
        <label>Use date</label>
        <input type="text" name="use" id="use_date" />
    </p>
</div>     
<div class="grid_2">
    <p>
        <label>Expire date</label>
        <input type="text" name="expire" id="expire_date" />
    </p>
</div> 
<div class="grid_2">
    <p>
        <label>Limit</label>
        <select name="limit">
            <option value="10" <?php if($limit == 10) echo 'selected="selected"';?>>10</option>
            <option value="25" <?php if($limit == 25) echo 'selected="selected"';?>>25</option>
            <option value="50" <?php if($limit == 50) echo 'selected="selected"';?>>50</option>
        </select>
    </p>
</div>     
<div class="grid_2">
    <p>
        <label>Lọc</label>
        <input type="button" onclick="filter();" value="Filter" />
    </p>
</div>    
</form>

<form id="list_app" method="post">
<div class="grid_16">
    <table>
        <thead>
            <tr>
                 <td colspan="4">
                      <?php echo $this->pagination->create_links();?>
                 </td>
                     <td colspan="4" align="left"><b>Tổng số Giftcode = <?=$totalGiftcode?></b></td>
                 </tr>
            <tr>
                <th width="50px">
                    <?php 
                        $order_ = '';
                        if($order == 'ASC') $order_ = 'DESC';
                        else $order_ = 'ASC';
                        $href = site_url("admin/giftcode/viewall/id/$order/$code/$type/$sender/$status/$create/$use/$expire/$limit/$start");
                        echo "<a href='" . $href . "'>ID</a>";
                    ?>
                </th>
                <th width="80px">Code</th>
                <th width="100px">Type</th>
                <th width="100px">Value</th>
                <th width="100px">Sender</th>
                <th width="80px">Receiver</th>
                <th width="80px">Status</th>
                <th width="80px">Create</th>
                <th width="80px">Expire</th>
                <th width="80px">Used</th>
                <th>Reason</th>
                <th width="100px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if($giftcodes):?>
            <?php foreach($giftcodes as $giftcode):?>
            <tr id='hover'>
                <td><?=$giftcode->id?></td>
                <td><?=$giftcode->code?></td>
                <td><?=$giftcode->type?></td>
                <td><?=$giftcode->value?></td>
                <td>
                    <?php
                        if(!$giftcode->sender) {
                            echo $giftcode->sender;
                        } else {
                            $user = $CI->user_model->getUserById($giftcode->sender);
                            $username = $user->username;
                            $txt = site_url('admin/user/edit/'.$giftcode->sender);
                            echo "<a href='$txt' target='_blank'>$username</a>";
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if(!$giftcode->receiver) {
                            echo $giftcode->receiver;
                        } else {
                            $user = $CI->user_model->getUserById($giftcode->receiver);
                            $username = $user->username;
                            $txt = site_url('admin/user/edit/'.$giftcode->receiver);
                            echo "<a href='$txt' target='_blank'>$username</a>";
                        }
                    ?>
                </td>
                <td><?=$giftcode->status?></td>
                <td><?=date('d/m/Y', $giftcode->create_date)?></td>
                <td><?=date('d/m/Y', $giftcode->expire_date)?></td>
                <td><?=date('d/m/Y H:i:s', $giftcode->use_date)?></td>
                <td><?=$giftcode->reason?></td>
                <td>
                    <a href="<?=site_url('admin/giftcode/delete/'.$giftcode->id)?>" target="_blank">Delete</a>
                    >
            </tr>
            <?php endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
            <?php if(!$giftcodes):?>
                <center>Không tìm thấy dữ liệu</center>
            <?php endif;?>
    </div>
    </form>
<script type="text/javascript">
    function filter() {
        var url = '<?=site_url('admin/giftcode/viewall')?>' + '/<?php echo "$sort/$order/";?>' ;

        var giftcode = $('input[name=giftcode]').val();
        giftcode = (giftcode=='')?0:giftcode;
        url += encodeURIComponent(giftcode) + '/';

        var type = $('select[name=type]').val();
        type = (type==-1)?0:type;
        url += encodeURIComponent(type) + '/';

        var sender = $('input[name=sender]').val();
        sender = (sender=='')?-1:sender;
        url += encodeURIComponent(sender) + '/';
        
        var status = $('select[name=status]').val();
        url += encodeURIComponent(status) + '/';
        
        var create_date = $('#create_date').val();
        create_date = (create_date=='')?0:create_date;
        url += encodeURIComponent(create_date) + '/';
        
        var use_date = $('#use_date').val();
        use_date = (use_date=='')?0:use_date;
        url += encodeURIComponent(use_date) + '/';
        
        var expire_date = $('#expire_date').val();
        expire_date = (expire_date=='')?0:expire_date;
        url += encodeURIComponent(expire_date) + '/';
        
        var limit = $('select[name=limit]').val();
        url += encodeURIComponent(limit) + '/';
        //alert(url);
        window.location.href = url;
    }  
    
    $(function() {
        $( "#create_date" ).datepicker({ dateFormat: 'yymmdd' });
        $( "#use_date" ).datepicker({ dateFormat: 'yymmdd' });
        $( "#expire_date" ).datepicker({ dateFormat: 'yymmdd' });
    });
</script>				
</div>