<?php 
    $now = time();
    $userAgent = $this->input->user_agent();
    $isOS3 = strpos($userAgent, 'OS 3');
    $os3popup = $this->session->userdata('os3popup');
    $goitai = array(
        'http://appstore.vn/i/' => 'Gói tải ứng dụng',
        'http://appstore.vn/a/' => 'Gói tải ứng dụng',
        'http://appstore.vn/b/' => 'Gói tải ứng dụng',
        'http://appstore.vn/c/' => 'Gói đọc truyện',
        'http://appstore.vn/f/' => 'Gói xem phim',
        'http://appstore.vn/m/' => 'Gói nghe nhạc',
        'http://appstore.vn/e/' => 'Gói đọc truyện',
        'http://appstore.vn/trunk/a/' => 'Gói đọc truyện',
        'http://app.vn/' => 'Gói test local'
    );
?>
<?php 
    if(isset($_SERVER['HTTP_REFERER'])) {
        $url = $_SERVER['HTTP_REFERER'];
    } else {
        $url = site_url('home/category/'.$categoryId);
    }
    $app->size = strip_tags($app->size);
    $app->version = strip_tags($app->version);
    if(!$app->size) $app->size = 'none';
    $version = $this->app_model->getLastVersion($app->app_id);
    $app->version = $version;
    //khuyen mai app
    $price = $this->app_model->getTymPrice($app->app_id);
    if(!$price['price']) $ribbon_free = true;
    else $ribbon_free = false;

    //price
    $priceColor = array(
        't1' => 'Red',
        't2' => 'Purple',
        't3' => 'Green',
        't4' => 'yellow'
    );
    $appPrice = $price['price'];
    $promoPrice = $appPrice - $app->promo_price;
    $color = $priceColor[$price['type']];
    if($appPrice) {
        $percentStr = $app->promo_price/$appPrice;
        $percentStr *= 100;
        $percent = ceil($percentStr);
        if($percent < 2) $percent = 1;
    } else {
        $percent = 1;
    }
    if($app->promo_start <= $now && $now <= $app->promo_end && $app->promo_enable) {
        $price['price'] -= $app->promo_price;
    }
?>
<?php if($logged) { ?>
<script type="text/javascript">
    var userTym = new Array();
    userTym['t1'] = <?=$user->t1?>;
    userTym['t2'] = <?=$user->t2?>;
    userTym['t3'] = <?=$user->t3?>;
    userTym['t4'] = <?=$user->t4?>;
    
    var moreTym = new Array();
    moreTym['t1'] = 0;
    moreTym['t2'] = <?=floor($user->t1 * $rates['rate1_2'])?>;
    moreTym['t3'] = <?=floor($user->t1 * $rates['rate1_3'])?>;
    moreTym['t4'] = <?=floor($user->t1 * $rates['rate1_4'])?>;
</script>
<?php } else { ?>
<script type="text/javascript">
    var userTym = new Array();
    userTym['t1'] = 0;
    userTym['t2'] = 0;
    userTym['t3'] = 0;
    userTym['t4'] = 0;
    
    var moreTym = new Array();
    moreTym['t1'] = 0;
    moreTym['t2'] = 0;
    moreTym['t3'] = 0;
    moreTym['t4'] = 0;
</script>
<?php } ?>
<script type="text/javascript" src="<?=base_url()?>js/jquery.slider.js"></script>
<script type="text/javascript" src="<?=base_url()?>js/popup/ddaccordion.js"></script>
<script type="text/javascript">
var myScroll;

function loaded() {
	myScroll = new iScroll('scroller', {
		snap:true,
		momentum:false,
		hScrollbar:false,
		onScrollEnd: function () {
			document.querySelector('#indicator > li.active').className = '';
			document.querySelector('#indicator > li:nth-child(' + (this.pageX+1) + ')').className = 'active';
		}
	 });
}

document.addEventListener('DOMContentLoaded', loaded, false);
</script>
<script type="text/javascript">
var myScrollpic;

function loaded() {
	myScrollpic = new iScroll('scrollerpic', {
		snap:true,
		momentum:false,
		hScrollbar:false,
		onScrollEnd: function () {
			document.querySelector('#indicatorpic > li.activepic').className = '';
			document.querySelector('#indicatorpic > li:nth-child(' + (this.pageX+1) + ')').className = 'activepic';
		}
	 });
}

document.addEventListener('DOMContentLoaded', loaded, false);
</script>

<!---->

