<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Admin Control Panel</title>
    <link rel="stylesheet" href="<?=base_url()?>css/960.css" type="text/css" media="screen" charset="utf-8" />
    <link rel="stylesheet" href="<?=base_url()?>css/template.css" type="text/css" media="screen" charset="utf-8" />
    <link rel="stylesheet" href="<?=base_url()?>css/colour.css" type="text/css" media="screen" charset="utf-8" />
    <link rel="stylesheet" href="<?=base_url()?>style/admin/admin.css" type="text/css" media="screen" charset="utf-8" />
    <!--[if IE]><![if gte IE 6]><![endif]-->
    <script src="<?=base_url()?>js/jquery.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/glow/1.7.0/core/core.js" type="text/javascript"></script>
    <script src="<?=base_url()?>js/glow/1.7.0/widgets/widgets.js" type="text/javascript"></script>
    <link href="<?=base_url()?>js/glow/1.7.0/widgets/widgets.css" type="text/css" rel="stylesheet" />
    <!--[if IE]><![endif]><![endif]-->
</head>
<body>
    <style>
        #head a{text-decoration:none;color:green;font-size:14px;}
        #head a:hover{color:lime;}
        #head span{font-size:14px;}
    </style>
    <h1 id="head">
        Appstore AdminCP 
        <span>|
        <a href="http://appstore.vn/i/index.php/admin/managerapp">iOS</a> |
        <a href="http://appstore.vn/a/index.php/admin/managerapp">A</a> |
        <a href="http://appstore.vn/b/index.php/admin/managerapp">B</a> |
        <a href="http://appstore.vn/c/index.php/admin/managerapp">C</a> |
        <a href="http://appstore.vn/e/index.php/admin/managerapp">E</a> |
        <a href="http://appstore.vn/f/index.php/admin/managerapp">F</a> |
        <a href="http://appstore.vn/j/index.php/admin/managerapp">J</a> |    
        <a href="http://appstore.vn/m/index.php/admin/managerapp">M</a> |
        <a href="javascript:showChangePassForm();" style="color:red;">Đổi mật khẩu</a> |
        <a href="<?php echo site_url('admin/login/logout')?>">Thoát</a> |
        </span>
    </h1>
    <?php $active = $this->uri->segment(2);?>
    <ul id="navigation">
        <!--<li><span <?php if ($active=='dashboard') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/dashboard')?>">Dashboard</a></span></li>-->
        <li><span class="active"><a href="<?php echo site_url('admin/dashboard/flushCache')?>">Xóa cache</a></span></li>
        <li><span <?php if ($active=='managerapp') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/managerapp')?>">Ứng dụng</a></span></li>
        <li><span <?php if ($active=='category') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/category')?>">Chuyên mục</a></span></li>
        <li><span <?php if ($active=='usertransaction') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/usertransaction')?>">Giao dịch</a></span></li>
        <li><span <?php if ($active=='ad') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/ad')?>">Quảng cáo</a></span></li>
        <li><span <?php if ($active=='logo') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/logo')?>">Logo</a></span></li>
        <li><span <?php if ($active=='statistic') echo 'class="active"'; ?>><a href="<?php echo site_url('admin/statistic')?>">Thống kê tải</a></span></li>
        <li><span <?php if ($active=='textad') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/textad')?>">Text Ads</a></span></li>
        <li><span <?php if ($active=='online') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/online')?>">Online</a></span></li>
        <li><span <?php if ($active=='packagelog') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/packagelog')?>">Thống kê gói</a></span></li>
        <li><span <?php if ($active=='exchangelog') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/exchangelog')?>">Thống kê quy đổi</a></span></li>
        <li><span <?php if ($active=='report') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/report')?>">Report by user</a></span></li>
        <li><span <?php if ($active=='user') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/user')?>">Users</a></span></li>
        <li><span <?php if ($active=='actionlog') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/actionlog')?>">Thống kê cộng Tym</a></span></li>
        <li><span <?php if ($active=='promotion') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/promotion')?>">Khuyến mãi</a></span></li>
        <li><span <?php if ($active=='event') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/event')?>">Sự kiện</a></span></li>
        <?php if($this->session->userdata('is_root') == 'yes'):?>
        <li><span <?php if ($active=='actionreward') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/actionreward')?>">Hành động cộng Tym</a></span></li>
        <li><span <?php if ($active=='textnote') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/textnote')?>">Thông báo text</a></span></li>        
        <li><span <?php if ($active=='manageradmin') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/manageradmin')?>">Admins</a></span></li>
        <li><span <?php if ($active=='setting') echo 'class="active"'; ?> ><a href="<?php echo site_url('admin/setting')?>">Quản lý Giá</a></span></li>
        <?php endif;?>
    </ul>
    <div id="changepassword" style="text-align:center;display:none;">
        <form name="changepasswordform">
            <font color="green">Mật khẩu hiện tại: </font><input type="password" name="curpass" style="width:120px;" />
            <font color="red">Mật khẩu mới: </font><input type="password" name="newpass" style="width:120px;" />
            <font color="red">Gõ lại mật khẩu mới: </font><input type="password" name="renewpass" style="width:120px;" />
            <input type="button" value="Đổi pass ngay" id="submitpass" style="color:green;" onclick="changePass();" />
            <input type="button" value="Ẩn đi" style="color:green;" onclick="hideChangePassForm();" />
        </form>
    </div>
    <script>
    function showChangePassForm() {
        $('#changepassword').slideToggle('slow');
    }
    function hideChangePassForm() {
        $('#changepassword').slideToggle('slow');
    }
    function changePass() {
        var curPass = $('input[name=curpass]').val();
        var newPass = $('input[name=newpass]').val();
        var renewPass = $('input[name=renewpass]').val();
        if(newPass != renewPass) {
            alert('Mật khẩu mới không trùng nhau!!!');return;
        }
        if(newPass.length < 10) {
            alert('Mật khẩu mới quá ngắn!!! phải ít nhất 10 kí tự');return;
        }
        
        $.ajax({
           url: "<?php echo site_url('admin/dashboard/changePassword') ?>",
           data: "curPass=" + curPass + "&newPass=" + newPass + "&renewPass=" + renewPass,
           type: "POST",
           beforeSend: function() {
               $('#submitpass').val('Chờ lát nhá!!!');
               $('#submitpass').css('color','red');
           },
           success: function(data) {
               if(data==1) {
                   alert('Thay đổi mật khẩu thành công');
                   $('input[name=curpass]').val('');
                   $('input[name=newpass]').val('');
                   $('input[name=renewpass]').val('');
                   $('#changepassword').hide('slow');
               } else {
                   alert('Mật khẩu cũ sai rồi nhá!!!');
               }
               $('#submitpass').val('Đổi pass ngay!!!');
               $('#submitpass').css('color','green');               
           }
        });
    }
    </script>