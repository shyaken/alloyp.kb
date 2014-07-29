<?php
    if ($apps != false) {
        $CI =& get_instance();
        $CI->load->model('app_model');
        foreach ($apps as $app) {
            $now = time();
            $filter = $now - (3 * 24 * 60 * 60);
            $ribbon = "";
            $updateTime = $app->last_update;
            if ($app->is_sticky==1) {
                $ribbon = "ribbon_hot";
            }
            if ($updateTime>$filter) {
                $ribbon = "ribbon_update";
            }
            $uploadTime = $app->upload_time;
            if ($uploadTime>$filter) {
                $ribbon = "ribbon_new";
            }
            
            $app->size = strip_tags($app->size);
            if(!$app->size) $app->size = 'unknown';
            
            $version = $CI->app_model->getLastVersion($app->app_id);
            $app->version = $version;
            
            $price = $CI->app_model->getTymPrice($app->app_id);
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
                        
            //app thumnail
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
            $now = time();
            if ($app->publish==1 && $app->promo_start <= $now && $now <= $app->promo_end) {
?>
<li class="store">
    <a href="<?=site_url('home/app/'.$app->app_id)?>">
        <span class="ribbon_sale"><b>-<?=$percent?>%</b></span>
        <span class="shadown"><img alt="list" src="<?=base_url().$appThumb?>" /></span>
        <span class="comment"><?=$app->app_name?></span>
        <span class="name"><?=$app->vendor?></span>
        <span class="download">
            <b class="iVers"><?=$app->version?></b>
            <b class="iSize"><?=$app->size?></b>
            <b class="iDown"><?=$app->download?></b>
        </span>
        <span class="sales">
              <?=$appPrice?> 
              <b class="tym<?=$color?>">♥</b>
              → 
              <?=$promoPrice?> 
              <b class="tym<?=$color?>">♥</b>
            [ <span id="time<?=$app->app_id?>" class="time"></span> ]
        </span>
        <span class="<?=$ribbon?>"></span>
    </a>
</li>   
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
<?php }}} ?>