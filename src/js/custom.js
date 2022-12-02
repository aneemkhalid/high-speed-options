
jQuery(document).ready(function() {
    // Provider Plan Detail 
    var internetTab = document.getElementById("internet-tab");
    var tvTab = document.getElementById("tv-tab");
    var bundleTab = document.getElementById("bundle-tab");
    if(internetTab == null && tvTab != null && bundleTab != null){
        $("#tv-tab").addClass("active");
        $("#tv.tab-pane").addClass("show active");
    } else if(internetTab == null && tvTab == null && bundleTab != null){
        $("#bundle-tab").addClass("active");
        $("#bundle.tab-pane").addClass("show active");
    } else if(internetTab == null && bundleTab == null && tvTab != null){
        $("#tv-tab").addClass("active");
        $("#tv.tab-pane").addClass("show active");
     }

   // Filters for Explore Topics
     $( ".explore.topics .filter-tag").each(function(index) {
        $(this).on("click", function(){
            if($(this).hasClass('blue')){
                $(this).removeClass('blue');
                $(this).children('span').hide();
            }
            else{
                $(this).addClass('blue');
                $(this).children('span').css('display', 'inline-block');
            }
            if(($('#resource-internet').hasClass('blue')) && ($('#resource-tv').hasClass('blue')) && ($('#resource-streaming').hasClass('blue'))){
                $('#resource-topic-select').addClass('blue tags');
                $('#resource-topic-select').children('span').css('display', 'inline-block');
            }
            else{
                $('#resource-topic-select').removeClass('blue tags');
                $('#resource-topic-select').children('span').hide();
            }
        });
    });
    $('#resource-topic-select').on("click", function(){
        
        if($(this).hasClass('blue')){    
            $('.explore.topics .filter-tag').removeClass('blue');
            $('.explore.topics .filter-tag').children('span').hide();
        }
        else{
            $('.explore.topics .filter-tag').addClass('blue');
            $('.explore.topics .filter-tag').children('span').css('display', 'inline-block');
        }
    });

    // Filters for Explore Formats
    $( ".explore.formats .filter-tag").each(function(index) {
        $(this).on("click", function(){
            if($(this).hasClass('green')){
                $(this).removeClass('green');
                $(this).children('span').hide();
            }
            else{
                $(this).addClass('green');
                $(this).children('span').css('display', 'inline-block');
            }
            if(($('#resource-guide').hasClass('green')) && ($('#resource-video').hasClass('green')) && ($('#resource-news').hasClass('green')) && ($('#resource-insights').hasClass('green')) && ($('#resource-howto').hasClass('green'))){
                $('#resource-format-select').addClass('green tags');
                $('#resource-format-select').children('span').css('display', 'inline-block');
            }
            else{ 
                $('#resource-format-select').removeClass('green tags');
                $('#resource-format-select').children('span').hide();
            }
        });
    });
    $('#resource-format-select').on("click", function(){
        if($(this).hasClass('green')){    
            $('.explore.formats .filter-tag').removeClass('green');
            $('.explore.formats .filter-tag').children('span').hide();
        }
        else{
            $('.explore.formats .filter-tag').addClass('green');
            $('.explore.formats .filter-tag').children('span').css('display', 'inline-block');
        }
    });
    
    
    //add gclid to all internal links on page
    addGclidToInternalLinks();

    //add gclid to all search forms
    addGclidToSearch();

    // Pass gclid param to cta links
    populateAffClickID();

    //  jQuery('.posts_slider').slick({
    //     slidesToShow: 3,
    //     slidesToScroll: 3,
    //     speed:0,
    //     cssEase: 'linear',
    //     infinite: true,
    //     arrows: true,
    //     dots: false,
    //     draggable: false,
    //     responsive: [
    //         {
    //             breakpoint: 992,
    //             settings: {
    //                 slidesToShow: 2,
    //                 slidesToScroll: 2
    //             }
    //         },
    //         {
    //             breakpoint: 641,
    //             settings: {
    //                 slidesToShow: 1,
    //                 slidesToScroll: 1
    //             }
    //         }
    //     ]
    // });

    $('.zip_search .change-location').on('click', function(){
        $(this).hide();
        $('.zip_search .zip_search_form_wrapper').show();
    });
    $('.submit-zip').on('click', function(){
        $(this).closest('.zip_search_form').submit();
    });
    $('.zip_search_nav .nav-link').on('click', function(){ 
        var link_id = $(this).attr('id');
        var param = link_id.replace('-search-tab', '');
        var queryParams = new URLSearchParams(window.location.search);
        queryParams.set('type', param);
        history.replaceState(null, null, "?"+queryParams.toString());
        var navHref = $(this).attr("href");
        navHref = navHref.replace('#', '');
        $(".sort-by-dropdown .dropdown-btn span").addClass('placeholder-text').text('Sort By');
        $(".sort-by-dropdown button").removeClass('show');
        $("button[data-dropdownType='" + navHref +"']").addClass('show');
        if (param == 'tv'){
            $(".provider-type-header").text('TV');
        } else {
            $(".provider-type-header").text(param);
        }
    });
    $('input[name=zip]').on('input', function() {
        $(this).attr('placeholder', 'Search by ZIP');
        $(this).removeClass('zip-error');
    });

    // $('.zip_search, .locations-main').on('click', '.zip_search_overview .provider-box-row .provider-more-info button', function() {
    //     var toggle = $(this).find('.detail-text').text() == 'View Details' ? 'Hide Details' : 'View Details';
    //     $(this).find('.detail-text').text(toggle);

    // });
    
    $('.sort-by-dropdown .dropdown-menu button').click(function () {
        var sortBy = $(this).attr('data-dataGroup');
        var dropdownType = $(this).attr('data-dropdownType');
        var wrapper = $('#typeTabContent .'+dropdownType);
        var sortOrder = $(this).attr('data-sortOrder');

        $('.sort-by-dropdown .dropdown-btn span').text($(this).text()).removeClass('placeholder-text');
        $('.sort-by-dropdown .dropdown-menu button').removeClass('sort-active');
        $(this).addClass('sort-active');
        if (sortOrder == 'highest'){
            wrapper.find('.zip-container').sort(function(a, b) {
                if(a.getAttribute(sortBy)==='N/A'){
                    return 1;
                } else if(b.getAttribute(sortBy)==='N/A'){
                    return -1;
                } else {
                    return +b.getAttribute(sortBy) - +a.getAttribute(sortBy);
                }
            })
            .appendTo(wrapper);
        } else if (sortOrder == 'lowest'){
            wrapper.find('.zip-container').sort(function(a, b) {
                if(a.getAttribute(sortBy)==='N/A'){
                    return 1;
                } else if(b.getAttribute(sortBy)==='N/A'){
                    return -1;
                } else {
                    return +a.getAttribute(sortBy) - +b.getAttribute(sortBy);
                }
            })
            .appendTo(wrapper);
        }
    });

    //Cookie Notice
    $('.cookie-bar-close').click(function(){
        $('#cookie-law-info-bar').slideUp();
        $('#cookie-law-info-again').slideDown();
    });

    //zip search modal
    $('.zip-popup-btn').click(function(){
        var popupID = $(this).attr('data-target');
        $(popupID+' .zip_search_input').focus();
        $(popupID).on('shown.bs.modal', function (event) {
            $(popupID+' .zip_search_input').focus();
        });
    });
    $('.modal-zip-search-input').on('input', function(e){
        var validZipcode = isValidZip($(this).val());
        if(isMobile && validZipcode) {
            $(this).parent().submit();
        }
    });

    //toc active state
    $("#ez-toc-container .ez-toc-list li:first-child").addClass("active");
    
    var addClassOnScroll = function () {
        var windowTop = jQuery(window).scrollTop();
        jQuery('.commercial_page_left_content h2 span[id]').each(function (index, elem) {
            var offsetTop = jQuery(elem).offset().top;
            var outerHeight = jQuery(this).outerHeight(true);
            var widnowsHeight = jQuery( window ).height();

            if( windowTop >= offsetTop - 100) {
                var elemId = jQuery(elem).attr('id');
                jQuery(".ez-toc-list li.active").removeClass('active');
                jQuery(".ez-toc-list li a[href='#" + elemId + "']").parent().addClass('active');
            }
        });
    };

    jQuery(function () {
        jQuery(window).on('scroll', function () {
            addClassOnScroll();
        });
        jQuery('body').on('click','.ez-toc-list > li',function(){
            addClassOnScroll();
        });
    });

    if (toc_toggle_on_page == null ) {
        var toc_toggle_on_page = 0;
        
    }
    
          
          if (toc_toggle_on_page === 1){
              
              
              var addClassOnScroll2 = function () {
					var windowTop = jQuery(window).scrollTop();
					jQuery('.entry-content h2 span[id]').each(function (index, elem) {
						var offsetTop = jQuery(elem).offset().top;
						var outerHeight = jQuery(this).outerHeight(true);
						var widnowsHeight = jQuery( window ).height();

						if( windowTop >= offsetTop - 100) {
							var elemId = jQuery(elem).attr('id');
							jQuery(".toc_wrapper .ez-toc-list li.active").removeClass('active');
							jQuery(".toc_wrapper .ez-toc-list li a[href='#" + elemId + "']").parent().addClass('active');
						}
					});
				};

				jQuery(function () {
					jQuery(window).on('scroll', function () {
						addClassOnScroll2();
					});
					jQuery('body').on('click','.toc_wrapper .ez-toc-list > li',function(){
						addClassOnScroll2();
					});
				});
              
              
              
              jQuery('.main-content .disclaimer-block').insertAfter('.main-content #ez-toc-container');              
            }  
    
    
    var s = $(".find-providers a");				   
	$(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if (windowpos >= 1000) {
			s.addClass("activate");
		} else {
			s.removeClass("activate");	
		}
	});

    $(document).on('click', '.zip-popup-btn', function(){
        $('.single-provider .product-type-check').prop('checked', false);
        var current_clicked = $(this).attr('data-prodtype');
        $('.single-provider .check-' + current_clicked).prop('checked', true);
    });

    $('.single-provider .plans-page').on('click', function(e) {
        var url = window.location.href;
        var params = window.location.search.substring(1);

        var urlParts = url.split('?');
        var urlParams = new URLSearchParams(urlParts[1]);

       
            //if($(this).hasClass('plans-page')) {
                //console.log(true)
        e.preventDefault();
        if(params) {
            if(!urlParams.has("plans")) {
                //urlParams.append('plans', 'show');
                url = urlParts[0] +  '?plans=show&' + urlParams.toString();
            }
        }
        else {
            urlParams.append('plans', 'show');
            url = urlParts[0] +  '?' + urlParams.toString();
        }
            //}
            // else {
            //     e.preventDefault();
            //     if(params) {
            //         if(urlParams.has("plans")) {
            //             urlParams.delete("plans")
            //         }
            //         if(urlParams.values().next().done) {
            //             url = urlParts[0];
            //         }
            //         else {
            //             url = urlParts[0] +  '?' + urlParams.toString();
            //         }
            //     }
            // }

        window.location.assign(url);
    })


    $(".comparison-features-block .show-more").click(function() {
      
        var $el = $(this);
        var $parent  = $el.parent();
   
        $parent
        .animate({
              "max-height": 9999
        }, 'slow');
        $parent.removeClass('with-after-content');
        $el.fadeOut();

        return false;

    });
    
});