<script>
$(document).ready(function(){
        countries.expandit(0);  // tab description app

        <?php if(!$os3popup && $isOS3 && base_url() == 'http://appstore.vn/e/'): ?>
              os3popup();
        <?php endif; ?>
        $('.link-download').click(function(){
            $('#links').hide('slow');
            $('#info').show();
        });

        $('.title_app').click(function(){
            $('#links').hide('slow');
            $('#info').show();
        });
        showMore();

        // hiển thị vote
    $('.total-rating').raty({
    	half:  true,
    	start: <?php if($app->vote == 0){echo '0';}else{echo $app->vote_score/$app->vote;} ?>,
    	readOnly: true
    });
    
});

    function back() {
        history.go(-1);
    }

    var dich = 0;
    function translateDesc(appid)
    {
        if(dich > 0) {
        $("#translated").show();
        $("#translated_en").hide();
        exit;
        }
        dich++;
		$.ajax({
			url: "<?php echo site_url('home/translated');?>",
			type: "POST",
			data: "appid=" + appid,
			beforeSend: function() {
				$("#translated_en").hide();
				$("#translated").html("Translating");
			},
			success: function(response) {
				$("#translated").html(response);
			}
		});
    }    

function translated_vi() {
    $("#translated_en").show();
    $("#translated").hide();
}

var accept = 0;

function beforeDownloadTicket(store, ticketId, versionId, order, price, tx) {
    //price = 0 hoặc có giá nhưng vẫn trong 24h thì price = 0 -> ticket_view.php
    if(price == '0') {
        downloadTicket(store, ticketId, versionId, order);
    } else {
        accept = 0;
        /*
         * khong du tien
         */
        if((userTym[tx]+moreTym[tx])<price) {
            var data = new Array();
            data['t1'] = 'Red';
            data['t2'] = 'Purple';
            data['t3'] = 'Green';
            data['t4'] = 'Yellow';
            $('#currentt1').html(userTym['t1']);
            $('#xprice').html(price);
            $('#xtype').css('color', data[tx]);
            $('#xcurrent').html(userTym[tx]);
            $('#xtype-current').css('color', data[tx]);
            var xtotal = userTym[tx] + moreTym[tx];
            $('#xtotal').html(xtotal);
            $('#xtype-total').css('color', data[tx]);
            if(tx == 't1') $('#typeoption').hide();
            else $('#typeoption').show();
            $.fn.colorbox({
                inline:true,
                width:"100%",
                height:"100%",
                href:"#downloadbox-fee",
                transition:"fade"
            });
        } else {
            var data = new Array();
            data['t1'] = 'Red';
            data['t2'] = 'Purple';
            data['t3'] = 'Green';
            data['t4'] = 'Yellow';
            $('#free-currentt1').html(userTym['t1']);
            $('#free-xprice').html(price);
            $('#free-xtype').css('color', data[tx]);
            $('#free-xcurrent').html(userTym[tx]);
            $('#free-xtype-current').css('color', data[tx]);
            var xtotal = userTym[tx] + moreTym[tx];
            $('#free-xtotal').html(xtotal);
            $('#free-xtype-total').css('color', data[tx]);
            if(tx == 't1') $('#typeoption').hide();
            else $('#typeoption').show();
            $.fn.colorbox({
                inline:true,
                width:"100%",
                height:"100%",
                href:"#downloadbox-free",
                transition:"fade",
                onClosed: function() {
                    if(accept == 1) {
                        downloadTicket(store, ticketId, versionId, order);
                    }
                }
            });
        }
    }
}

function acceptDownload() {
    accept = 1;
    $.colorbox.close();
}

function cancel() {
    accept = 0;
    $.colorbox.close();
}

/*
 * vip type
 * 0 chưa đăng kí
 * 1 đã hết hạn
 * 2 là vip
 */
var fshare_vip = <?=$vips['fshare']?>;
fshare_vip = 2;
// store: megashare || fshare || 4share
function downloadTicket(store, ticketId, versionId, order) {
    if(store == 'fshare') {
        if(fshare_vip != 2) {
            var fshare_reg = 'gói vip share của bạn đã hết hạn, bạn có muốn gia hạn vip?';
            if(fshare_vip == '0') {
                fshare_reg = 'bạn có muốn đăng kí gói vip fshare để tải nhanh hơn?';
            }
            var fshare_conf = window.confirm(fshare_reg);
            if(fshare_conf) {
                // show form dang ki vip
                $.fn.colorbox({
                    width:"100%",
                    height: "100%",
                    inline:true,
                    href:"#vipdownloadbox",
                    onClosed:function(){
                        afterBuy(ticketId, versionId, order);
                    }
                });
                return;
            }
        }
    }
    $('#links').hide();
    $.ajax({
        url: "<?php echo site_url('home/download') ?>/" + ticketId + "/" + versionId + "/" + order,
        type: "POST",
        success: function(data) {
            if (data=='0') {
                var answer = confirm('Bạn không đủ Tym để tìm link này, bạn có muốn nạp Tym vào tài khoản?');
                if (answer) {
                    window.location.href = '<?=site_url('home/account')?>';
                }
            } else if(data == '1') {
                var answer = confirm('<?=$goitai[base_url()]?> của bạn đã hết hạn hoặc chưa đăng kí, bạn có muốn mua gói?');
                if(answer) {
                    window.location.href = '<?=site_url('home/account')?>';
                }
            } else {
                countries.expandit(0);  // tab description app
                setTimeout("window.location.href = '" + data + "';", 1000);
            }
        }
    });
}

