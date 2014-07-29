<?php 
if($event->expired_time < time()) $expired = 1;
else $expired = 0;
?>﻿
<script>
    var logged = 0;
    var expired = <?=$expired?>;
    <?php if($logged) echo "logged = 1;";?>
    $(document).ready(function(){
            countries.expandit(0); // tab gioi thieu
			//Examples of how to assign the ColorBox event to elements
		});
        
        function closeColorbox() {
            $.fn.colorbox.close();
        } 
        
        function checkLogged() {
            if(logged == 1) {
                if(expired == 1) {
                    alert('Sự kiện đã kết thúc');
                    countries.expandit(0);
                }
            } else {
                $('.join').hide(); // an? tab choi
                eventLogin();
            }
        }
	</script>
<script src="<?=base_url()?>js/jquery.slider.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>js/countdown/jquery.countdown.js"></script>
<?php
if($event->expired_time > time()) $util = $event->expired_time - time();
else $util = 0;
?>
<script type="text/javascript">
$(function () {
	$('#defaultCountdown').countdown({until: <?=$util?>});
});
</script>

<div class="scrolltop">
	<marquee behavior="scroll">
	<?php if($headertext) echo $headertext->code?>
	</marquee> 
</div>
<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/event')?>">Sự kiện</a>
        <a href="<?=site_url('home/event/'.$event->event_id)?>">Tham gia</a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>
</div>
<div class="giftBox">
  <ul class="detailGift">
    <li>
    	<div class="thumbGift">
      <div class="nameGift"><?=$event->name?></div>
      
      <div class="itemGift">
     
      <div class="status"><a href="javascript:countries.expandit(1)" class="goPlay">Tham gia</a></div>
      
    	
      <div class="infoGift" >
        <div class="time">
          <div class="titlebx">Thời gian còn lại</div>
          <div id="defaultCountdown"></div>
        </div>
        <div class="numsPlay">Hiện có <b><?=$event->playing?></b> lượt chơi<br/>Tài trợ: <?=$event->sponsor?></div>
      </div>
      <div class="picGift"><img alt="list" src="<?=$event->image?>" /></div>
      <div class="social">
        <div class="social-in">
            <a href="#"><img alt="list" src="<?=base_url()?>images/logos/facebook.png" /></a>
            <a href="#"><img alt="list" src="<?=base_url()?>images/logos/googleplus.png" /></a>
            <a href="#"><img alt="list" src="<?=base_url()?>images/logos/twitter.png" /></a></div>
      </div></div>
      
      </div>
      
    </li>
    
  </ul>
  <div class="tabContent">
      <div class="shadetabs">
    <ul id="countrytabs" class="links">
          <li><a href="#" rel="country1" class="selected">Giới thiệu</a></li>
          <li onclick="checkLogged();"><a href="#" rel="country2">Chơi</a></li>
          <li><a href="#" rel="country3">Thống kê</a></li>
          <li><a href="#" rel="country4">Trợ giúp</a></li>
        </ul>
  </div>
  <div class="sContent">
    <div id="country1" class="tabcontent">
      <div class="dContent">
        <div class="desc_app point">
          <ul class="data">
            <li>
            <div class="slider">
                <?=$event->desc?>
              </div>
            <div class="slider_menu"> <a href="#" onclick="return sliderAction();">Xem thêm...</a> </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div id="country2" class="tabcontent">
      <div class="dContent">
        <div class="join">
        	<?=$playTab?>
        </div>
          
        <div class="elements login" id="event-login" style="display:none;">
          <div class="title-bx">
            <h1><a href="javascript:eventLogin();">Đăng nhập</a></h1>

          </div>
          <div class="content-bx">
            <ul class="formElements">
              <li class="smallfield"> <span class="name">Tên đăng nhập:</span>
                <input placeholder="" type="text" name="username5" />
              </li>
              <li class="smallfield"> <span class="name">Mật khẩu:</span>
                <input placeholder="" type="password" name="password5"  />
              </li>
            </ul>
            <div class="buttonPanel"> <a href="javascript:login(5,5);" class="white button w50" id="login-bttss" >Đăng nhập</a> <a href="javascript:middleLogin();" class="white button w50">Hủy bỏ</a> </div>
          </div>
        </div>  
      </div>
    </div>
    <div id="country3" class="tabcontent">
      <div class="dContent">
    <div class="statistics">
        
    <?php if($eventType == 'event'):?>    
        
    <div class="titlebx"><span>Top 10 may mắn</span></div>
    <div class="contentbx">
    <?php if($luckyPlayers):?>
    <table  border="0" cellspacing="0" cellpadding="0" class="tb_statistics">
    <tr>
        <th scope="col" class="stt">STT</th>
        <th scope="col" class="member" align="left">Tên tài khoản</th>
      </tr>
      <?php $i=1;foreach($luckyPlayers as $player):?>
      <tr>
        <td class="stt"><?=$i?></td>
        <td class="member" align="left"><?=$player->username?></td>
      </tr>
      <?php $i++;endforeach?>
    </table>
    <?php endif?>
    <?php if(!$luckyPlayers):?>
    Hiện tại chưa có dữ liệu
    <?php endif?>
    </div> 
        
    <div class="titlebx"><span>Top 10 tích cực</span></div>
    <div class="contentbx">
    <?php if($mostPlayers):?>
    <table  border="0" cellspacing="0" cellpadding="0" class="tb_statistics">
    <tr>
        <th scope="col" class="stt">STT</th>
        <th scope="col" class="member" align="left">Tên tài khoản</th>
        <th scope="col" class="numsend">Số lượt chơi</th>
      </tr>
      <?php $i=1;foreach($mostPlayers as $player):?>
      <tr>
        <td class="stt"><?=$i?></td>
        <td class="member" align="left"><?=$player->username?></td>
        <td class="numsend"><?=$player->playing?></td>
      </tr>
      <?php $i++;endforeach?>
    </table>
    <?php endif?>
    <?php if(!$mostPlayers):?>
    Hiện tại chưa có dữ liệu
    <?php endif?>
    </div>
    
    <?php endif; // end eventType == event ?>
    
    <div class="titlebx"><span>Top 10  mới chơi</span></div>
    <div class="contentbx">
    <?php if($lastPlayers):?>
    <table  border="0" cellspacing="0" cellpadding="0" class="tb_statistics">
    <tr>
        <th scope="col" class="stt">STT</th>
        <th scope="col" class="member" align="left">Tên tài khoản</th>
      </tr>
      <?php $i=1;foreach($lastPlayers as $player):?>
      <tr>
        <td class="stt"><?=$i?></td>
        <td class="member" align="left"><?=$player->username?></td>
      </tr>
      <?php $i++;endforeach?>
    </table>
    <?php endif?>
    <?php if(!$lastPlayers):?>
    Hiện tại chưa có dữ liệu
    <?php endif?>    
    </div>
    </div>
    </div>
