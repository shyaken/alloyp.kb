<script type="text/javascript">
    <?php 
        if($this->session->userdata('logged_in')) $href = 'lol';
        else $href = '#';
    ?>
    var href_login = '<?=$href?>';
    if(href_login != '#') {
        $('#loginroi').show();
        $('#chualogin').hide();
    } else {
        $('#loginroi').hide();
        $('#chualogin').show();
    }
    $(document).ready(function(){
        //Examples of how to assign the ColorBox event to elements

        $("#login-btt").colorbox({
            inline:true, 
            width:"100%",
            href:"#loginbox",
            height: "100%",
            transition:"fade",
            onOpen:function(){
                var href_login = $('.buttonSystem a').eq(1).attr('href');
                if(href_login != '#') {
                    window.location.href='<?=site_url('home/account')?>';
                }
            }
        });
        $("#login-btt2").colorbox({
            inline:true, 
            width:"100%",
            height: "100%",
            href:"#loginbox",
            transition:"fade",
            onOpen:function(){
                var href_login = $('.buttonSystem a').eq(1).attr('href');
                if(href_login != '#') {
                    window.location.href='<?=site_url('home/account')?>';
                }
            }
        });
        $("#forgot-btt").colorbox({inline:true, width:"100%",height: "100%",href:"#forgotbox",transition:"fade"});

    });

	function requestPassword() {
		var username = $('input[name=username_forget]').val();
        var email = $('input[name=email_forget]').val();
        $('#resetbutton').html('<b>Đang yêu cầu...</b>');
        $.ajax({
		url: "<?php echo site_url('user/resetPassword') ?>",
		data: "username=" + username + "&email=" + email,
		type: "POST",
        beforeSend: function() {
            $('#resetbutton').attr('href', 'javascript:void(0);')
        },
		success: function(data) {
            $('#resetbutton').html('<b>Gửi lại mật khẩu</b>');
            if (data=='fail') {
                alert('Tên đăng nhập hoặc email không chính xác!');
                $('#resetbutton').attr('href', 'javascript:requestPassword();');
            } else {
                //$('#info-system').html(data);
                alert('Vui lòng kiểm tra email để nhận mật khẩu mới! Tin nhắn có thể vào hộp thư Rác!');
                $('#resetbutton').attr('href', 'javascript:requestPassword();');
                $.colorbox.close();
            }
		}
		});
    }

    function login(x,y,appId) {
        var username = $('input[name=username'+x+']').val();
        var password = $('input[name=password'+y+']').val();
        $('#loginbutton').html('<b>Đang đăng nhập...</b>');
        $.ajax({
		url: "<?php echo site_url('user/login') ?>",
		type: "POST",
        data: "username="+ username +"&password="+password,
		success: function(data) {
            $('#loginbutton').html('<b>Đăng nhập</b>');
            if (data=='fail') {
                alert('Tên đăng nhập hoặc mật khẩu sai');
            } else if (data=='inactive') {
                alert('Tài khoản của bạn chưa kích hoạt');
            }else {
                <?php 
                    if(isset($this->uri->segments[2])) {
                        $curFunc = $this->uri->segments[2];
                    } else {
                        $curFunc = "";
                    }
                ?>
                var curFunction = "<?=$curFunc?>";
                if(curFunction == "app") {
                    //ajax gan userTym, moreTym o app_view
                    $.ajax({
                        url: "<?=site_url('home/getTymData')?>",
                        success: function(response) {
                            var ketquane = response.split("@");
                            var userTymStr = ketquane[0];
                            var moreTymStr = ketquane[1];
                            var userTymArr = userTymStr.split("_");
                            for(var i=1; i<=4; i++) userTym['t'+i] = parseInt(userTymArr[i-1]);
                            var moreTymArr = moreTymStr.split("_");
                            for(var i=1; i<=4; i++) moreTym['t'+i] = parseInt(moreTymArr[i-1]);
                        }
                    });
                }
                $('#info-system').html(data);
                //thay icon user
                $('#loginroi').show();
                $('#loginroi').css('display','');
                $('#chualogin').hide();
                if(x == 2) footerLogin();
                if(x == 1) headerLogin();
                if(x == 3) {
                    middleLogin();
                    download(appId);
                }
                if(x == 4) {
                    middleLogin1(); //comment box
                    $('#commentbox').show();
                }
                if(x==5) {
                    eventLogin();
                    $('.join').show(); //hien tab choi
                }
                logged = 1;	// đã login
                <?php if(isset($package_expired)): ?>
                package_expired = <?=$package_expired?>;
                <?php endif ?>
                $.colorbox.close();
                // header_main VDEC xo so
                checkVDEC();
                alert('Bạn đã đăng nhập thành công!');
            }
		}
	});
    }
    
    var countLogin = 0;
    function footerLogin() {
        if(countLogin%2 == 0) {
            $('#mypanel2').show();
            windowHeight = document.documentElement.scrollHeight || document.body.scrollHeight;
            setTimeout('window.scrollTo(0, windowHeight);', 100);
        } else {
            $('#mypanel2').hide();
        }
        countLogin++;
        var href_login = $('.buttonSystem a').eq(1).attr('href');
        if(href_login != '#') {
            $('#mypanel2').hide();
        }
    }
    
    var countLogin1 = 0;
    function headerLogin() {
        if(countLogin1%2 == 0) {
            $('#mypanel').show();
            setTimeout('window.scrollTo(0, 0);', 100);
        } else {
            $('#mypanel').hide();
        }
        countLogin1++;
        var href_login = $('.buttonSystem a').eq(1).attr('href');
        if(href_login != '#') {
            $('#mypanel').hide();
        }
    }
    
    var countLogin2 = 0;
    function middleLogin() {
        if(countLogin2%2 == 0) {
            $('#download-login').show();
            //setTimeout('window.scrollTo(0, 0);', 100);
        } else {
            $('#download-login').hide();
        }
        countLogin2++;
        var href_login = $('.buttonSystem a').eq(1).attr('href');
        if(href_login != '#') {
            $('#download-login').hide();
        }
    }
    
    var countLogin3 = 0;
    function middleLogin1() {
        if(countLogin3%2 == 0) {
            $('#comment-login').show();
            //setTimeout('window.scrollTo(0, 0);', 100);
        } else {
            $('#comment-login').hide();
        }
        countLogin3++;
        var href_login = $('.buttonSystem a').eq(1).attr('href');
        if(href_login != '#') {
            $('#comment-login').hide();
        }
    }
    
    var countLogin4 = 0;
    function eventLogin() {
        if(countLogin4%2 == 0) {
            $('#event-login').show();
            //setTimeout('window.scrollTo(0, 0);', 100);
        } else {
            $('#event-login').hide();
        }
        countLogin4++;
        var href_login = $('.buttonSystem a').eq(1).attr('href');
        if(href_login != '#') {
            $('#event-login').hide();
        }
    }
