/*
 * jquery-counter plugin
 *
 * Copyright (c) 2009 Martin Conte Mac Donell <Reflejo@gmail.com>
 * Dual licensed under the MIT and GPL licenses.
 *
 * http://docs.jquery.com/License
 *
 * IE fix by Andrea Cardinale <a.cardinale@webandtech.it> [23 September 2009]
 * IE fix added by Giguashvili, Levan <levangig@gmail.com> [04 April 2011]
 */
jQuery.fn.countdown = function(userOptions)
{
  // Default options
  var options = {
    stepTime: 60,
    // startTime and format MUST follow the same format.
    // also you cannot specify a format unordered (e.g. hh:ss:mm is wrong)
    format: "dd:hh:mm:ss",
    digitImages: 6,
    digitWidth: 16,
    digitHeight: 23,
    timerEnd: function(){},
    image: "digits.png"
  };
  var digits = [], interval;

  var getTime = function() {
    var result;

    dateNow = new Date();	//grab current date
    amount = options.targetDate.getTime() - dateNow.getTime();	//calc milliseconds between dates
    delete dateNow;

    // if time is already past
    if(amount < 0){
      result = "00:00:00:00";
    }
    // else date is still good
    else{
      days=0;hours=0;mins=0;secs=0;out="";

      amount = Math.floor(amount/1000);//kill the "milliseconds" so just secs

      days=Math.floor(amount/86400);//days
      amount=amount%86400;

      hours=Math.floor(amount/3600);//hours
      amount=amount%3600;

      mins=Math.floor(amount/60);//minutes
      amount=amount%60;

      secs=Math.floor(amount);//seconds

      if(days<10) out+= "0";
      out += days+":";
      
      if(hours<10) out+= "0";
      out += hours+":";

      if(mins<10) out+= "0";
      out += mins+":";

      if(secs<10) out+= "0";
      out += secs;

      return(out);
    }
  };

  // Draw digits in given container
  var createDigits = function(where) 
  {
    var c = 0;
    var tempStartTime = options.startTime;
    // Iterate each startTime digit, if it is not a digit
    // we'll asume that it's a separator
    for (var i = 0; i < options.startTime.length; i++)
    {
      if (parseInt(tempStartTime.charAt(i)) >= 0) 
      {
        elem = jQuery('<div id="cnt_' + i + '" class="cntDigit" />').css({
          height: options.digitHeight * options.digitImages * 10, 
          float: 'left', background: 'url(\'' + options.image + '\')',
          width: options.digitWidth});
        digits.push(elem);
        margin(c, -((parseInt(tempStartTime.charAt(i)) * options.digitHeight *
                              options.digitImages)));
        digits[c].__max = 9;
        // Add max digits, for example, first digit of minutes (mm) has 
        // a max of 5. Conditional max is used when the left digit has reach
        // the max. For example second "hours" digit has a conditional max of 4 
        switch (options.format[i]) {
          case 'h':
            digits[c].__max = (c % 2 == 0) ? 2: 9;
            if (c % 2 == 0)
              digits[c].__condmax = 4;
            break;
          case 'd': 
            digits[c].__max = 9;
            break;
          case 'm':
          case 's':
            digits[c].__max = (c % 2 == 0) ? 5: 9;
        }
        ++c;
      }
      else 
        elem = jQuery('<div class="cntSeparator"/>').css({float: 'left'})
                .text(tempStartTime.charAt(i));

	  where.append('<div>');
      where.append(elem);
	  where.append('</div>');
    }
  };
  
  // Set or get element margin
  var margin = function(elem, val) 
  {
    if (val !== undefined)
      return digits[elem].css({'marginTop': val + 'px'});

    return parseInt(digits[elem].css('marginTop').replace('px', ''));
  };

  // Makes the movement. This is done by "digitImages" steps.
  var moveStep = function(elem) 
  {
    digits[elem]._digitInitial = -(digits[elem].__max * options.digitHeight * options.digitImages);
    return function _move() {
      mtop = margin(elem) + options.digitHeight;
      if (mtop == options.digitHeight) {
        margin(elem, digits[elem]._digitInitial);
        if (elem > 0) moveStep(elem - 1)();
        else 
        {
          clearInterval(interval);
          for (var i=0; i < digits.length; i++) margin(i, 0);
          options.timerEnd();
          return;
        }
        if ((elem > 0) && (digits[elem].__condmax !== undefined) && 
            (digits[elem - 1]._digitInitial == margin(elem - 1)))
          margin(elem, -(digits[elem].__condmax * options.digitHeight * options.digitImages));
        return;
      }

      margin(elem, mtop);
      if (margin(elem) / options.digitHeight % options.digitImages != 0)
        setTimeout(_move, options.stepTime);

      if (mtop == 0) digits[elem].__ismax = true;
    }
  };

  jQuery.extend(options, userOptions);
  this.css({height: options.digitHeight, overflow: 'hidden'});
  if (!options.startTime) {
    options.startTime = getTime();
  }
  createDigits(this);
  interval = setInterval(moveStep(digits.length - 1), 1000);
};
