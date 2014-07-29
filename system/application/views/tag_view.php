<script>
    var page = 0;
    function showMore() {
        $('#showmore').hide();
        $('#loader').show();
        page++;
        $.ajax({
            url: "<?=site_url('home/moreTagResult/'.$keyword.'/'.$filter)?>/" + page,
            success: function(data){
                window.location.hash = '#' + page;
                $('#newitem').append(data);
                $('#loader').hide();
                $('#showmore').show();
            }
        });
    }

    $(document).ready(function(){
        curPage = window.location.hash;
        page = 0;
        currentPage = curPage.substr(1);
        if (currentPage>0) {
            for (i=1; i<=currentPage; i++) {
                showMore();
            }
        }
    });
</script>
<div id="tabmenu">
	<ul class="individual">
        <li><a <?php if ($filter=='download') echo 'id="current";'?> href="<?=site_url('home/searchApp/'. $keyword .'/download')?>">Tải nhiều nhất</a></li>
		<li><a <?php if ($filter=='upload_time') echo 'id="current";'?> href="<?=site_url('home/searchApp/'. $keyword .'/upload_time')?>">Mới nhất</a></li>
        <li><a href="<?=site_url('home/promotion')?>">Khuyến mãi</a></li>
	</ul>
</div>
<div class="searchbox">
	<div class="searchbox-in">
        <form action="<?=site_url('home/searchApp')?>" method="post">
			<fieldset><input name="keyword" id="search" placeholder="search" type="text" />
			<input id="submit" type="hidden" /></fieldset>
		</form>
	</div>
</div>
<div class="scrolltop">
	<marquee behavior="scroll">
	<?php if($headertext) echo $headertext->code?>
	</marquee> </div>
<div id="content">
<div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/tag/'.$keyword)?>">Tag '<?=$keyword?>'</a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>    
	<div id="appdetail"></div>
    <ul class="pageitem">
        <?php if ($apps!='0') { echo $apps;?>
        
        <li id="newitem"></li>
        <li class="store">
            <div id="loader" style="display: none; text-align: center; margin: 5px;"><img src="<?=base_url()?>/images/loading.gif"/></div>
            <a id="showmore" href="#pageitem"><span class="more" onclick="showMore();">Xem tiếp...</span></a>
        </li>
        <?php } ?>
        <div class="pathbar path">
            <div class="leftpath"> 
                <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
                <a href="<?=site_url('home/tag/'.$keyword)?>">Tag '<?=$keyword?>'</a>
            </div>
            <div class="rightpath">
                <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
            </div>
        </div>
        <?php if(!$apps) {?>
        <span style="text-align: center; color: red; padding: 5px;">Không tìm thấy kết quả phù hợp</span>
        <?php } ?>
        <!-- More Games -->
        
	</ul>
</div>
