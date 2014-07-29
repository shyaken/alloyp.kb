<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="index,follow" name="robots" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link href="<?=base_url()?>/pics/homescreen.gif" rel="apple-touch-icon" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=0.6667, user-scalable=no" name="viewport" />
<link href="<?=base_url()?>/css/style.css" media="screen" rel="stylesheet" type="text/css" />
<script src="<?=base_url()?>/js/functions.js" type="text/javascript"></script>
<script src="<?=base_url()?>/js/jquery.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=base_url()?>/js/jquery.raty-1.4.0/js/jquery.raty.js"></script>
<title>appstore.vn</title>
<link href="<?=base_url()?>/pics/startup.png" rel="apple-touch-startup-image" />
<meta content="" name="keywords" />
<meta content="" name="description" />
</head>

<body>

<div id="topbar">
	<div id="leftnav">
		<a href="<?php echo site_url('home/app/' . $appid)?>">Back</a></div>
	<div id="title">
		Bình luận</div>
    <div id="rightnav">
		<?php
            $CI =& get_instance();
            $CI->load->model('category_model');
            $category = $CI->category_model->getInfo($app->category);
            $url = site_url('home/category/'.$category->category_id);
        ?>
        <a href="<?=$url?>"><?=$category->category_name?></a>
        </div>
</div>
<div id="comment_app">

<style>
#showmorelink a {color:black;font-size:12px;}
</style>
<script>
var voted = 0;
<?php if($voted) echo "voted = 1;";?>
var logged = 0;
<?php if($logged) echo "logged = 1;";?>;

var page = 0;
function showMore() {
	var x = page + 1;
	window.location.hash = '#' + x;
	$("#showmorelink").hide();
	$("#showmoreloading").show();
	page++;
	$.ajax({
		url: "<?php echo site_url('home/moreCommentInApp/' . $appid)?>/" + page,
		type: "POST",
		success: function(data) {
			$("#commentslist").append(data);
			$("#showmorelink").show();
			$("#showmoreloading").hide();
		} 
	});	
}

// vote app
function voteApp(score, appid) {
	if(logged == 0) {
		alert('Bạn phải đăng nhập trước khi bình chọn');
        $.fn.colorbox({width:"100%", height: "100%", inline:true, href:"#loginbox"});           
		return;
	}
	$.ajax({
		url: "<?php echo site_url('home/vote') ?>/" + appid + "/" + score,
		type: "POST",
		beforeSend: function() {
			$('comment-form-msg').html('<b>Đang gửi thông tin bình chọn!</b>');
		},
		success: function(data) {
			$('#app-rating').html('<b>Đã bình chọn!</b>');
			$('#comment-form-msg').hide('slow');
			$('#comment-form').show('slow');
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
	return true;
}

$(document).ready(function(){
    curPage = window.location.hash;
    page = 0;
    currentPage = curPage.substr(1);
    if (currentPage>0) {
        for (i=0; i<currentPage; i++) {
            showMore();
        }
    }
	// an thong bao thanh cong
	var timeout = setTimeout('$("#postsuccess").hide();', 5000);

	// hiển thị vote
	$('#app-rating').raty({
		half:  true,
		start: <?php if($app->vote_score == 0 && $app->vote ==0){echo "0";} else{echo$app->vote_score/$app->vote;}?>,
	<?php if(!$voted) { ?>
		click: function(score, evt) {
			voteApp(score, '<?=$app->app_id ?>');
		}
	<?php } else { ?>
		readOnly: true
	<?php } ?>
	});
    
});
</script>

<form name="commentform" action="<?php echo site_url('home/comment/' . $appid)?>" method="post">
	<ul>
	<?php 
		$CI =& get_instance();
		$CI->load->model('user_model');
		$CI->load->model('app_model');
	?>
	<?php 
		if(isset($success)) {
			echo '<li id="postsuccess">' . $success . '</li>';	
		}
	?>
	<?php if($comments) { ?>
		<?php foreach($comments as $comment):?>
		<?php 
			$vote = $CI->app_model->getVote(array('app_id'=>$comment->app_id, 'user_id'=>$comment->user_id));
		?>
		<script>
		$(document).ready(function(){
			$('#comment<?=$comment->comment_id?>').raty({
				half:  true,
				readOnly: true,
				start: <?=$vote->rate?>
			});
		});
		</script>
		<li class="commlist">
		<span class="comment" id="comment<?=$comment->comment_id?>"></span>
		<span class="starcomment">by  
		<?php 
			$user = $CI->user_model->getUserById($comment->user_id);
			echo $user->username; 
		?>
		on
		<?=$comment->post_date?></span> <span class="detailcomment"><?=$comment->content?></span> 
		</li>
		<?php endforeach;//end comment?>
		<span id="commentslist"></span>
		<?php if(count($comments == 25)) { ?>
		<span id="showmorecomment">
			<div id="showmorelink" style="margin-top:5px; padding-top:10px;">
				<center><a href="javascript:showMore();">Xem tiếp 25 bình luận</a></center>
			</div>	
			<div id="showmoreloading" style="display:none; margin-top:5px; padding-top:5px;"><center><img src="<?php echo base_url()?>/images/loading.gif" align="center" /></center></div>
		</span>
		<?php } ?>
        <?php } else { ?>
            <li class="commlist">
                Chưa có bình luận nào!
            </li>
        <?php }?>	
		<div class="send-comment">
			<h4>Đánh giá </h4>
			<span id="app-rating"></span>
			
			<p id="comment-form-msg"><br />Vui lòng bình chọn ứng dụng trước khi bình luận!</p>
			<div id="comment-form" style="display:none;">
			<textarea onblur="clickrecall(this,'text entry')" onclick="clickclear(this, 'text entry')" name="contentcm" id="contentcm"></textarea>
			<input type="submit" name="postcm" value="Gửi bình luận" style="display:block;height: 50px;font-size: 15px;font-weight:bold;border:1px #b8b8b8 solid;-webkit-border-radius: 5px;text-align: center;background:-webkit-gradient(linear,0% 0%,0% 100%,from(#ffffff),color-stop(96%,#d9d9d9),color-stop(98%,#b8b8b8),to(#f1f2f2));color: #000;text-shadow:#ffffff 0 1px 0;padding:0 20px;margin:0 auto;clear:both;float:none;" onclick="return canComment();" />
			</div>
		</div>
		<script>
		$(document).ready(function(){
			if(logged == 1 && voted == 1) {
				$("#comment-form-msg").hide("slow");
				$("#comment-form").show("slow");
			}
		});
		</script>
	</ul>
	</form>
</div>
</body>

</html>
