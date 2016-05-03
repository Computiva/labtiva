/*************************************************
      Single Site White JS 
      @author Fabio Mangolini
      http://www.responsivewebmobile.com
**************************************************/
jQuery(document).ready(function(){
	
//GLOBALS 
	//Navigation variables
	var timeline = new Array(); //array containing the menu anchor
	var offsets = new Array(); //array with the offset of each section from the top
	var current;

	//Section dimensions
	var sectionWidth = 0;
	var sectionHeight = 0;

	var page = $('.page');
	

	sizeSection();
	generateNavigationTimeline();
	var indicator = closestSection($(window).scrollTop());
	navSelect(timeline[indicator]);
	$('.scroll').tooltip({placement: 'right'});

	console.log(offsets);

/**********************************
			EVENTS
***********************************/

	//CLICK
    $("a.scroll").bind('click', function(e){
    	e.preventDefault();

    	current = $(this).attr('href'); //set the current active page
    	scrollToElement(current, 500); //scroll to the clicked element
    });

    //Scrolls to Top when clicked
	$('#scrollToTop').click(function(){
	    $("html, body").animate({ scrollTop: 0 }, 700);
	    return false;
	});

    //RESIZE
    $(window).bind('resize', function() {
		sizeSection();
	});

	//SCROLL	
	$(window).bind('scroll', function() {
		//Calls the function to retrieve the closest section to top and and highlight it
		var indicator = closestSection($(window).scrollTop());
		navSelect(timeline[indicator]);	

		//show or hide the scroll to top button depending on the position from top
		if ($(this).scrollTop() > 300) {
	        $('#scrollToTop').fadeIn('fast');
	    } else {
	        $('#scrollToTop').fadeOut('fast');
	    }  
	});

	


/**********************************
			FUNCTIONS
***********************************/
	//Gets the closest section to Top
	function closestSection(currentPos) {
		y = currentPos; //the current position of scroll passed from the scroll event.
        var controls = []; //new array to contain abs values of distance.
        console.log(y);

        $.each(offsets, function(){
        	controls.push(Math.abs(this - y));       
            //stores the abs value of the distance from current scroll position to
            //offsetTop of each section.
        })
        min = Math.min.apply( Math, controls ); //which abs value is smallest?

        return $.inArray(min, controls); //returns the array index of lowest abs value.
    }

    //Selects and deselect menu voices
	function navSelect(selector) {
    	$('.scroll').removeClass('enabled');
    	$('a[href="'+selector+'"].scroll').addClass('enabled');
    }

	//Generate the Array with Navigation Timeline
	function generateNavigationTimeline() {
		$(page).each(function(index) {
			timeline[index] = '#'+$(this).attr('id');
			offsets[index] = Math.round($(this).position().top);
		})
	}

    //Sets the size of each section depending on window dimensions
    function sizeSection() {
    	sectionHeight = $(window).height();
    	sectionWidth = $(window).width();
    	
    	$(page).css({'min-height': sectionHeight}); //sets the min-height of all the sections
    }

    //Scrolls to the clicked element
    function scrollToElement(selector, time) {
	    time = typeof(time) != 'undefined' ? time : 2000;
	    element = $(selector);
	    offset = element.position().top + $(selector).scrollTop();

	    $('html, body').animate({
	        scrollTop: offset,
	    }, time);
	}
});