function checkLogged(appId) {
    //khac voi khong su dung goi va gia = 0
    //su dung goi va gia = 0
    //khong su dung goi va gia > 0
    //su dung goi va gia > 0
    var price = <?=$price['price']?>;
    var catPrice = 1;
    <?php if(isset($price['catPrice'])) echo "catPrice = 0;"; ?>
    
    if(logged == 1 || price == 0) {
        download(appId);
    } else {
        middleLogin();
    }
}

function getTickets(versionId) {
    
    $.ajax({
        url: "<?php echo site_url('home/getTickets');?>/"+versionId,
        type: "POST",
        beforeSend: function() {
            $('.showprice'+versionId).hide();
            $('.loading-bar'+versionId).show();
        },
        success: function(response) {
            $('#ticket-'+versionId).html(response);
            $('.loading-bar'+versionId).hide();
            $('.showprice'+versionId).show();
        }
    });
}
function os3popup() {
    var confirm = window.confirm('Bạn đang dùng iOS3, hãy đọc hướng dẫn xem ebook với iOS3 nhé???');
    if(!confirm) return;
    $.ajax({
        url: "<?=site_url('home/os3popup')?>",
        success: function() {}
    });
    window.location.href = '<?=site_url('home/help/help_ebook')?>';
}

/*
 * buy vip fshare
 */
function buyFshare(package_id) {
    var conf = window.confirm('Bạn có chắc chắn mua gói này?');
    if(!conf) return;
    $.ajax({
        url: "<?=site_url('home/buyfshare')?>/" + package_id,
        beforeSend: function() {},
        success: function(flag) {
            /*
             * cờ trạng thái
             * 0 -> hết tiền
             * 1 -> chưa đăng nhập
             * 2 -> lỗi kết nối fshare
             * 99 -> thành công
             */
            if(flag == 0) {
                var conf = window.confirm('bạn không đủ tym để buy vip fshare, bạn có muốn nạp tài khoản?');
                if(conf) {
                    window.location.href = '<?=site_url('home/tym')?>';
                }
            } else if(flag == 1) {
                alert('Vui lòng đăng nhập');
                $.fn.colorbox({width:"100%", height: "100%", inline:true, href:"#loginbox"});           
            } else if(flag == 2) {
                alert('lỗi kết nối với fshare, vui lòng liên hệ với support. Sẽ tiếp tục tải không vip');
            } else {
                alert('đăng kí vip fshare thành công');
            }
            $.colorbox.close();
        }
    }); 
}

// gọi hàm này sau khi buy vip (thành công hoặc không)
function afterBuy(ticketId, versionId, order) {
    $.ajax({
        url: "<?php echo site_url('home/download') ?>/" + ticketId + "/" + versionId + "/" + order,
        type: "POST",
        success: function(data) {
            if (data=='0') {
                var answer = confirm('Bạn không đủ Tym để tìm link này, bạn có muốn nạp Tym vào tài khoản?');
                if (answer) {
                    window.location.href = '<?=site_url('home/account')?>';
                }
            } else if(data == '1') {
                var answer = confirm('<?=$goitai[base_url()]?> của bạn đã hết hạn hoặc chưa đăng kí, bạn có muốn mua gói?');
                if(answer) {
                    window.location.href = '<?=site_url('home/account')?>';
                }
            } else {
                window.location.href = data;
            }
        }
    });
}
</script>
<body>
    
<style>#tymdo{color:red;}</style>   

<div style="display:none;">
    <div id="vipdownloadbox" style="top:100px;">
        <ul class="pageacc">
		<li class="smallfield">
        <span class="name">Mua gói VIP download FShare</span>
        <div class="infoButton">
            <?php if($fsharePack){?>
                <?php foreach($fsharePack as $fshare):?>
                <a href="javascript:buyFshare(<?=$fshare->id?>);">
                    Gói <?=$fshare->key?> ngày = <?=number_format($fshare->value, 0, ',', '.')?> <span id="tymdo">♥</span>
                </a>
                <?php endforeach;?>
            <?php } else {?> 
                <a href="#">Hiện tại chưa có gói nào hết</a>
            <?php }?>
        </div>
        </li>
        </ul>
    </div>
