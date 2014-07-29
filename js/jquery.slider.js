// Set the initial height
var sliderHeight = "75px";

$(document).ready(function(){
	// Show the slider content
	$('.slider').show();
	
	$('.slider').each(function () {
		var current = $(this);
		current.attr("box_h", current.height());
	});
	
	$(".slider").css("height", sliderHeight);
});

// Set the initial slider state
var slider_state = "close";

function sliderAction()
{
	if (slider_state == "close")
	{
		sliderOpen();
		slider_state = "open"
		$(".slider_menu").html('<a href="#" onclick="return sliderAction();">Thu nhỏ</a>');
	}
	else if (slider_state == "open")
	{
		sliderClose();
		slider_state = "close";
		$(".slider_menu").html('<a href="#" onclick="return sliderAction();">Xem thêm...</a>');
	}
	
	return false;
}

function sliderOpen()
{
	var open_height = $(".slider").attr("box_h") + "px";
	$(".slider").animate({"height": open_height}, {duration: "slow" });
}

function sliderClose()
{
	$(".slider").animate({"height": sliderHeight}, {duration: "slow" });
}