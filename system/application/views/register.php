<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link href="<?=base_url()?>pics/homescreen.gif" rel="apple-touch-icon" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<?php 
        if (base_url() == "http://appstore.vn/e/") {
    ?>
        <link href="<?=base_url()?>css/style-e.css" media="screen" rel="stylesheet" type="text/css" />
    <?php 
        } else if(base_url() == "http://appstore.vn/a/") {
    ?>
        <link href="<?=base_url()?>css/style-a.css" media="screen" rel="stylesheet" type="text/css" />
    <?php    
        } else { 
        $link = base_url().'css/'.$this->session->userdata('style').'.css';
    ?>
        <link href="<?=$link?>" media="screen" rel="stylesheet" type="text/css" />
    <?php } ?>
<script src="<?=base_url()?>js/functions.js" type="text/javascript"></script>
<script src="<?=base_url()?>js/jquery.1.3.2.min.js" type="text/javascript"></script>
<title>appstore.vn</title>
<link href="<?=base_url()?>pics/startup.png" rel="apple-touch-startup-image" />
<meta content="" name="keywords" />
<meta content="" name="description" />
<script>
if(screen.width > "768"){
    //window.location.href="http://appstore.vn/intro.html";
}
</script>
<script>
    var canSubmit = true;    
    function checkUsername() {
        canSubmit = true;
        $('#name_check').hide();
        $('#name_error').hide();
        $('#name_message').hide();
        var username = $('input[name=username]').val();
        var filter = /^([a-zA-Z0-9_])+$/;
        if (!filter.test(username)) {
            $('#name_error').show();
            $('#name_message').text('Tên tài khoản chỉ gồm chữ và số');
            $('#name_message').show();
            canSubmit = false;return;
        }    
        if(username.length > 16) {
            $('#name_error').show();
            $('#name_message').text('Tên tài khoản nhỏ hơn 16 kí tự');
            $('#name_message').show();
            canSubmit = false;return;
        }
        $.ajax({
		url: "<?php echo site_url('user/checkExistsUsername') ?>/" + username,
		type: "POST",
		success: function(data) {
            if (data==0) {
                $('#name_check').show();
            } else {
                $('#name_error').show();
                $('#name_message').text('Tài khoản đã tồn tại');
                $('#name_message').show();
                canSubmit = false;
            }
		}
        });
    }

    function checkPassword() {
        $('#password_check').hide();
        $('#password_error').hide();
        $('#password_message').hide();
        var password = $('input[name=password]').val();
        if (password.length >= 6) {
            $('#password_check').show();
        } else {
            $('#password_error').show();
            $('#password_message').text('Mật khẩu quá ngắn (dưới 6 kí tự)');
            $('#password_message').show();
            canSubmit = false;return;
        }
    }

    function checkRetype() {
        $('#retype_check').hide();
        $('#retype_error').hide();
        $('#retype_message').hide();
        var password = $('input[name=password]').val();
        var retype = $('input[name=retype]').val();
        if (password==retype) {
            $('#retype_check').show();
        } else {
            $('#retype_error').show();
            $('#retype_message').text('Mật khẩu không chính xác');
            $('#retype_message').show();
            canSubmit = false;return;
        }
    }

    function checkLocation() {
        $('#location_check').hide();
        $('#location_error').hide();
        $('#location_message').hide();
        var city = $('select[name=city]').val();
        if (city!=0) {
            $('#location_check').show();
        } else {
            $('#location_error').show();
            canSubmit = false;return;
        }
    }
    
    function register() {
        checkUsername();
        checkPassword();
        checkRetype();
        checkLocation();
        if (typeof(canSubmit) == 'undefined') {
            canSubmit = true;
        }
        if(!canSubmit) {
			alert('Vui lòng kiểm tra lại thông tin');
			return;
        }        
        registerUser();
    }

    function registerUser() {
        checkUsername();
        checkPassword();
        checkRetype();
        checkLocation();
		var username = $('input[name=username]').val();
		var password = $('input[name=password]').val();
		var city = $('select[name=city]').val();

		$.ajax({
			url: "<?php echo site_url('user/registerUser')?>",
			type: "POST",
			data: "username=" + username + "&password=" + password + "&city=" + city,
			beforeSend: function() {
				$('#registerUser').attr('href','#');
				$('#registerUser').html('Vui lòng chờ');
			},
			success: function(data) {
                if(data != "-1") {
                    $('input[name=userId]').val(data);
                    $('#register-step1').hide();
                    $('#register-step2').show(10);
                    $('#reg-username').html(username);
                } else {
                    $('#registerUser').html('Tiếp tục');
                    $('#registerUser').attr('href', 'javascript:register();');
                }
			}
		});
    }
</script>

</head>
<body>
<div id="topholder">
  <div id="topbar">
    <div id="leftnav"> <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a> <a href="index.html">Back</a></div>
    <div id="title"> Đăng ký</div>
    <div id="usernav"><a  href="#"  id="login-btt"><img src="<?=base_url()?>images/button/user1.png"></a> <a href="<?=site_url('home/help')?>"><img src="<?=base_url()?>images/button/help1.png"></a></div>
  </div>
</div>
<?php
    $CI =& get_instance();
    $CI->load->model('setting_model');
    $partnersms = $CI->setting_model->globalSetting('partnersms');
    $smsxxx = str_replace('x', '1', $partnersms->value);