</div>
<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/category/'.$categoryId)?>"><?=$categoryName?></a>
        <!--<a href="javascript;" style="color:black !important;"> >> </a>-->
        <a href="<?=site_url('home/app/'.$app->app_id)?>">Thông tin</a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>
  <ul>
    <li class="appdetail">
        <?php if(!$price['price']):?>
        <span class="ribbon_free"></span>
        <?php endif?>
        <?php if($app->promo_start <= $now && $now <= $app->promo_end && $app->promo_enable) { ?>
        <span class="ribbon_sale"><b>-<?=$percent?>%</b></span>
        <?php } ?>
        <span class="shadown">
            <?php
                if(file_exists($app->image) || file_exists('.'.$app->image)) {
                    $appThumb = $app->image;
                } else {
                    $defaultThums = array(
                        'http://appstore.vn/a/' => 'images/android-default.png',
                        'http://appstore.vn/b/' => 'images/bb-default.png',
                        'http://appstore.vn/c/' => 'images/comic-default.png',
                        'http://appstore.vn/e/' => 'images/ebook-default.png',
                        'http://appstore.vn/f/' => 'images/film-default.png',
                        'http://appstore.vn/i/' => 'images/ios-default.png'
                    );
                    if(!array_key_exists(base_url(), $defaultThums)) {
                        $defaultThums[base_url()] = 'images/ios-default.png';
                    }
                    $appThumb = $defaultThums[base_url()];
                    //$appThumb = $app->image;
                }
            ?>
            <img alt="list" src="<?=base_url().$appThumb?>" />
        </span>
        <span class="comment"><?=$app->app_name?></span>
        <span class="name"><?=$app->vendor?></span>
        <span class="download">
            <b class="iVers"><?=$app->version?></b>
            <b class="iSize"><?=$app->size?></b>
            <b class="iDown"><?=$app->download?></b>
        </span>
        <?php 
            if($app->promo_start <= $now && $now <= $app->promo_end && $app->promo_enable) {
        ?>
        <span class="sales">
              <?=$appPrice?><b class="tym<?=$color?>">♥</b>→<?=$promoPrice?><b class="tym<?=$color?>">♥</b> [ <span id="time<?=$app->app_id?>" class="time"></span>]
        </span>
        <script>
            $("#time<?=$app->app_id?>").countdown({
                date: "<?=date('M j, Y H:i:s', $app->promo_end)?>", 
                onChange: function( event, timer ){



                },
                onComplete: function( event ){

                    $(this).html("Đã hết hạn");
                },
                leadingZero: true,
                direction: "down"
            });
        </script>
        <?php } else { ?>
        <span class="stars0" id="jraty<?=$app->app_id?>"></span>
        <span class="starcomment"><b class="iComm"><?=$app->comment?></b></span>
        <?php } ?>
  </ul>
</div>
   
<div class="tabContent">
    <div class="shadetabs">
    <ul id="countrytabs" class="links">
          <li><a href="#" rel="country1" class="selected">Mô tả</a></li>
          <li onclick="checkLogged(<?=$app->app_id?>);"><a href="#" rel="country2">Tìm link tải</a></li>
          <li><a href="#" rel="country3">Trợ giúp</a></li>
        </ul>
    </div>
    <div class="sContent">
    <div id="country1" class="tabcontent">
          <div class="dContent">
        <div class="desc_app">
            <ul class="data">
            <li>
              <div class="slider">
                <?=$app->description?>
              </div>
                  <div class="slider_menu"> <a href="#" onclick="return sliderAction();">Xem thêm...</a> </div>
            </li>
          </ul>
        </div>
      <!-- screenshot -->
      <?php if(!strpos($app->screenshot,"no_screenshot") && $app->screenshot!=''):?>
        <div  class="horizontal-scroll-wrapper">
      <div id="wrapperpic">
        <?php 
            $screenshots = explode('@@', $app->screenshot);
            $num = count($screenshots); $num = $num * 200;
        ?>
        <div id="scrollerpic" style="width:<?=$num?>px">
        <ul >
        <?php
            foreach($screenshots as $screenshot) {
        ?>
          <li>
            <img class="portrait" src="<?=base_url().$screenshot?>" />
          </li>
        <?php } ?>  
        </ul>
        </div>
        </div>
        <div id="navslidepic">
          <ul id="indicatorpic">
        <li class="activepic">1</li>
        <li>2</li>
        <li>3</li>
        <li>4</li>
        <li>5</li>
      </ul>
        </div>
        </div>
      <?php endif;?>
      <!-- end screenshot -->
        <div id="comment_app">
              <div class="commentNums"><span><?=$app->comment?> Bình luận</span>
            <div class="total-rating"></div>
          </div>
            <ul>
            
            <span id="commentslist"></span>
            	
            </ul>
            <?php if(count($comments) == $commentLimit) { ?>
            <div id="showmorecomment">
            <div id="showmorelink"> <a href="javascript:showMore();">Xem thêm...</a> </div>
            <div id="showmoreloading" style="display:none; margin-top:5px; padding-top:5px;">
              <center>
                <img src="<?=base_url()?>/images/loading.gif" align="center">
              </center>
            </div>
            </div>
            <?php } ?>
            </div>
        
      </div>
        </div>
    <div id="country2" class="tabcontent">
      <div class="dContent">
        <div id="loading_bar" style="display: none;background-color:#333333;text-align:center;color:white;">
            <br/>&nbsp;&nbsp;Đang tìm kiếm...<br/>
            <img style="width: 100%;" height="19" src="<?=base_url()?>/images/loading_bar.gif"/>
            <br />
            <br />
        </div>  
        <div id="links">
          
        </div>
        <div class="elements login" id="download-login" style="display:none;">
          <div class="title-bx">
            <h1><a href="javascript:middleLogin();">Đăng nhập</a></h1>

          </div>
          <div class="content-bx">
            <ul class="formElements">
              <li class="smallfield"> <span class="name">Tên đăng nhập:</span>
                <input placeholder="" type="text" name="username3" />
              </li>
              <li class="smallfield"> <span class="name">Mật khẩu:</span>
                <input placeholder="" type="password" name="password3"  />
              </li>
            </ul>
            <div class="buttonPanel"> <a href="javascript:login(3,3,<?=$app->app_id?>);" class="white button w50" id="login-bttss" >Đăng nhập</a> <a href="javascript:middleLogin();" class="white button w50">Hủy bỏ</a> </div>
          </div>
        </div>  
      </div>
    </div>
    <div id="country3" class="tabcontent">
          <div class="dContent">
        <div class="info_app">
              <?php if(base_url() == 'http://appstore.vn/e/'):?>
              <div class="help_app">Để đọc truyện, bạn cần cài đặt ứng dụng Comic Zeal. Sau đó bạn vào lại AppstoreVN...</div>
              <?php endif?>
              <ul class="support_app">
            <li>
                  <h3>Yahoo</h3>
                  <big>hotro06</big> </li>
            <li>
                  <h3>Skype</h3>
                  <big>hotro06</big></li>
            <li>
                  <h3>Hotline</h3>
                  <big>1900599975</big> </li>
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
?>
      <script type="text/javascript">
        function guithacmac() {
            var mainContent = $('#contentthacmac').val();
            var subject = "Thắc mắc về nội dung " + '<?=$app->app_id?>' + ", kho " + '<?=$storeName?>';
            var content = "Chào AppstoreVN,%0A%0A";
            content += "Tôi có thắc mắc là:%0A";
            content += mainContent;
            content += "%0A------------------------------------";
            content += "%0AUsername của tôi là: " + '<?=$this->session->userdata('username')?>' + "%0A";
            content += "Tôi đang dùng máy:%0A" + '<?=$userAgent?>' + "%0A";
            content += "Link: " + '<?=site_url('home/app/'.$app->app_id)?>' +"%0A";
            window.location.href = "mailto:hotro@appstore.vn?subject=" + subject + "&body=" + content; 
        }