</script>
<?php if (isset($message)) echo $message;?>
<?php if ($logged==0) { ?>
<div class="buttonSystem bxPanel"> 
<a href="javascript:footerLogin();" class="white button w30"><img src="<?=base_url()?>images/button/dangnhap.png"/><span>Đăng nhập</span></a> 
<a href="#" class="white button w30" onclick="window.location.href='<?=site_url('user/register')?>';"><img src="<?=base_url()?>images/button/quanlycanhan.png"/><span>Đăng ký</span></a> 
<a href="<?=site_url('home/event')?>" class="white button w30"><img src="<?=base_url()?>images/button/hopqua.png"/><span>Hộp quà</span></a>  
<a href="#" class="white button w30" id="forgot-btt"><img src="<?=base_url()?>images/button/quenmatkhau.png"/><span>Quên MK</span></a> 
<a href="<?=site_url('home/help')?>" class="white button w30"><img src="<?=base_url()?>images/button/trogiup.png"/><span>Trợ giúp</span></a>
 <a href="mailto:hotro@gsm.vn?subject=T%C3%B4i%20c%E1%BA%A7n%20h%E1%BB%97%20tr%E1%BB%A3%20!%20Username%3A&body=Ch%C3%A0o%20AppStoreVn%20!%0A%0AUsername%20c%E1%BB%A7a%20t%C3%B4i%20l%C3%A0%3A%0AT%C3%B4i%20c%E1%BA%A7n%20h%E1%BB%97%20tr%E1%BB%A3%20v%E1%BB%81%20vi%E1%BB%87c" class="white button w30"><img src="<?=base_url()?>images/button/lienhe.png"/><span>Liên hệ</span></a>  
</div>
<div id="acc-system">

<!--Popup-->
<div style='display:none'> 
<!-- Login box -->
<div id='loginbox'>
    <div class="dialog login">
      <div class="title-bx">
        <h1>Đăng nhập</h1>
      </div>
      <div class="content-bx">

        <ul class="formDialog">
          <li class="smallfield"> <span class="name">Tài khoản:</span>
            <input placeholder="" type="text" name="username0" />
          </li>
          <li class="smallfield"> <span class="name">Mật khẩu:</span>
            <input placeholder="" type="password" name="password0"  />
          </li>

        </ul>
        <div class="buttonPanel"> <a href="javascript:login(0,0);" class="white button w50" id="loginbutton" >Đăng nhập</a> <a href="<?=site_url('user/register');?>" class="white button w50">Đăng ký</a> </div>
      </div>
    </div>
</div>
  <!--Quen mat khau-->
<div id='forgotbox'>
    <div class="dialog forgot-acc">
          <div class="title-bx">
        <h1>Quên mật khẩu</h1>
      </div>
          <div class="content-bx">
        <ul class="formDialog">
              <li class="smallfield"> <span class="name">Tài khoản:</span>
            <input placeholder="" type="text" />
          </li>
              <li class="smallfield"> <span class="name">Email:</span>
            <input placeholder="" type="text"  />
          </li>
            </ul>
        <div class="buttonPanel"> <a href="#" class="white button w50">Gửi lại</a> <a href="#" class="white button w50">Bỏ qua</a> </div>
      </div>
        </div>
  </div>
</div>
<!--End Popup-->
<?php } ?>
<?php
//log search
//khanhpt - 29-12-2011
        if (isset($user->gender) && strpos($_SERVER['REQUEST_URI'], 'searchApp')) {
            $content = $user->gender . "|\n";
            file_put_contents('search.log', $content, FILE_APPEND);
        } elseif (strpos($_SERVER['REQUEST_URI'], 'searchApp')) {
            $content = "|\n";
            file_put_contents('search.log', $content, FILE_APPEND);
        }