if (!window.sortTable) {
    window.sortTable = (n) => {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable");
        switching = true;
        //Set the sorting direction to ascending:
        dir = "asc";
        /*Make a loop that will continue until
        no switching has been done:*/
        while (switching) {
            //start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /*Loop through all table rows (except the
            first, which contains table headers):*/
            for (i = 1; i < (rows.length - 1); i++) {
            //start by saying there should be no switching:
            shouldSwitch = false;
            /*Get the two elements you want to compare,
            one from current row and one from the next:*/
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /*check if the two rows should switch place,
            based on the direction, asc or desc:*/
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                //if so, mark as a switch and break the loop:
                shouldSwitch= true;
                break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    //if so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                    }
                }
            }
            if (shouldSwitch) {
            /*If a switch has been marked, make the switch
            and mark that a switch has been done:*/
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            //Each time a switch is done, increase this count by 1:
            switchcount ++;      
            } else {
            /*If no switching has been done AND the direction is "asc",
            set the direction to "desc" and run the while loop again.*/
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
};

// Comparison Providers Horizontal Scroll Table Logic
jQuery(document).ready($ => {
    ($ => {
        // Cache
        const $rightScrollArrow = $('.right-scroll');
        const $leftScrollArrow = $('.left-scroll');
        // DataTable Cache
        const $dtFrame = $('.compare-providers-table-scroll-main-container');
        const $dTable = $('.compare-providers-table-scroll-main-container .compare-providers-table-scroll');

        /**
         * Toggles visibility of scroll arrows
         */
        const showHideArrows = () => {
            const dtFrameLeft = Math.ceil($dtFrame.offset().left);
            const dtFrameRight = Math.ceil(dtFrameLeft + $dtFrame.outerWidth());
            const dtScrollLeft = Math.ceil($dtFrame.scrollLeft());
            const dTableLeft = Math.ceil($dTable.offset().left);
            const dTableRight = Math.ceil(dTableLeft + $dTable.outerWidth());

            if( dTableRight > dtFrameRight && !$rightScrollArrow.is(':visible') ) {
                $rightScrollArrow.fadeIn(500);
            } else if( $rightScrollArrow.is(':visible') && dTableRight <= dtFrameRight ) {
                $rightScrollArrow.fadeOut(500);
            }

            if( dtScrollLeft > 0 && !$leftScrollArrow.is(':visible') ) {
                $leftScrollArrow.fadeIn(500);
            } else if( $leftScrollArrow.is(':visible') && dtScrollLeft <= 0 ) {
                $leftScrollArrow.fadeOut(500);
            }
        }
        /**
         * Calculates horizontal scroll position for comparison 
         * provider table on arrow clicks
         */
        const handleScroll = arrow => {
            const dtFrameLeft = $dtFrame.offset().left;
            const dtFrameRight = dtFrameLeft + $dtFrame.outerWidth();
            const dtScrollLeft = $dtFrame.scrollLeft();

            let targetColumnLeft = 0;
            let targetColumnRight = 0;

            // Find the last column/td before hitting either the right edge of the
            // frame if using the right arrow, or the right edge of the fixed column
            // if using the left arrow. Then get the left or right edge of that column
            // in order to calculate the scroll position.
            $dTable.find('tr:first-child td').each((i, el) => {
                const columnLeft = $(el).offset().left;
                if(columnLeft < dtFrameRight && arrow === 'right') {
                    targetColumnLeft = columnLeft;
                }
                if(columnLeft < dtFrameLeft && arrow === 'left') {
                    targetColumnRight = columnLeft + $(el).outerWidth();
                }
            });

            let scrollLeftUnit = 0;

            // Calculate the scrollLeft (the width of the element that is being hidden as
            // overflow behind the left side of its frame).
            if(arrow === 'right') {
                // The -2 at the end is to nudge the column to the left just slightly
                // enough to hide its left border behind the fixed column. Otherwise,
                // the border comes through the box-shadow.
                scrollLeftUnit = dtScrollLeft + targetColumnLeft - (dtFrameLeft - 2);
            } else if(arrow === 'left') {
                scrollLeftUnit = dtScrollLeft - (dtFrameRight - targetColumnRight);
            }

            $('.compare-providers-table-scroll-main-container').animate({
                scrollLeft: scrollLeftUnit,
            }, () => showHideArrows());
        }

        // Cycle through the rows of the fixed table and set each row height
        //  to that of the main table rows
        $('#compare-providers-table-scroll-main tr').each((i, el) => {
            $('#compare-providers-table-scroll-fixed tr').eq(i).height($(el).height());
        });

        $rightScrollArrow.on('click', () => handleScroll('right'));
        $leftScrollArrow.on('click', () => handleScroll('left'));
        $dtFrame.on('scroll', () => showHideArrows());
    })(jQuery);


    //activate sticky menu on scroll
    function primaryNavFade() {
        if($(window).width() < 1185){
            primaryNavToggle('top', 'mobile');
            return;
        }

        let scroll = $(window).scrollTop();
        let header = $(".main-nav-header");

        // check page position on load
        if(scroll >= 20){
            primaryNavToggle('scroll');
        } else{
            primaryNavToggle('top');
        }

        // check page position on scroll
        $(window).on('scroll', function() {  
            if( $(document).width() >= 1200 ){  
                scroll = $(window).scrollTop();

                if (scroll >= 20 && header.hasClass('sticky-active') != true) {
                    primaryNavToggle('scroll');
                    
                } else if(scroll <= 20 && header.hasClass('sticky-active') ) {
                    primaryNavToggle('top');
                }
            }
        });
    }; 
    primaryNavFade();

     // handle nav difference from top of page to scrolled sticky
     function primaryNavToggle(status = 'top', window = ''){
        let header = $(".main-nav-header");
        if(status == 'top'){
                // remove class - used fto check status of nav bar
                header.removeClass('sticky-active');
                $('.header-container').fadeTo( 200, 0, function(){
                    $('#primary-menu').css('justify-content', 'flex-end');
                    $('.sticky-logo').hide();
                    $('.custom-logo').show();
                    if( window != 'mobile' ){
                        $('.header-zip-search-container').hide();
                    }
                    $('.header-container').fadeTo(200, 1);
                });
        } else if( status == 'scroll' ){
                // add class - used fto check status of nav bar
                header.removeClass('sticky-active').addClass("sticky-active");
                $('.header-container').fadeTo( 200, 0, function(){
                    $('#primary-menu').css('justify-content', 'flex-start');
                    $('.custom-logo').hide();
                    $('.sticky-logo').show();
                    $('.header-zip-search-container').show();
                    $('.header-zip-search-container .zip_search_form').show();
                    $('.header-container-mobile').css('display', 'flex');
                    $('.header-container').fadeTo(200, 1);
                });
        } 
        else if( status == 'window-resize' ){
            if( $(document).width() < 1200 ){  
                $('.header-zip-search-container').show();
                $('.header-zip-search-container .zip_search_form').hide();
                primaryNavFade();
                if($(document).width() < 768){
                    $('.header-zip-search-container .zip_search_form').show();
                }
            } else{  
                $('.header-zip-search-container').hide();
                primaryNavFade()
            }
        }
    }

    // fire function to show mobile nav on window resize
    let resizeTimeout;
    $(window).on('resize', function(){
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function(){    
            primaryNavToggle('window-resize');
        }, 200);
    })
    

    //Get height of tallest plan item top section and set to all siblings.
    var tallest = 0;
    var titleTall = 0;
    var titleTallest = 0;
    var priceTallest = 0;
    $('.connections-container .connection-container').each(function() {

        $(this).find('.plans-container .plan-item .top h5').each(function() {
            var height = $(this).height();
            //console.log($(this));
            if (height > titleTall) {
                titleTall = height;
            }
        }).each(function() {
            $(this).height(titleTall);
        })

        $(this).find('.plans-container .plan-item .top .title-container').each(function() {
            var height = $(this).height();
            //console.log($(this));
            if (height > titleTallest) {
                titleTallest = height;
            }
        }).each(function() {
            $(this).height(titleTallest);
        })

        $(this).find('.plans-container .plan-item .top .price').each(function() {
            var height = $(this).height();
            //console.log($(this));
            if (height > priceTallest) {
                priceTallest = height;
            }
        }).each(function() {
            $(this).height(priceTallest);
        })

        //console.log($(this))
        $(this).find('.plans-container .plan-item .top').each(function() {
            var height = $(this).height();
            //console.log($(this));
            if (height > tallest) {
                tallest = height;
            }
        }).each(function() {
            $(this).height(tallest);
        })
       
        //Reset height for other connection containers
        tallest = 0;
        titleTall = 0;
        titleTallest = 0;
        priceTallest = 0;
    })
});