?>
<!-- bước 1 -->
<div id="register-step1">
<div id="content">
  <center style="color:#fff;margin:10px;-webkit-border-radius: 8px;background:-webkit-gradient(linear,0% 0%,0% 100%,from(rgba(133,133,133,0.5)),color-stop(3%,rgba(99,99,99,0.5)),color-stop(97%,rgba(0,0,0,0.5)),to(rgba(0,0,0,0)));font-size:16px;font-weight:bold;line-height:40px;">Bước 1/2: Nhập thông tin đăng ký</center>  
  <ul class="pageacc">
    <li class="smallfield"> <span class="name">Tên đăng nhập*:</span>
      <input placeholder="Nhập tên" type="text" onblur="checkUsername();" name="username"/>
      <abbr class="name_error" id="name_message" style="display: none;">Tài khoản đã tồn tại</abbr>
      <span class="error" id="name_error" style="display: none;"></span>
      <span class="check" id="name_check" style="display: none;"></span>
    </li>

    <li class="smallfield"> <span class="name">Mật khẩu*:</span>
      <input placeholder="" type="password" onblur="checkPassword();" name="password"/>
      <abbr class="name_error" id="password_message" style="display: none;">Mật khẩu quá ngắn (dưới 6 kí tự)</abbr>
      <span class="error" id="password_error" style="display: none;"></span>
      <span class="check" id="password_check" style="display: none;"></span>
    </li>
    <li class="smallfield"> <span class="name">Gõ lại mật khẩu*:</span>
      <input placeholder="" type="password" onblur="checkRetype();" name="retype" />
      <abbr class="name_error" id="retype_message" style="display: none;">Mật khẩu không chính xác</abbr>
      <span class="error" id="retype_error" style="display: none;"></span>
      <span class="check" id="retype_check" style="display: none;"></span>
    </li>
    <li class="smallfield"> <span class="name">Địa điểm*:</span>
      <select class="txt" name="city" onblur="checkLocation();">
        <option value="0">Lựa chọn</option>
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
		<option value="Other">Other</option>
      </select>
      <abbr class="name_error" id="location_message" style="display: none;">Địa điểm không chính xác</abbr>
      <span class="error" id="location_error" style="display: none;"></span>
      <span class="check" id="location_check" style="display: none;"></span>
    </li>
  </ul>
</div>
  <div class="buttonPanel bxPanel"> 
      <a class="white button w50" href="javascript:register();" id="registerUser">Tiếp tục</a> 
      <a href="<?php echo site_url('home')?>" class="white button w50">Hủy bỏ</a> 
  </div>
</div>
<!-- 
/bước 1
bước 2
 -->
 <div id="register-step2">
	<div id="content">
        <div class="step">
            <ul>
                <li><span>Bước 2/2: Kích hoạt tài khoản</span></li>
            </ul>
        </div>

        <div id="content">
            <div class="activation">
            <div class="titleinfo"><span>Cách 1: Kích hoạt bằng tin nhắn</span></div>
            <div class="ac-sms">
                <p class="des_comm"><span class="send_comm" style="width:55px;">Soạn tin</span> <span class="name_comm">APP KH <b id="reg-username"></b></p>
                <p class="des_comm"><span class="send_comm" style="width:55px;">Gửi tới</span> <span class="name_comm"><a href="sms:<?=$smsxxx?>"><?=$smsxxx?></a></span> <span class="send_comm">(Phí 1000 VNĐ)</span></p>
            </div>
            <div class="titleinfo"><span>Cách 2: Kích hoạt bằng email</span></div>
            <div class="ac-email">

            <input placeholder="Nhập email của bạn..." name="emailactive" type="text"  /><a href="javascript:sendEmailActive();">Kích hoạt</a>
            <input name="userId" type="hidden"  />
            </div>
            </div>
        </div>    
 	</div>
</div>
<style>
#sms-input{width:120px;border: 1px solid black;text-align:center;}
#register-step1{}
#register-step2{display:none;margin:0 auto;width:100%;float:none;color:white;text-align:left;font-weight:bold;}
#register-step2 .activeButton{margin:5px auto;height:50px;width:200px;font-size:16px;}
.email-input{width:240px;}
</style>
<script>
//var email_check = 0;
function checkEmail() {
    var email = $('input[name=email]').val();
    $.ajax({
        url: "<?php echo site_url('user/checkExistsEmail') ?>",
        type: "POST",
        data: "email=" + email,
        success: function(data) {
            if (data==0) {
                email_check = 1;
            } else {
                alert('Email đã tồn tại rồi !!!');
            }
        }
    });
}

function sendEmailActive() {

    var userid = $('input[name=userId]').val();
    var email = $('input[name=emailactive]').val();
    
    if(email.length == 0) {
    	alert('Vui lòng nhập email');
    	return;
    }
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (!filter.test(email)) {
		alert('Địa chỉ email bạn nhập không đúng');
		return;
	}
    
    $.ajax({
        url: "<?php echo site_url('user/checkExistsEmail') ?>",
        type: "POST",
        data: "email=" + email,
        success: function(data) {
            if (data==0) {
                //email_check = 1;
                $.ajax({
                    url: "<?php echo site_url('user/sendEmailActive') ?>",
                    type: "POST",
                    data: "email=" + email + "&userid=" + userid,
                    beforeSend: function() {
                        $('input[name=emailactive]').val('đang gửi thông tin kích hoạt...');
                    },
                    success: function(data) {
                        $('input[name=emailactive]').val('');
                        alert('Bạn đã kích hoạt thành công');
                        setTimeout("window.location.href='<?php echo base_url(); ?>';", 1000);
                    }
                });
            } else {
                alert('Email đã tồn tại rồi !!!');
            }
        }
    });
    
}
</script>
</body>
</html>
