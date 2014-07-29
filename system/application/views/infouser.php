<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link href="pics/homescreen.gif" rel="apple-touch-icon" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<?php 
        if (base_url() == "http://appstore.vn/e/") {
    ?>
        <link href="<?=base_url()?>css/style-e.css" media="screen" rel="stylesheet" type="text/css" />
    <?php } else if(base_url() == "http://appstore.vn/a/") {
    ?>
        <link href="<?=base_url()?>css/style-a.css" media="screen" rel="stylesheet" type="text/css" />
    <?php    
        } else { 
        $one = $this->session->userdata('style');
        if($one == 'style') {
    ?>
     <link href="<?=base_url()?>css/style.css" media="screen" rel="stylesheet" type="text/css" id="stylex" />
         <?php    
        } else {
    ?>
     <link href="<?=base_url()?>css/style-w.css" media="screen" rel="stylesheet" type="text/css" id="stylex" />
         <?php }} ?>
<script src="<?php echo base_url()?>js/functions.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>js/jquery.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
<link href="<?=base_url()?>js/colorbox/css/colorbox.css" media="screen" rel="stylesheet" type="text/css" />
<title>AppStore.vn</title>
<link href="<?php echo base_url()?>pics/startup.png" rel="apple-touch-startup-image" />
<meta content="" name="keywords" />
<meta content="" name="description" />
<script>
if(screen.width > "768"){
 //window.location.href="http://appstore.vn/intro.html";
}
</script>
<script>
	var current_user = "<?=$user->username?>";
	var current_email = "<?=$user->email?>";
	var canSubmit = true;
    var birthday_help = 0;
    
    function birthdayHelp() {
        if(birthday_help > 0) return;
        alert('Định dạng yyyy-mm-dd');
        birthday_help++;
    }
    
    function changeStyle(style) {
        $('#stylex').attr('href', '<?=base_url()?>css/' + style + '.css'); 
        $.ajax({
            url: "<?=site_url('user/changeStyle')?>",
            data: "style=" + style,
            type: "POST",
            beforeSend: function() {
                $('#saveconfig').hide();
            },
            success: function() {
                $('#saveconfig').show();
            }
        });
    }
    
    function changeGender() {
        var gender = $('select[name=gender]').val();
        var style = 'style-w';
        if(gender == 1) style = 'style';
        changeStyle(style);
    }

    function checkUsername() {
        $('#name_message').hide();
        var username = $('input[name=username]').val();
        if(current_user == username) { return; }
        $.ajax({
			url: "<?php echo site_url('user/checkExistsUsername') ?>/" + username,
			type: "POST",
			success: function(data) {
	            if (data!=0) {
		            canSubmit = false;
	                $('#name_message').text('Tài khoản đã tồn tại');
	                $('#name_message').show();
	                return;
	            }
			}
     	});
    }

    function checkPassword() {
        $('#password_message').hide();
        var password = $('input[name=password]').val();
        <?php if(!$logged):?>
        if (password.length < 6) {
            canSubmit = false;
            $('#password_message').text('Mật khẩu ít nhất 6 kí tự!');
            $('#password_message').show();
            return;
        } 
        <?php endif;?>
        <?php if($logged):?>
        if (password != "" && password.length < 6) {
            canSubmit = false;
            $('#password_message').text('Mật khẩu ít nhất 6 kí tự!');
            $('#password_message').show();
            return;
        } 
        <?php endif;?>
    }
    function checkLocation() {
        $('#city_message').hide();
        var city = $('select[name=city]').val();
        if (city==0) {
            canSubmit = false;
            $('#city_message').text('Vui lòng chọn địa chỉ!');
            $('#city_message').show();
            return;
        }
    }

    function checkEmail() {
        if($('select[name=active_by]').val() != 'email'){
            return true;
        }
		var email = $('input[name=email]').val();
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (!filter.test(email)) {
			$('#email_message').text('Vui lòng nhập đúng địa chỉ email');
			$('#email_message').show();
			canSubmit = false;
			return;
		} else {
			//ajax check email
			if(email == current_email) { return; }
				$('#email_message').hide();
			$.ajax({
				url: "<?php echo site_url('user/checkExistsEmail') ?>",
				data: "email=" + email,
				type: "POST",
				success: function(data) {
		            if (data!=0) {
		            	$('#email_message').text('Email đã tồn tại rồi nha!');
			            $('#email_message').show();
		                canSubmit = false;
		                return;
		            }
				}
	     	});
		}
 	}

    function activate() {
        canSubmit = true;
        checkUsername();
        checkPassword();
        checkLocation();
        <?php if(!$logged):?>checkEmail();<?php endif;?>
        <?php if($logged):?>
        if($('input[name=fullname]').val() == 'Chưa khai báo') {
       	 	$('input[name=fullname]').val('0');
        }
        if($('input[name=chucvu]').val() == 'Chưa khai báo') {
       	 	$('input[name=chucvu]').val('0');
        }
        if($('input[name=cmnd]').val() == 'Chưa khai báo') {
        	$('input[name=cmnd]').val('0');
        }
        if($('input[name=birthday]').val() == 'Chưa khai báo') {
        	$('input[name=birthday]').val('0000-00-00');
        }
        <?php endif;?>
        if(!canSubmit) {
			alert('Vui lòng kiểm tra lại thông tin');
			return;
        }        
        $('#infouser').submit();
    }

    function showEmailForm() {
		if($('select[name=active_by]').val() == 'email') $('#email-input').show('slow');
		else $('#email-input').hide('slow');
    }
    
    function cancelSave() {
        window.location.href='<?php echo base_url();?>';
    }
