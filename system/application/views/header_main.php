<?php include_once('check_agent.php');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
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
	<link href="<?=base_url()?>pics/homescreen.gif" rel="apple-touch-icon" />
	<title>AppStore.vn</title>
    <link href="<?=base_url()?>/pics/startup.png" rel="apple-touch-startup-image" />
    <meta content="APPSTORE" name="keywords" />
    <meta content="<?php echo str_replace('"', '', $description); ?>" name="description" />
	<script type="text/javascript" src="<?=base_url()?>js/jquery.js"></script>
	<!--slide top-->
    <script src="<?=base_url()?>js/jappstore.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="<?=base_url()?>js/jquery.raty-1.4.0/js/jquery.raty.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/tabcontent.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/scroll/iscroll.js?v3.7.1"></script>
    <script type="text/javascript" src="<?=base_url()?>js/slide/jcarousellite_1.0.1.pack.js"></script>
    <script src="<?=base_url()?>js/countdown/jquery.jcountdown1.3.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function() {
    		$(".slidertop").jCarouselLite({
        		btnNext: ".next",
        		btnPrev: ".prev",
        		visible: 6
    		});
		});

	</script>
    <script type="text/javascript" charset="utf-8">
        window.onload = function() {
          setTimeout(function(){window.scrollTo(0, 1);}, 100);
        }
    </script>
</head>
<script language="javascript">
<!--
// test
var state = 'none';

function showhide(layer_ref) {

if (state == 'block') {
state = 'none';
}
else {
state = 'block';
}
if (document.getElementById &&!document.all) {
hza = document.getElementById(layer_ref);
hza.style.display = state;
}
}
//-->
</script>
<script type="text/javascript" charset="utf-8">
    //window.onload = function() {
    //  setTimeout(function(){window.scrollTo(0, 1);}, 100);
    //}
    function checkVDEC() {
        $.ajax({
            url: "<?=site_url('user/generateVDEC')?>",
            success: function(response) {
                if(response != '#') {
                    $('#linkxoso').attr('href',response);
                } else {
                    var confirm = window.confirm('Vui lòng đăng nhập để sử dụng chức năng này');
                    if(!confirm) return;
                    headerLogin();
                }
            }
        });
    }
    
    function topLogin() {
        var href_login = $('.buttonSystem a').first().attr('href');
        if(href_login != '#') {
            window.location.href = '<?=site_url('home/account')?>';
        } else return;
    }
</script>
<body>
<?php if($googlead) echo $googlead->code?>
<?php
$agent = $_SERVER['HTTP_USER_AGENT'];
$ios = 0;
$phim = 0;
$book = 0;
$music = 0;
$comic = 0;
$android = 0;
if (base_url() == "http://appstore.vn/a/") {
    $android = 1;
}
if (base_url() == "http://appstore.vn/i/") {
	$ios = 1;
}
if (base_url() == "http://appstore.vn/f/") {
	$phim = 1;
}
if (base_url() == "http://appstore.vn/e/") {
	$book = 1;
}
if (base_url() == "http://appstore.vn/m/") {
    $music = 1;
}
if(base_url() == "http://appstore.vn/c/") {
    $comic = 1;
}
if($this->session->userdata('logged_in')) {
    $userId = $this->session->userdata('userid');
    $username = $this->session->userdata('username');
    $partner_name = 'vdec';
    $secret_code = 'vdecabc@123';
    $time = time(true);
    $hash = md5($partner_name.$userId.$time.$userId.$username.$secret_code);
    $link = "http://xs.appstore.vn/?userid=$userId&username=$username&appstoreid=$userId&time=$time&hash=$hash";
} else {
    $link = 'javascript:checkVDEC();';	
}
?>
<div id="topholder">
      <div id="topbar" >
    <div class="slidernav">
          <div class="slidertop">
          
        <ul>
              <?php if(base_url() != "http://appstore.vn/a/") {?>
                <?php if (preg_match('@iPhone|iPod|iPad@', $agent)) { ?>
              <li> <?php if ($ios == 1): ?>id="pressed"<?php endif; ?> <a  href="http://appstore.vn/i"><img src="<?=base_url()?>images/logos/app-ios.png"></a> </li>
                  <?php } ?>
              <?php } ?>
              <li <?php if ($phim == 1): ?>id="pressed"<?php endif; ?>><a href="http://appstore.vn/f"><img src="<?=base_url()?>images/logos/film.png"></a> </li>
              <li <?php if ($book == 1): ?>id="pressed"<?php endif; ?>> <a href="http://appstore.vn/e"><img src="<?=base_url()?>images/logos/ebook.png" ></a></li>
              <li <?php if ($comic == 1): ?>id="pressed"<?php endif; ?>> <a href="http://appstore.vn/c"><img src="<?=base_url()?>images/logos/comic.png"></a></li>
              <li> <a href="<?=$link?>" id="linkxoso"><img src="<?=base_url()?>images/logos/lottery.png"></a> </li>
              <li> <a href="<?=site_url('home/event')?>"><img src="<?=base_url()?>images/logos/hopqua.png"></a> </li>
                <?php if (preg_match('@Android|android@', $agent)) { ?>
              <li <?php if ($android == 1): ?>id="pressed"<?php endif; ?>> <a href="http://appstore.vn/a"><img src="<?=base_url()?>images/logos/app-android.png"></a> </li>
              <?php } ?>
              <li <?php if ($music == 1): ?>id="pressed"<?php endif; ?>> <a href="http://appstore.vn/m"><img src="<?=base_url()?>images/logos/music.png"></a> </li>
              
        </ul>
            
      </div>
          <div class="next"><img src="<?=base_url()?>images/next.png" alt="next" /></div>
        </div>
  </div>