?>
<?php if ($logged!=0) { ?>
<script>
    function logout() {
        $('#logout-btt').html('<b>Đang đăng xuất...</b>');
        $.ajax({
		url: "<?php echo site_url('user/logout') ?>",
		type: "POST",
		success: function(data) {
            $('#loginroi').hide();
            $('#chualogin').show();
			$('#info-system').html(data);
			logged = 0;
            var curFunc = '<?=$curFunc?>';
            if(curFunc == 'account' || curFunc == 'tym' || curFunc == 'infouser') {
                window.location.href = '<?=base_url()?>';
            }
        }   
        });
    }
</script>
<div class="buttonSystem bxPanel"> 
<a href="<?=site_url('home/infouser')?>" class="white button w30">
    <?php 
        $gender = 'quanlycanhan.png';
        if(isset($user->gender)) {if($user->gender == 0) $gender = 'woman.png';}
    ?>
    <img src="<?=base_url()?>images/button/<?=$gender?>"/>
    <?php
        if(strlen($username)>=11) {
    ?>
        <span>
        <marquee behavior="scroll" scrollamount="2"><?=$username?></marquee>
        </span>
    <?php } else { ?>
        <span><?=$username?></span>
    <?php } ?>
</a>
<a href="<?=site_url('home/event')?>" class="white button w30"><img src="<?=base_url()?>images/button/hopqua.png"/><span>Hộp quà</span></a> 
<a href="<?=site_url('home/account')?>" class="white button w30"><img src="<?=base_url()?>images/button/quanlytaikhoan.png"/><span>Tài khoản</span></a> 
<a href="<?=site_url('home/transaction')?>" class="white button w30"><img src="<?=base_url()?>images/button/quanlygiaodich.png"/><span>Giao dịch</span></a> 
<a href="<?=site_url('home/help')?>" class="white button w30" ><img src="<?=base_url()?>images/button/trogiup.png"/><span>Trợ giúp</span></a> 
<a href="javascript:logout();" class="white button w30" ><img src="<?=base_url()?>images/button/dangxuat.png"/><span>Đăng xuất</span></a> 
</div>
<?php } ?>
