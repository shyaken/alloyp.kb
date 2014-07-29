<?php if($comments) { ?>
		<?php foreach($comments as $comment) { ?>
		<?php 
			$CI =& get_instance();
			$CI->load->model('user_model');
			$CI->load->model('app_model');
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
		<?php } //end comment?>
		
<?php } // end if ?>	