</script>
</head>

<body>

<?php if(isset($sms_selected)):?>
<script>
$(document).ready(function(){
	setTimeout('$.fn.colorbox({width:"100%", inline:true, href:"#active_sms"});',100);
});
</script>
<style>
#active_sms{width:100%;float:none;margin:0 auto;text-align:center;color:red;font-size:12px;}
.input-txt{color:green;width:140px;background:silver;text-align:center;}
</style>
<div style="display:none;">
	<div id="active_sms">
		Soạn tin 
		<input type="text" value="APP KH <?=$user->username?>" class="input-txt" onfocus="this.select();" /> 
		gửi tới <?=str_replace('x', 1, $smsResult)?> để kích hoạt
	</div>
</div>
<?php endif; ?>

<div id="topholder">
  <div id="topbar">
    <div id="leftnav"> <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a><a href="<?=base_url()?>">Back</a></div>
    <?php 
        if($this->session->userdata('logged_in')) {
            $icontop='user-ac.png';
            $topUrl = site_url('home/account');
        } else {
            $icontop='user.png';
            $topUrl = 'javascript:footerLogin();';
        }
    ?>
    <div id="usernav">
        <a  href="<?=$topUrl?>"  id="login-btt-dis">
            <img src="<?=base_url()?>images/button/<?=$icontop?>">
        </a> 
        <a href="<?=site_url('home/help')?>">
            <img src="<?=base_url()?>images/button/help1.png">
        </a>
    </div>
  </div>
</div>

<form action="<?php echo site_url('home/infouser') ?>" method="post" id="infouser" name="infouser">
<div id="content">
<?php if(isset($success)):?>
<span class="graytitle" id="msg-alert" style="color:green;text-align:center;width:100%;"><?=$success?><br /></span>
<?php endif;?>
<?php if(isset($email_sent)):?>
<span class="graytitle" id="msg-alert" style="color:green;text-align:center;width:100%;"><?=$email_sent?><br /></span>
<?php endif;?>
<script>
$(document).ready(function(){
	var timeout = setTimeout("$('#msg-alert').hide('slow')", 3000);
});
</script>
<span class="graytitle">Thông tin cơ bản</span>
	<ul class="pageacc">
		<li class="smallfield">
        <span class="name">Tên đăng nhập:</span>
        <input value="<?=$user->username?>" type="text" name="username" onblur="checkUsername();" <?php if($logged) echo 'disabled="disabled"';?> />  
        <abbr class="name_error" id="name_message" style="display: none;">Tài khoản đã tồn tại</abbr>
        <span class="nums <?php if(!$logged) echo ' current'?>">+10 ♥</span>
        </li>
	<li class="smallfield">
        <span class="name">Mật khẩu mới:</span><input type="password" name="password" onblur="checkPassword();" />
         <abbr class="name_error" id="password_message" style="display: none;">Mật khẩu quá ngắn (dưới 6 kí tự)</abbr>
         <!-- <span class="nums current">+10 ♥</span> -->
        </li>
    <?php if($logged) { ?>
    	<li class="smallfield">
        <span class="name">Trạng thái:</span>
            <?php if ($user->active_by=='inactive') {
                $activeStatus = 'Chưa kích hoạt';
            } else {
                $activeStatus = 'Đã kích hoạt';
            }
            ?>
        	<input type="text" disabled="disabled" value="<?=$activeStatus?>" />
        </li>
    <?php } else { ?>
    	<li class="smallfield">
        <span class="name">Đã kích hoạt:</span>
        	<select name="active_by" onChange="showEmailForm();">
        	<option value="sms">Kích hoạt bằng SMS</option>
        	<option value="email">Kích hoạt bằng email</option>
        	</select>
        <span class="nums current">+10 ♥</span>
        </li>
        <li class="smallfield" id="email-input" style="display:none">
        <span class="name">Email:</span>
        <input value="Nhập email ở đây" onclick="if(this.value=='Nhập email ở đây') this.value='';" onblur="checkEmail();" type="text" name="email" />
        <abbr class="name_error" id="email_message" style="display: none;">Email đã tồn tại</abbr>
        <span class="nums <?php if(!$user->email) echo ' current'?>">+10 ♥</span>  
        </li>
     <?php }?> 
        <li class="smallfield">
        <span class="name">Tỉnh thành:</span>
        <select class="txt" name="city" id="cityselect">