var countries=new ddtabcontent("countrytabs")
countries.setpersist(true)
countries.setselectedClassTarget("links") //"link" or "linkparent"
countries.init();

function getCaptcha() {
    return;
    if($('#giftcode-box').css('display') == 'block') return;
    $.ajax({
        url: "<?=site_url('home/genCaptcha')?>",
        success: function(data) {
            $('.imgCaptcha').attr('src', data);
        }
    })
}

</script> 
<script>
function genGiftcode() {
    var captcha = $('input[name=captcha]').val();
    if(captcha == '') {
        alert('Vui lòng nhập ảnh xác nhận');
        return;
    }
    $.ajax({
        url: "<?=site_url('home/genAppCode')?>",
        data: "captcha=" + captcha + "&app_id=" + <?=$app->app_id?>,
        type: "POST",
        beforeSend: function(){},
        success: function(data) {
            if(data == 0) {
                alert('Fake request');
                return;
            } else if(data == 1) {
                alert('Vui lòng đăng nhập');
                return;
            } else if(data == 2) {
                alert('Mã xác nhận không đúng');
                return;
            } else {
                var result = data.split('@@');
                $('#codehere').html(result[0]);
                $('#step2').show('slow');
                //thay captcha
                $('.imgCaptcha').attr('src', result[1]);
                $('input[name=captcha]').val('');
            }
        }
    });
}
</script>
</div>
    <div class="info_app">
          <ul class="mess_bt arrowlistmenu">
            <li><a href="#" class="menuheader expandable"><img src="<?=base_url()?>images/button/binhluan.png"/><span class="titleInfo">Bình luận</span></a></li>
            <li><a href="#" class="menuheader expandable"><img src="<?=base_url()?>images/button/loantin.png"/><span class="titleInfo">Loan tin</span></a></li>
            <li><a href="#" class="menuheader expandable" onclick="getCaptcha();"><img src="<?=base_url()?>images/button/guitang.png"/><span class="titleInfo">Gửi tặng</span></a></li>
          </ul>
          <div class="categoryitems" style="display:none;">
             
          <div class="elements login" id="comment-login" style="display:none;">
              <div class="title-bx">
                <h1><a href="javascript:middleLogin1();">Đăng nhập</a></h1>

              </div>
              <div class="content-bx">
                <ul class="formElements">
                  <li class="smallfield"> <span class="name">Tên đăng nhập:</span>
                    <input placeholder="" type="text" name="username4" />
                  </li>
                  <li class="smallfield"> <span class="name">Mật khẩu:</span>
                    <input placeholder="" type="password" name="password4"  />
                  </li>
                </ul>
                <div class="buttonPanel"> <a href="javascript:login(4,4);" class="white button w50" id="login-bttss" >Đăng nhập</a> <a href="javascript:middleLogin1();" class="white button w50">Hủy bỏ</a> </div>
              </div>
            </div>   
            <div id='commentbox'>
            <div class="elements send-comment">
              <div class="content-bx">
                  <?php if(!$voted) {?>
                    <div class="sendRating">
                          <h4>Bình chọn ứng dụng</h4>
                          <span id="app-rating" style="width: 100px; cursor: pointer; ">

                          </span> 
                    </div>
                  <?php } ?>          
                    <div id="comment-form" class="comment-form">
                          <textarea onblur="clickrecall(this,'text entry')" onclick="clickclear(this, 'text entry')" name="contentcm" id="contentcm"></textarea>
                          <input type="submit" name="postcm" value="Gửi bình luận" onclick="canComment();" >
                          <br /><br />
                    </div>
              </div>
            </div>
            </div>
        </div>
        <div class="categoryitems" style="display:none;">    
            <div id="sharebox">
            <div class="elements send-comment">
              <div class="content-bx">
                <div id="link">
                <style>#link img{width:40px !important;height:40px;}</style>    
                <a href="javascript:share_facebook();" title="share on facebook"><img alt="share on facebook" src="<?=base_url()?>/images/facebook.png" /></a>
                <a href="javascript:share_twitter();" title="share on twitter"><img alt="share on twitter" src="<?=base_url()?>/images/twitter.png" /></a>
                <a href="javascript:share_linkhay();" title="share on linkhay"><img alt="share on linkhay" src="<?=base_url()?>/images/buzz.png" /></a>
                </div>
              </div>
            </div>
            <br />
          </div>
        </div>
        
      <div class="categoryitems" style="display:none;" id="giftcode-box">
      <div id="sendfriend">
        <div class="elements join">
              <div class="giftCode">
                    <ul>
                        <li>

                        <p class="Captcha">
                            <span class="tlCaptcha"><b>Bước 1: </b>Nhập mã an toàn</span>
                            <img src="<?=base_url()?>uploads/captcha/default.jpg" alt="" class="imgCaptcha" />
                            <input name="captcha" type="text" class="ipCaptcha" />
                        </p>
                        <p><input name="Gửi tặng" type="submit" onclick="genGiftcode();" value="Gửi tặng" /></p>
                        <div id="step2" style="display:none;">
                        <p class="Captcha">
                            <b>Bước 2: </b>Copy mã sau rồi gửi tặng bạn của bạn
                        </p>    
                        <p class="createdCode" id="codehere">EFE45342343FE</p>
                        <p class="noteStep">Ấn tay 2 lần vào để COPY và gửi cho bạn của bạn.</p>
                        <p class="Captcha">
                            <b>Bước 3: </b>Người nhận giftcode này phải có tài khoản trên AppStore, rồi vào phần tài khoản để kích hoạt giftcode!
                        </p> 
                        </div>
                    </li>

                </ul>
            </div>
              </div>
        </div>
      </div>
