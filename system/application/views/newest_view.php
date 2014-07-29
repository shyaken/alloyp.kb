<script>
    var page = 0;
    function showMore() {
        $('#showmore').hide();
        $('#loader').show();
		page++;
        $.ajax({
            url: "<?=site_url('home/moreNewest')?>/" + page,
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
        <li><a href="<?=site_url('home/topDownload')?>">Tải nhiều</a></li>
		<li><a href="<?=site_url('home/newest')?>">Mới nhất</a></li>
        <li><a href="<?=site_url('home/promotion')?>">Khuyến mãi</a></li>
	</ul>
</div>
    
<div class="searchbox">
	<div class="searchbox-in">
        <form action="<?=site_url('home/searchApp')?>" method="post" id="formsearch">
			<fieldset><input name="keyword" id="search" placeholder="Nhập từ khóa để tìm kiếm" type="text" />
			<input id="submit" type="submit" value="Tìm kiếm" /></fieldset>
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
        <a href="<?=site_url('home/newest')?>">Mới nhất</a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>    
	<div id="appdetail"></div>
    <ul class="pageitem">
        <?=$apps?>
        <!-- More Games -->
        <li id="newitem"></li>
		<li class="store">
            <div id="loader" style="display: none; text-align: center; margin: 5px;"><img src="<?=base_url()?>/images/loading.gif"/></div>
            <a id="showmore" href="#pageitem"><span class="more" onclick="showMore();">Xem tiếp 25 apps...</span></a>
        </li>
	</ul>

<div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/newest')?>">Mới nhất</a>
    </div>
    <div class="rightpath">
        <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
    </div>
</div>    
</div>