<option value="0">Chưa khai báo</option>
<option value="Hà Nội">Hà Nội</option>
<option value="TP Hồ Chí Minh">TP Hồ Chí Minh</option>
<option value="Thừa thiên Huế">Thừa thiên Huế</option>
<option value="Đà Nẵng">Đà Nẵng</option>
<option value="An Giang">An Giang</option>
<option value="Bà Rịa Vũng Tàu">Bà Rịa Vũng Tàu</option>
<option value="Bắc Kạn">Bắc Kạn</option>
<option value="Bắc Giang">Bắc Giang</option>
<option value="Bạc Liêu">Bạc Liêu</option>
<option value="Bắc Ninh">Bắc Ninh</option>
<option value="Bến Tre">Bến Tre</option>
<option value="Bình Định">Bình Định</option>
<option value="Bình Dương">Bình Dương</option>
<option value="Bình Phước">Bình Phước</option>
<option value="Bình Thuận">Bình Thuận</option>
<option value="Cà Mau">Cà Mau</option>
<option value="Cần Thơ">Cần Thơ</option>
<option value="Cao Bằng">Cao Bằng</option>
<option value="Đắc Nông">Đắc Nông</option>
<option value="Đắc Lắc">Đắc Lắc</option>
<option value="Điện Biên">Điện Biên</option>
<option value="Đồng Nai">Đồng Nai</option>
<option value="Đồng Tháp">Đồng Tháp</option>
<option value="Gia Lai">Gia Lai</option>
<option value="Hà Giang">Hà Giang</option>
<option value="Hà Nam">Hà Nam</option>
<option value="Hà Tây">Hà Tây</option>
<option value="Hà Tĩnh">Hà Tĩnh</option>
<option value="Hải Dương">Hải Dương</option>
<option value="Hải Phòng">Hải Phòng</option>
<option value="Hậu Giang">Hậu Giang</option>
<option value="Hoà Bình">Hoà Bình</option>
<option value="Hưng Yên">Hưng Yên</option>
<option value="Khánh Hoà">Khánh Hoà</option>
<option value="Kiên Giang">Kiên Giang</option>
<option value="Kon Tum">Kon Tum</option>
<option value="Lai Châu">Lai Châu</option>
<option value="Lâm Đồng">Lâm Đồng</option>
<option value="Lạng Sơn">Lạng Sơn</option>
<option value="Lào Cai">Lào Cai</option>
<option value="Long An">Long An</option>
<option value="Nam Định">Nam Định</option>
<option value="Nghệ An">Nghệ An</option>
<option value="Ninh Bình">Ninh Bình</option>
<option value="Ninh Thuận">Ninh Thuận</option>
<option value="Phú Thọ">Phú Thọ</option>
<option value="Phú Yên">Phú Yên</option>
<option value="Quảng Bình">Quảng Bình</option>
<option value="Quảng Nam">Quảng Nam</option>
<option value="Quảng Ngãi">Quảng Ngãi</option>
<option value="Quảng Ninh">Quảng Ninh</option>
<option value="Quảng Trị">Quảng Trị</option>
<option value="Sóc Trăng">Sóc Trăng</option>
<option value="Sơn La">Sơn La</option>
<option value="Tây Ninh">Tây Ninh</option>
<option value="Thái Bình">Thái Bình</option>
<option value="Thái Nguyên">Thái Nguyên</option>
<option value="Thanh Hoá">Thanh Hoá</option>
<option value="Tiền Giang">Tiền Giang</option>
<option value="Trà Vinh">Trà Vinh</option>
<option value="Tuyên Quang">Tuyên Quang</option>
<option value="Vĩnh Long">Vĩnh Long</option>
<option value="Vĩnh Phúc">Vĩnh Phúc</option>
<option value="Yên Bái">Yên Bái</option>
</select>
<script>
var cityselect = document.getElementById('cityselect');