</div>
<!-- related app -->

<div class="slideappother">
      <div class="titleappother"><span>Các nội dung liên quan</span></div>
      <div class="appother">
    <div id="wrapper">
      <div id="scroller" style="width:810px">
        <ul id="thelist">
          <li>
            <ul class="pageother">
                <?php 
                    for($i=0; $i<3; $i++) {
                        if(isset($relatedApps[$i])) {
                ?>
                  <li class="storeother">
                      <a href="<?=site_url('home/app/'.$relatedApps[$i]->app_id)?>">
                          <img alt="list" src="<?=base_url().$relatedApps[$i]->image?>" />
                          <span class="comment"><?=$relatedApps[$i]->app_name?></span>
                      </a>
                  </li>
                <?php 
                        }
                    } 
                ?>
            </ul>
          </li>
         <li>
            <ul class="pageother">
                <?php 
                    for($i=3; $i<6; $i++) {
                        if(isset($relatedApps[$i])) {
                ?>
                  <li class="storeother">
                      <a href="<?=site_url('home/app/'.$relatedApps[$i]->app_id)?>">
                          <img alt="list" src="<?=base_url().$relatedApps[$i]->image?>" />
                          <span class="comment"><?=$relatedApps[$i]->app_name?></span>
                      </a>
                  </li>
                <?php 
                        }
                    } 
                ?>
            </ul>
          </li>
          <li>
            <ul class="pageother">
                <?php 
                    for($i=6; $i<9; $i++) {
                        if(isset($relatedApps[$i])) {
                ?>
                  <li class="storeother">
                      <a href="<?=site_url('home/app/'.$relatedApps[$i]->app_id)?>">
                          <img alt="list" src="<?=base_url().$relatedApps[$i]->image?>" />
                          <span class="comment"><?=$relatedApps[$i]->app_name?></span>
                      </a>
                  </li>
                <?php 
                        }
                    } 
                ?>
            </ul>
          </li>
        </ul>
      </div>
        </div>
    <div id="navslide">
      <ul id="indicator">
        <li class="active">1</li>
        <li>2</li>
        <li>3</li>
      </ul>
        </div>
  </div>