</div>
<div id="topheader" >
      <div id="header">
    <div id="logo"  style="background: url('<?=base_url().$logo->image?>') no-repeat 0; " ><a class="base" href="<?=base_url()?>" name="appstorevn"></a></div>
    <div id="userbuttons">
        <!--
        <span>
        <a  href="javascript:alert('Coming soon');" ><img src="<?=base_url()?>images/logos/hopqua.png"></a> 
        </span>
        -->
        <span id="loginroi" style="display:none;">
        <a  href="<?=site_url('home/account')?>">
            <img src="<?=base_url()?>images/button/user-ac.png">
        </a>
        </span>
        <span id="chualogin">
        <a  href="javascript:headerLogin();" id="mypaneltabsss">
            <img src="<?=base_url()?>images/button/user.png">
        </a> 
        </span>
        <span>
        <a href="<?=site_url('home/help')?>"><img src="<?=base_url()?>images/button/help.png"></a>
        </span>
    </div>
  </div>
</div>

<div id="mypanel" class="ddpanel" style="display:none;">
  <div id="mypanelcontent" class="ddpanelcontent">
    <div class="elements login">
      <div class="title-bx">
        <h1><a href="javascript:headerLogin();">Đăng nhập</a></h1>
      
      </div>
      <div class="content-bx">
        <ul class="formElements">
          <li class="smallfield"> <span class="name">Tên đăng nhập:</span>
            <input placeholder="" type="text" name="username1" />
          </li>
          <li class="smallfield"> <span class="name">Mật khẩu:</span>
            <input placeholder="" type="password" name="password1"  />
          </li>
        </ul>
        <div class="buttonPanel"> 
            <a href="javascript:login(1,1);" class="white button w50">
                Đăng nhập
            </a> 
            <a href="javascript:headerLogin();" class="white button w50">
                Hủy bỏ
            </a> 
        </div>
      </div>
    </div>
  </div>
</div>    
    
<div class="bannergroup">
	<div class="minibanner">
	
<?php 
	$used = array();
	$all = false;
	foreach($headerads as $ad):
	$used[] = $ad->start;
	$id = $ad->type . $ad->start . $ad->unit;
	$width = "147";
	$height = "67";
	if($ad->unit == 2) $width = "300";
	if($ad->type == "all") { $width = "300"; $height = "150"; $all = true; }
?>
	<div id="<?=$id?>">
		<?php 
			if($ad->code == '') {
		?>
		<a href="<?php echo site_url('home/advertise/' . $ad->id)?>" target="_blank">
		<img alt="" height="<?=$height?>" src="<?=base_url() . $ad->image?>" width="<?=$width?>" />
		</a>
		<?php 
			} else {
				echo $ad->code;
			}
		?>
	</div>		
<?php endforeach;?>
<?php 
    for($i=1; $i<5; $i++) {
        if(!in_array($i, $used)) {
            if(($i==3 || $i==1) && $all == false) {
                echo "<script>$('.bannergroup .minibanner').css('height','68px');
                      $('#ngang31').css('top','0px');
                      $('#ngang41').css('top','0px');
                      </script>";break;
            } else {
                echo '<div id="ngang' . $i . '1">';
                echo '<img src="' . base_url() . 'uploads/ad/default.png" />';
                echo '</div>';
            }
        }
    }
?>	
	</div>
		<style>#header1ad img{width:300px;height:50px;}</style>
		<div id="header1ad" style="text-align:center;height:50px;">
		
			<?php foreach($header1ads as $ad1):?>
				<?php 
					if($ad1->code == '') {
				?>
				<a href="<?php echo site_url('home/advertise/' . $ad1->id)?>" target="_blank">
				<img alt="" height="50px" src="<?=base_url() . $ad1->image?>" width="300px" />
				</a>
				<?php 
					} else {
						echo $ad1->code;
					}
				?>
			<?php endforeach;?>	
		</div>	
</div>  