</div>
        <div id="country4" class="tabcontent">
          <div class="dContent">
        <div class="info_app">
              <div class="help_app">Thông tin trợ giúp</div>
              <ul class="support_app">
            <li>
                  <h3>Yahoo</h3>
                  <big>hotro06</big> </li>
            <li>
                  <h3>Skype</h3>
                  <big>hotro06</big></li>
            <li>
                  <h3>Hotline</h3>
                  <big>19005785</big> </li>
            <li>
                  <h3>Email</h3>
                  <big><a href="mailto:hotro@appstore.vn">hotro@appstore.vn</a></big></li>
          </ul>
              <div class="titleMess"><span>Thắc mắc của bạn:</span></div>
              <ul class="send_mess">
            <li>
                  <textarea  id="contentthacmac" name="contentfb"></textarea>
                </li>
            <li>
                  <input type="submit" name="sendfeedback" value="Gửi thắc mắc" onclick="return guithacmac();" >
                </li>
          </ul>
            </div>
      </div>
        </div>
  </div>
<?php
                //get store name
                $storeName = substr(base_url(), -2, 1);
                $userAgent = $this->input->user_agent();
?>
      <script type="text/javascript">
        function guithacmac() {
            var mainContent = $('#contentthacmac').val();
            var subject = "Thắc mắc về sự kiện " + '<?=$event->event_id?>' + ", kho " + '<?=$storeName?>';
            var content = "Chào AppstoreVN,%0A%0A";
            content += "Tôi có thắc mắc là:%0A";
            content += mainContent;
            content += "%0A------------------------------------";
            content += "%0AUsername của tôi là: " + '<?=$this->session->userdata('username')?>' + "%0A";
            content += "Tôi đang dùng máy:%0A" + '<?=$userAgent?>' + "%0A";
            content += "Link: " + '<?=site_url('home/event/'.$event->event_id)?>' +"%0A";
            window.location.href = "mailto:hotro@appstore.vn?subject=" + subject + "&body=" + content; 
        }

var countries=new ddtabcontent("countrytabs")
countries.setpersist(true)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()

</script> 
    </div>
</div>
<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/event')?>">Sự kiện</a>
        <a href="<?=site_url('home/event/'.$event->event_id)?>">Tham gia</a>
    </div>
    <div class="rightpath">
        <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
    </div>
  </div>
</div>
<!--Popup-->
<div style='display:none'> 
   <!--Gift-->
  <div id='giftselect'>
    <div class="dialog giftselect">
      <div class="title-bx">
        <h1>Kết quả trả về</h1>
      </div>
      <div class="content-bx">
        
        <ul class="formDialog">
          <li class="contentGift" id="resulttext">
              
          </li>
          
        </ul>
        <div class="buttonPanel"> <a href="#" class="white button w100" id="login-btt" >OK</a></div>
        </div>
    </div>
  </div>
</div>
