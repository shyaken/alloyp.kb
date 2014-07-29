var jQT = new $.jQTouch({
  icon: 'jqtouch.png',
  addGlossToIcon: true,
  startupScreen: 'jqt_startup.png',
  statusBar: 'black',
  preloadImages: []	
});

$(function(){
	jQT.addAnimation({
      name: 'slide',
      selector: '.revealme'
  });
});


