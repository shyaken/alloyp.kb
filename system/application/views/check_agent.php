
<?php
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if (!preg_match('@iPhone|iPod|iPad@', $agent) && preg_match('@Android|android@', $agent)) {
		echo '<script>window.location.href="http://appstore.vn/a/index.php/home/topDownload";</script>';
	} else if (!preg_match('@iPhone|iPod|iPad@', $agent) && strpos($agent, 'BlackBerry')) {
		echo '<script>window.location.href="http://appstore.vn/b/index.php/home/topDownload";</script>';
	} else if (!preg_match('@iPhone|iPod|iPad@', $agent)){
		echo '<script>window.location.href="http://appstore.vn/j/index.php/home/topDownload";</script>';
	} else {
	}
?>
<script src="<?=base_url()?>/js/away.js" type="text/javascript"></script>
