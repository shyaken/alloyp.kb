<?php
    $CI =& get_instance();
    $CI->load->model('app_model');
?>
<div class="list-download">
	<ul>
        <?php 
            $count = 0;
            if(isset($versions[0])) {
                $price = $CI->app_model->getTymPrice($versions[0]->app_id);
            } else {
                $price = false;
            }
            $count = 0;
            foreach ($versions as $version) {
                $appx = $CI->app_model->getInfo($version->app_id);
                $now = time();
                //khuyen mai
                if($appx->promo_enable && $appx->promo_start <= $now && $now <= $appx->promo_end) {
                    if($count == 0) $price['price'] -= $appx->promo_price;
                    $count ++;
                }
                /*
                foreach ($tickets as $ticket) {
                    if ($ticket['version']==$version->app_version_id) {
                        $link = $ticket['link'];
                        foreach ($hosts as $key => $val) {
                            if (strpos($link, $key)) {
                                $title = $val;
                            }
                        }
                
            
            /*
            foreach ($versions as $version) {
            //$links = explode('@@', $version->link);
                //foreach ($tickets as $ticket) {
                $ticket = $tickets[$count++];
                foreach ($hosts as $key => $val) {
                    if (strpos($ticket['link'], $key)) {
                        $title = $val;
                    }
                }
            //<a onclick="downloadTicket('<?=$ticket['ticket_id']?>',<?=$ticket['version']?>,<?=$ticket['order']?>);" href="#">
            */
        ?>
		<li>
            <a onclick="getTickets(<?=$version->app_version_id?>);">
            <span class="vers"></span>
            <span class="name"><font size='2'>Link tải của <?=$version->version?></font></span>
			<span class="desc" id="upload_by<?=$version->app_version_id?>">Uploaded by <b><?=$appx->uploader?></b></span>
            <span class="desc"></span>
            <span class="loading-bar<?=$version->app_version_id?>" style="display:none;font-size:12px;">Đang tìm link</span>
            <span class="showprice<?=$version->app_version_id?>" style="display:inline;font-size:12px;">
                <?php 
                    if ($price['type']=='t1') {
                        $color = 'red';
                    } elseif ($price['type']=='t2') {
                        $color = 'purple';
                    } elseif ($price['type']=='t3') {
                        $color = 'green';
                    } elseif ($price['type']=='t4') {
                        $color = 'yellow';
                    } else {
                        $color = 'red';
                    }
                    if($price && $price['price'] && $version->price) {
                ?>
                <script>//$('#upload_by<?=$version->app_version_id?>').hide();</script>
                <?=$price['price']?> <font color="<?=$color?>">♥</font></span>
                <?php } else { ?>
                Free
                <?php } ?>
            </span>
            </a>
        </li>
        <div id="ticket-<?=$version->app_version_id?>"></div>
        <?php } ?>
        
	</ul>
</div>
