<script>
    var page = 0;
    function showMore() {
        $('#showmore').hide();
        $('#loader').show();
        page++;
        $.ajax({
            url: "<?=site_url('home/moreAppInCategory/'.$category)?>/" + page + "/<?=$filter?>",
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
            for (i=1; i<currentPage; i++) {
                showMore();
            }
        }
    });

</script>
<div id="tabmenu">
	<ul class="individual">
        <li><a <?php if ($filter=='download') echo 'id="current";'?> href="<?=site_url('home/category/'.$category.'/0/download')?>">Tải nhiều</a></li>
		<li><a <?php if ($filter=='is_sticky') echo 'id="current";'?> href="<?=site_url('home/category/'.$category.'/0/is_sticky')?>">Hot nhất</a></li>
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
	</marquee> 
</div>
<div id="content">
<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/category/'.$category)?>"><?=$category_name?></a>
    </div>
    <div class="rightpath">
        <a href="#bottompage" name="toppage"><img src="<?=base_url()?>images/pagebottom.png"></a>
    </div>
  </div>
</div>
    <div id="appdetail"></div>
	<ul class="pageitem">
        <?=$apps?>
        <!-- More Games -->
        <li id="newitem"></li>
		<li class="store">
            <div id="loader" style="display: none; text-align: center; margin: 5px;"><img src="<?=base_url()?>/images/loading.gif"/></div>
            <a id="showmore" href="javascript:showMore();"><span class="more">Xem tiếp...</span></a>
        </li>
	</ul>

<div class="title_app">
  <div class="pathbar path">
    <div class="leftpath"> 
        <a href="<?=base_url()?>"><img alt="home" src="<?=base_url()?>images/home.png" /></a>
        <a href="<?=site_url('home/category/'.$category)?>"><?=$category_name?></a>
    </div>
    <div class="rightpath">
        <a href="#toppage" name="bottompage"><img src="<?=base_url()?>images/pagetop.png"></a>
    </div>
  </div>
</div>    
</div>