for(var i=0; i < cityselect.options.length; i++){
	if(cityselect.options[i].value == '<?=$user->city?>') cityselect.options[i].selected='selected';
}
</script>		
	<abbr class="name_error" id="city_message" style="display: none;">Email đã tồn tại</abbr>			                        
    <span class="nums <?php if(!$user->city) echo ' current'?>">+10 ♥</span>
        </li>
	</ul>

<!-- Thông tin bổ sung - dành cho user đã xác nhận -->
<?php if($logged):?>	
    <span class="graytitle">Thông tin mở rộng</span>
    <ul class="pageacc">
		<li class="smallfield">
        <span class="name">Họ và tên:</span><input value="<?php if($user->fullname){echo $user->fullname;}else{echo 'Chưa khai báo';}?>" onclick="if(this.value=='Chưa khai báo') this.value='';" type="text" name="fullname" /> <span class="nums">+10 ♥</span>
        </li>
	<li class="smallfield">
        <span class="name">Ngày sinh:</span><input value="<?php if($user->birthday != '0000-00-00'){echo $user->birthday;}else{echo 'Chưa khai báo';}?>" onclick="if(this.value=='Chưa khai báo') this.value='';birthdayHelp();" type="text" name="birthday" />
          <span class="nums">+10 ♥</span>
        </li>
        <li class="smallfield">
        <span class="name">Giới tính:</span><select name="gender" onchange="changeGender();"><option value="1" <?php if($user->gender==1){echo 'selected="selected"';}?>>Nam</option><option value="0" <?php if($user->gender==0){echo 'selected="selected"';}?>>Nữ</option></select>  <span class="nums">+10 ♥</span>
        </li>
        <li class="smallfield">
        <span class="name">Công việc:</span><input value="<?php if($user->chucvu){echo $user->chucvu;}else{echo 'Chưa khai báo';}?>" onclick="if(this.value=='Chưa khai báo') this.value='';" type="text" name="chucvu" />  <span class="nums">+10 ♥</span>
        </li>
        <li class="smallfield">
        <?php
        if (strlen($user->cmnd)>4) {
            $cmnd = 'XXXX'.substr($user->cmnd, 4);
        }
        ?>
        <span class="name">Số CMND:</span><input value="<?php if($user->cmnd){echo $cmnd;}else{echo 'Chưa khai báo';}?>" onclick="if(this.value=='Chưa khai báo') this.value='';" type="text" name="cmnd" />  <span class="nums">+10 ♥</span>
        </li>
	</ul>
    <span class="graytitle">Cấu hình</span>
    <ul class="pageacc">
        <li class="smallfield"> 
            <span class="name">Giao diện:</span> 
            <a href="javascript:changeStyle('style');" class="styleswitch style-1" >Đen</a>
            - 
            <a href="javascript:changeStyle('style-w');" class="styleswitch style-2">Hồng</a>
        </li>
    </ul>
</div>
<?php endif;?>
<!-- /Thông tin bổ sung - dành cho user đã xác nhận -->
<div class="buttonPanel bxPanel" id="saveconfig"> 
    <a href="javascript:activate();" class="white button w50">Lưu cấu hình</a> 
    <a href="javascript:cancelSave();" class="white button w50">Hủy bỏ</a> 
</div>
</form>
</body>

</html>