</div>
<!-- end related app -->
    <div class="pathbar path">
    <div class="leftpath">
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/category/'.$categoryId)?>"><?=$categoryName?></a>
        <!--<a href="javascript;" style="color:black !important;"> >> </a>-->
        <a href="<?=site_url('home/app/'.$app->app_id)?>">Thông tin</a>
    </div>
    <div class="rightpath"><a href="#appstorevn" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a></div>
  </div>

<script>
var logged = 0;
<?php if($logged) echo "logged = 1;";?>

function sendEmail() {
	if(logged == 0) { alert('Vui lòng đăng nhập trước khi sử dụng chức năng này'); return; }
	var email = $("#send-email").val();
	//var pattern = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
	var pattern = new RegExp("@");
	if(!pattern.test(email)) {
		alert("Vui lòng nhập đúng địa chỉ email!");
		return;
	}
	$.ajax({
		url: "<?php echo site_url('home/sendEmail')?>",
		type: "POST",
		data: "email=" + email + "&name=<?=$app->app_name?>&app_id=<?=$app->app_id?>",
		beforeSend: function() {
			$("#send-button").val("waiting");
			$("#send-button").css("color","red");
		},
		success: function(data) {
            alert(data);
			$("#send-button").val("Sent");
			$("#send-button").css("color","green");
		}
	});
}
//report a problem
function reportApp(code) {
	if(logged == 0) {
		alert('Vui lòng đăng nhập để sử dụng chức năng này!');
		return;
	}
	var userid = 1;
	<?php if($userid) echo " userid = " . $userid . ";"?>
	$.ajax({
		url: "<?php echo site_url('home/reportApp')?>/" + code + "/" + <?=$app->app_id?> + "/" + userid,
		type: "POST",
		beforeSend: function() {
			$("#helper_report_reason").hide("slow");
			$("#helper_report_msg").show("slow");
			$("#helper_report_msg").html("<li class='smallfield'><div class='infoButton'><a href='#'>Đang thực hiện ... xin vui lòng chờ</a></li>");
		},
		success: function(data) {
			$("#helper_report_msg").html("<li class='smallfield'><div class='infoButton'><a href='#'>Cảm ơn bạn đã báo cáo!</a></div></li>");
			var timeout = setTimeout("$('#helper_report_msg').hide();$('#helper_report_reason').show();", 2000);
		}
	});
}
</script>
<script>
$(document).ready(function(){
	$('#colorbox_share').colorbox({width:'100%', height: "100%", inline:true, href:'#helper_share'});
	$('#colorbox_send').colorbox({width:'100%', height: "100%", inline:true, href:'#helper_send'});
	$('#colorbox_report').colorbox({width:'100%', height: "100%", inline:true, href:'#helper_report'});
});
function share_facebook() {
	window.open('http://www.facebook.com/share.php?u=<?php echo site_url('home/app/' . $app->app_id)?>');
}
function share_twitter() {
	window.open('http://twitter.com/#!/?status=<?php echo site_url('home/app/' . $app->app_id)?>');
}
function share_linkhay() {
	window.open('http://linkhay.com/submit?url=<?php echo site_url('home/app/' . $app->app_id)?>&title=AppStore.Vn');
}
</script>
<style>
#helper_send, #helper_share {text-align:center;margin:0 auto;width:100%;color:silver;}
#helper_report {color:red;text-align:justify;padding:5px;}
#helper_report a {text-decoration:none;color:silver;}
#send-email {width:300px;background:silver;color:white;}
</style>

