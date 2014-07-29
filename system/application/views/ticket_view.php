<div class="findLink">
<?php
    $hosts = array(
        'mega.1280.com'   => 'FShare',
        'fshare.vn'       => 'FShare',
        '4share.vn'       => '4Share.vn',
		'dl.appstore.vn'  => 'Megashare',
        'megashare.vn'    => 'Megashare',
        'megaplus.vn'     => 'Megashare',
        'share.vnn.vn'    => 'Megashare',
        'mediafire.com'   => 'Mediafire',
        'zshare.net'      => 'ZShare',
        'megaupload.com'  => 'Megaupload',
        'rapidshare.com'  => 'Rapidshare',
        'fileape.com'     => 'Fileape',
        'filedude.com'    => 'Filedude',
        'hotfile.com'     => 'Hotfile',
        'filesonic.com'   => 'FileSonic',
        '4shared.com'     => '4Shared',
        'my.opera.com'    => 'MyOpera',
        'rapidshare.de'   => 'Rapidshare.de',
        'multiupload.com' => 'Multiupload',
        'filestore6.com'  => 'FileStore.com',
        'filestore8.com'  => 'FileStore.com',
        'filestore9.com'  => 'FileStore.com',
        'ipa.appstore.vn' => 'AppstoreVN'
    );
    $title = 'Unknown Mirror';
    $CI =& get_instance();
    $CI->load->model('app_model');
    foreach ($tickets as $ticket) {
	$app = $CI->app_model->getInfo($ticket['app_id']);
        $price = $CI->app_model->getTymPrice($ticket['app_id']);
        //khuyen mai app
        $now = time();
        if($app->promo_enable && $app->promo_start <= $now && $now <= $app->promo_end) {
            $price['price'] -= $app->promo_price;
        }
        $version = $this->app_model->getVersion($ticket['version']);
        if(!$version->price || $ticket['paid']) $xPrice  = 0;
        else $xPrice = $price['price'];
        $xType = $price['type'];
        $link = $ticket['link'];
        foreach ($hosts as $key => $val) {
            if (strpos($link, $key)) {
                $title = $val;
            }
        }
?>
<?php if($title != 'Megashare'):?>
<?php
$store = 'other';
if($title == 'FShare') $store = 'fshare';
?>
    <a href="javascript:;" onclick="beforeDownloadTicket('<?=$store?>','<?=$ticket["ticket_id"]?>',<?=$ticket["version"]?>,<?=$ticket["order"]?>, '<?=$xPrice?>', '<?=$xType?>');" style="text-align:left;"><span class="link">Link tải <?=$ticket['order']?> từ <?=$title?></span><span class="point"><img src="<?=base_url()?>images/button/pd.png" alt="" /></span></a>
<?php endif?>
<?php } ?>
</div>