// browser window scroll (in pixels) after which the "back to top" link is shown
var offset = 300,
//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
offset_opacity = 1200,
//duration of the top scrolling animation (in ms)
scroll_top_duration = 700,
//grab the "back to top" link
$back_to_top = jQuery('.back-to-top');
jQuery(window).scroll(function(){
    ( jQuery(this).scrollTop() > offset ) ? $back_to_top.addClass('modeltheme-is-visible') : $back_to_top.removeClass('modeltheme-is-visible modeltheme-fade-out');
    if( jQuery(this).scrollTop() > offset_opacity ) { 
        $back_to_top.addClass('modeltheme-fade-out');
    }
});
//smooth scroll to top
$back_to_top.on('click', function(event){
    event.preventDefault();
    $('body,html').animate({
        scrollTop: 0 ,
        }, scroll_top_duration
    );
});

// TOAST SHOW / HIDE
jQuery(document).ready(function($) {

    // make unique key with postID-toast
    // check session storage for key
    let current_pid = $('#hso-toast').data('pid') + '-toast'
    let toast_status = sessionStorage.getItem(current_pid)

    // show toast if it has not been set to hide
    setTimeout(function(){
        if( toast_status != 'hide' ){
            $('.toast').addClass('active')
        }
    }, 2000);

    // close toast function & add page id to session variable
    $('#close-toast').on('click', function(){

        $('.toast').removeClass('active')
        sessionStorage.setItem(current_pid, 'hide');

    })

    
    //custom provider select box
    $(".select-box .init").on("click", function() {
        $(this).parent().children('.inner').children('ul').children('.main-list').children('li').toggle();
        $(this).parent().children('.inner').children('.see-more').toggleClass('block');
        if($(this).parent().children().children().children().children('li').css("display") == "list-item") {
            $(this).parent().addClass('active');
        }
        else{
            $(this).parent().removeClass('active');
        }
        if($(this).parent().siblings().hasClass('active')){
            $(this).parent().siblings().removeClass('active');
            $(this).parent().siblings().children().children().children().children('li').toggle();
            $(this).parent().siblings().children('.inner').children('.see-more').removeClass('block');
        }
    });

    var allOptions1 = $("#select-provider-box1 ul div").children();

    $("#select-provider-box1 ul div").on("click", "li", function() {
        allOptions1.toggle();
        allOptions1.removeClass('selected');
        $(this).addClass("selected");
        $("#select-provider-box2 ul div li").removeClass('selected');
        $("#select-provider-box2 .init").text('Provider 2');
        if($("#select-provider-box2 .init").text() == 'Provider 2'){
            $('.comparison-providers-box .cta_btn').removeClass('cta_active');
        }
    });

    $(document).on("click", ".second-provider-generator", function() {
        $('.second-provider-generator').toggle();
        $('.second-provider-generator').removeClass('selected');
        $(this).addClass("selected");
        if($(this).hasClass('selected')) {
            $('.comparison-providers-box .cta_btn').addClass('cta_active');
        }
        else{
            $('.comparison-providers-box .cta_btn').removeClass('cta_active');
        }
    });

    $(".select-box ul div").on("click", "li", function() {
        $(this).parent().parent().parent().parent().children('.init').html($(this).children('span').html());
        $(this).parent().parent().parent().children('.see-more').toggleClass('block');
        $(this).parent().parent().parent().parent().removeClass('active');
    });
});

$(document).on('click', function (e) {
    if (($(e.target).closest(".select-box .inner").length === 0) && ($(e.target).closest(".select-box .init").length === 0)) {

        if($('#select-provider-box2').hasClass('active')){
            $('#select-provider-box2').removeClass('active');
            $("#select-provider-box2 .inner .see-more").removeClass('block');
            $("#select-provider-box2 li").toggle();
        }
        if($('#select-provider-box1').hasClass('active')){
            $('#select-provider-box1').removeClass('active');
            $("#select-provider-box1 .inner .see-more").removeClass('block');
            $("#select-provider-box1 li").toggle();
        }
    }
});