<div style="display:none;">
  <!--comment-->
  
  <!----> 
    
  <!--download1-->
  <div id='downloadbox-fee'>
    <div class="dialog download">
      <div class="title-bx">
        <h1>Xác nhận</h1>
      </div>
        <div class="content-bx">
        <ul class="formDialog">
              
            <li class="smallfield"> 
                <span >Phí tải nội dung này là <span id="xprice"></span> <span id="xtype" style="color:red;">♥</span></span>
            </li>
            <li class="smallfield"> <span >Bạn đang có <span id="currentt1"></span> <span style="color:red;">♥</span> và <span id="xcurrent"></span> <span id="xtype-current" style="color:red;">♥</span></span></li>
          	<li class="smallfield" id="typeoption"><span>(Tương đương với <span id="xtotal"></span> <span id="xtype-total" style="color:red;">♥</span>)</span></li>
            <li class="smallfield"><span class="note">Bạn vui lòng nhập thêm TYM để tải nội dung này</span></li>
            </ul>
        <div class="buttonPanel"> <a href="<?=site_url('home/tym')?>" class="white button w50" >Nạp TYM</a><a href="javascript:cancel();" class="white button w50" >Bỏ qua</a> </div>
      </div>
    </div>
  </div>
  <div id='downloadbox-free'>
    <div class="dialog download">
      <div class="title-bx">
        <h1>Xác nhận</h1>
      </div>
        <div class="content-bx">
        <ul class="formDialog">
              
            <li class="smallfield"> 
                <span >Phí tải nội dung <span id="free-xprice"></span> <span id="free-xtype">♥</span></span>
            </li>
            <li class="smallfield"> <span >Tài khoản của bạn <span id="free-currentt1"></span> <span style="color:red;">♥</span> và <span id="free-xcurrent"></span> <span id="free-xtype-current" style="color:red;">♥</span></span></li>
          	<li class="smallfield" id="typeoption"><span>(Tương đương với <span id="free-xtotal"></span><span id="free-xtype-total" style="color:red;">♥</span>)</span></li>
            <li class="smallfield" id="typeoption"><span>Bạn có thể tải lại nội dung này trong vòng 24h mà không bị trừ tiền</span></li>
            </ul>
        <div class="buttonPanel"> <a href="javascript:acceptDownload();" class="white button w50" >Đồng ý</a><a href="javascript:cancel();" class="white button w50" >Bỏ qua</a> </div>
      </div>
    </div>
  </div>
  <!---->
</div>

<script>
var voted = 0;
<?php if($voted) echo "voted = 1;";?>

var page = -1;
function showMore() {
	var x = page + 1;
	window.location.hash = '#' + x;
	$("#showmorelink").hide();
	$("#showmoreloading").show();
	page++;
	$.ajax({
		url: "<?php echo site_url('home/moreCommentInApp/' . $app->app_id)?>/" + page,
		type: "POST",
		success: function(data) {
			$("#commentslist").append(data);
			$("#showmorelink").show();
			$("#showmoreloading").hide();
		} 
	});	
}

function loadComment() {
	window.location.hash = '#' + 0;
    page = -1;
	page++;
	$.ajax({
		url: "<?php echo site_url('home/moreCommentInApp/' . $app->app_id)?>/" + page,
		type: "POST",
		success: function(data) {
			$("#commentslist").html(data);
		} 
	});	
}

// vote app
function voteApp(score, appid) {
	if(logged == 0) {
		alert('Bạn phải đăng nhập trước khi bình chọn');
        middleLogin1();
        $('#commentbox').hide();
		return;
	}
	$.ajax({
		url: "<?php echo site_url('home/vote') ?>/" + appid + "/" + score,
		type: "POST",
		beforeSend: function() {
			$('.sendRating').html('<b>Đang gửi thông tin bình chọn!</b>');
		},
		success: function(data) {
			$('.sendRating').html('<b>Đã bình chọn!</b>');
			$('.sendRating').hide('slow');
			voted = 1;
		}
	});
}

// kiểm tra có đủ điều kiện để post bình luận không
function canComment() {
	if($("#contentcm").val() == "") {
		alert('Vui lòng nhập bình luận!');
		$("#contentcm").focus();
		return false;
	}	
	if(logged == 0) {
		alert('Vui lòng đăng nhập trước khi bình luận');
        middleLogin1();
        $('#commentbox').hide();
		return false;
	}
	if(voted == 0) {
		alert('Vui lòng bình chọn ứng dụng trước khi bình luận!');
		return false;
	}
	// 160 kí tự
	if($("#contentcm").val().length > 160) {
		alert('Bình luận không quá 160 kí tự');
		return false;
	}
	$.ajax({
        url: "<?=site_url('home/comment/'.$app->app_id)?>",
        data: "comment=" + $('#contentcm').val(),
        type: "POST",
        beforeSend: function() {
            $('#contentcm').val('Đang gửi bình luận, vui lòng chờ!!!');
        },
        success: function(response) {
            $('#contentcm').val('');
            loadComment();
            //setTimeout("$.fn.colorbox.close()", 1500);
        }
    });
}

$('#app-rating').raty({
    	half:  true,
    	start: 0,
        <?php if(!$voted): ?>
		click: function(score, evt) {
			voteApp(score, '<?=$app->app_id ?>');
		},
        <?php endif;?>
    	readOnly: false
});
$('.stars0').raty({
    half:  true,
    start: <?php if($app->vote == 0){echo '0';}else{echo $app->vote_score/$app->vote;} ?>,
    readOnly: true
});
$(document).ready(function(){
    if(logged == 1 && voted == 1) {
        $("#comment-form").show("slow");
    }
});
</script>
