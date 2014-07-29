<?php 
if($events):
for($i=0; $i<count($events); $i++) {
    $expired = $events[$i]->expired_time;
    $class = 'endPlay';
    $url = '#';
    $txt = 'Đã kết thúc';
    $util = 0;
    if($expired > time()) {
        $class = 'goPlay';
        $url = site_url('home/event/'.$events[$i]->event_id);
        $txt = 'Tham gia';
        $util = $events[$i]->expired_time - time();
    }
?>
<script type="text/javascript">
$(function () {
	$('#defaultCountdown<?=$events[$i]->event_id?>').countdown({until: <?=$util?>});
});
</script>
<li>
  <div class="thumbGift line">
    <div class="nameGift"><?=$events[$i]->name?></div>
    <div class="itemGift">
      <div class="status"><a href="<?=$url?>" class="<?=$class?>"><?=$txt?></a></div>
      <div class="infoGift" >
        <div class="time">
          <div class="titlebx">Thời gian còn lại</div>
          <div id="defaultCountdown<?=$events[$i]->event_id?>"></div>
        </div>
        <div class="numsPlay">Hiện có <b><?=$events[$i]->playing?></b> lượt chơi<br/>
          Tài trợ: <?=$events[$i]->sponsor?></div>
      </div>
      <div class="picGift"><img alt="list" src="<?=$events[$i]->image?>" /></div>
      <div class="social">
        <div class="social-in">
            <a href="#">
                <img alt="list" src="<?=base_url()?>images/logos/facebook.png" />
            </a>
            <a href="#">
                <img alt="list" src="<?=base_url()?>images/logos/googleplus.png" />
            </a>
            <a href="#">
                <img alt="list" src="<?=base_url()?>images/logos/twitter.png" />
            </a>
        </div>
      </div>
    </div>
  </div>
</li>

<?php 
}
endif;
?>