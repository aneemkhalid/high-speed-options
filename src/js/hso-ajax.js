$(document).ready(function() {

    //Blog pagination functionality
    function blogPagination(tab) {


        // get value
        var paged = $('.'+tab+'search .load-more-button').attr("data-paged");
        var posts_per_page = $('.'+tab+'search .load-more-button').attr("data-posts_per_page");
        var tag = $('.'+tab+'search .load-more-button').attr("data-tag");
        var search_term = $('.'+tab+'search .load-more-button').attr("data-search-term");
        var exclude = $('.'+tab+'search .load-more-button').attr("data-exclude");
        var total_post_count = $('.'+tab+'search .load-more-button').attr("data-total-post-count");

        // submit the data
        jQuery.post(hso_ajax.ajaxurl, {
            action:    'blog_load_more',
            dataType: "json",
            paged: paged,
            posts_per_page: posts_per_page,
            tag: tag,
            search_term: search_term,
            exclude: exclude,
        }, function(data) {

            //unwrap data
            data2 = JSON.parse(data);
            var recent_posts_load = data2.recent_posts_load;
            if ( recent_posts_load != null ){
                recent_posts_load = recent_posts_load.replace(/\\/g, '');
            }
            var paged_new = data2.paged_new;
            paged_new = paged_new.replace(/\\/g, '');
            //display data
            $( '.'+tab+"search .latest-posts-wrapper" ).append( recent_posts_load );
            $('.'+tab+'search .load-more-button').attr("data-paged", paged_new);
            var post_count = $( '.'+tab+"search .latest-post" ).length;
            if (post_count == total_post_count) {
                $('.'+tab+'search .load-more-button').hide();
            } 
        });
    };

    $( ".insights" ).on( "click touch", ".load-more-button", function() {
        var tab = $(this).attr("data-tab");
        if (tab){
            tab = tab+'-';
        }
        blogPagination(tab);
    });   

    function blogSearch(search_term) {

        // submit the data
        jQuery.post(hso_ajax.ajaxurl, {
            action:    'blog_search',
            dataType: "json",
            search_term: search_term

        }, function(data) {

            //unwrap data
            data2 = JSON.parse(data);
            var recent_posts_load = data2.recent_posts_load;
            if ( recent_posts_load != null ){
                recent_posts_load = recent_posts_load.replace(/\\/g, '');
            }
            //display data
            $("#search .latest-posts-wrapper" ).html( recent_posts_load );
            var post_count = $( "#search .latest-post" ).length;
            var total_post_count = $('.search-total-posts').text();
            $('#search .load-more-button').attr("data-search-term", search_term);
            $('#search .load-more-button').attr("data-total-post-count", total_post_count);
            $('#search .load-more-button').attr("data-paged", "2");
            if (post_count == 0){
                $("#search .latest-posts-title div").html('<h2>No Results found for "'+search_term+'"</h2><p class="demote">Try searching again using different keywords or terms.</p>');
                $("#search .load-more-button").hide();
            } else {
                $("#search .latest-posts-title div").html("<h3>Search Results for "+search_term+"</h3>");
                $("#search .load-more-button").show();
            }
            $( ".tab-pane" ).removeClass('show').removeClass('active');
            $( "#search" ).addClass('show').addClass('active');
            $('.nav-tabs .nav-link').removeClass('active');
            if (post_count == total_post_count) {
                $('#search .load-more-button').hide();
            } 
        });
    };
    $( ".insights_nav" ).on( "click touch", "#blog-search-btn", function() {
        if ($("#blog-search-input").val() != ''){
            var search_term = $('#blog-search-input').val();
            blogSearch(search_term);
        }
    });
    $( ".insights_nav" ).on( "keypress", "#blog-search-input", function(e) {
        if(e.keyCode == 13 && $(this).val() != '') {
            var search_term = $(this).val();
            $(this).blur();
            blogSearch(search_term);
        }
    });
    $( ".insights_nav" ).on( "input", "#blog-search-input", function(e) {
        if ($(this).val() == ''){
            //show featured tab
            $( ".tab-pane" ).removeClass('show').removeClass('active');
            $('#featured-search-tab').addClass('active');
            $( "#featured-search" ).addClass('show').addClass('active');

        }
    });

    //reset input if you go back to the page
    $(window).bind("pageshow", function() {
        $('#blog-search-input').val(''); 
    });

    $(".insights_nav").on('focus', "#blog-search-input",  function(){
        $('#blog-search-form').addClass('search-container-underlined');
    });
    $(".insights_nav").on('focusout', "#blog-search-input",  function(){
        if ($("#blog-search-input").val() == ''){
            $('#blog-search-form').removeClass('search-container-underlined');
        }
    });

    //load zip search results after page load
    if ($('.zip_search_overview').length > 0){
            var city;
            var state;
            var is_city = false;
            var is_programmatic_city_page = false;
        if (typeof $('.zip_search_overview').attr("data-city") !== 'undefined') {
            is_city = true;
            city = $('.zip_search_overview').attr("data-city");
            state = $('.zip_search_overview').attr("data-state");
            is_programmatic_city_page = $('.zip_search_overview').attr("data-is-programmatic-city-page");
        }
        $.post(hso_ajax.ajaxurl, {
            action:    'load_zip_search',
            dataType: "json",
            city: city,
            state: state,
            is_city: is_city,
            is_programmatic_city_page: is_programmatic_city_page
        }, function(response) {
            $('.zip_search_overview').html(response.content).promise().done(function(){
                if (hso_ajax.site_environment === 'development'){
                    $.getScript(hso_ajax.theme_path+"/src/js/header-scripts-delayed-dev.js");
                } else {
                    $.getScript(hso_ajax.theme_path+"/src/js/header-scripts-delayed.js");
                }
                $.getScript(hso_ajax.theme_path+"/src/js/footer-scripts-delayed.js");
            });
            $('.zip_search_nav').show();
            if (is_city && !is_programmatic_city_page){
                var zip = getUrlParameter('zip');
                if (zip != null){
                    saveBDAPIDataforZip(zip);
                }
            }
        });
    }

    $('.zip-qualifier-block .zipcode form').on('submit', function(e) {
        e.preventDefault();

        var provider = $(this).data('provider');
        var zip = $(this).find('input[name=zip]').val();

        var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(zip);
        $zipInput = $(this).find('input[name=zip]');
        if (!isValidZip){
            $zipInput.val('');
            $zipInput.attr('placeholder', 'Zip not found');
            $zipInput.addClass('zip-error');
            return false; 
        }

        $('.zip-qualifier-block .zip-search-load-gif').show();
        $('.zip-qualifier-block .blue-zip-text').hide();
        $('.zip-qualifier-block .blue-container .icon-container').hide();
        $('.zip-qualifier-block .blue-container').removeClass('has-prov no-prov');

        $.post(hso_ajax.ajaxurl, {
            action:    'load_zip_search',
            dataType: "json",
            provider: provider,
            zip: zip,
        }, function(response) {
            //console.log(response)

            $('.zip-qualifier-block .zip-search-load-gif').hide();
            $('.zip-qualifier-block .zip-outer .zip_search_input').val('');
            if(response.city) {
                var c = JSON.parse(response.city);
                var city = '<span class="city">' + c['city'] + '</span>, ' + c['state'] + ' ' + zip;
                $('.zip-qualifier-block .blue-container .city-info').html(city);
            }
            if(response.content) {
                $('.zip-qualifier-block .blue-container').addClass('has-prov');
                $('.zip-qualifier-block .blue-container .unavailable h4').html('Available plans in');
                $('.zip-qualifier-block .zip_search_overview_qualifier').html(response.content).promise().done(function(){
                    if (hso_ajax.site_environment === 'development'){
                        $.getScript(hso_ajax.theme_path+"/src/js/header-scripts-delayed-dev.js");
                    } else {
                        $.getScript(hso_ajax.theme_path+"/src/js/header-scripts-delayed.js");
                    }
                    $.getScript(hso_ajax.theme_path+"/src/js/footer-scripts-delayed.js");
                    //Rerun Invoca script to change phone numbers
                    //Invoca.PNAPI.run()
                });
            }
            else {
                $('.zip-qualifier-block .blue-container').addClass('no-prov');
                $('.zip-qualifier-block .blue-container .more-providers .zip_search_input').val(zip);
                $('.zip-qualifier-block .blue-container .icon-container').show();
                $('.zip-qualifier-block .blue-container .unavailable h4').html('This provider is unavailable in');
            }
            //$('.zip_search_nav').show();
        });
    });


    
    // Authors Load More Ajax
    //$(".see-more_posts_btn").css({"display": "none"});
    $( document ).on( "click", ".see-more_posts_btn", function(e) {
        e.preventDefault();
        authors_load_more();
    });


    var authorPageNumber = 0;
    function authors_load_more(){
        var postperpage = 5; // Post per page
        var authorPageNumber = $('.authorpageNumber').val();
        //console.log(authorPageNumber);
        var authorId = $('.authorid').val();
        authorPageNumber++;
        var str = 'authorPageNumber=' + authorPageNumber + '&postperpage=' + postperpage + '&authorid=' + authorId + '&action=author_load_more_posts';

        $.ajax({
            type: "POST",
            dataType: "html",
            url: hso_ajax.ajaxurl,
            data: str,
            success: function( response ){
                if(response === ''){
                    //$(".author-bio-page .all-resources .resource-featured-post").html(response);
                    $(".author-no-more-posts").css({"display": "block"});
                    $(".see-more_posts_btn").css({"display": "none"});
                } else {
                        $('.authorpageNumber').val(authorPageNumber);
                        $(".author-bio-page .all-resources .resource-featured-post").append(response);
                        $(".see-more_posts_btn").css({"display": "inline-block"});
                }

            },
        });
    }

    // Authors Load More Ajax
    //$(".see-more_posts_btn").css({"display": "none"});
    $( document ).on( "click", ".see-more_posts_btn", function(e) {
        e.preventDefault();
        authors_load_more();
    });

      
    var authorPageNumber = 0;
    function authors_load_more(){
        var postperpage = 5; // Post per page
        var authorPageNumber = $('.authorpageNumber').val();
        //console.log(authorPageNumber);
        var authorId = $('.authorid').val();
        authorPageNumber++;
        var str = 'authorPageNumber=' + authorPageNumber + '&postperpage=' + postperpage + '&authorid=' + authorId + '&action=author_load_more_posts';

        $.ajax({
            type: "POST",
            dataType: "html",
            url: hso_ajax.ajaxurl,
            data: str,
            success: function( response ){
                if(response === ''){
                    //$(".author-bio-page .all-resources .resource-featured-post").html(response);
                    $(".author-no-more-posts").css({"display": "block"});
                    $(".see-more_posts_btn").css({"display": "none"});
                } else {
                        $('.authorpageNumber').val(authorPageNumber);
                        $(".author-bio-page .all-resources .resource-featured-post").append(response);
                        $(".see-more_posts_btn").css({"display": "inline-block"});
                }
               
            },
        });
    }

    // Resource Page Load More Ajax 
    $( document ).on( "click", ".more_posts_btn", function(e) {
        e.preventDefault();
        $(".no-more-posts").css({"display": "none"});
        var load_more_action = $('.more_posts_btn').data('action');
        load_posts(load_more_action);
    });

    var ppp = 10; // Post per page
    var pageNumber = 0;

    function load_posts(action){
        
        var ppp = 10; // Post per page
        var pageNumber = $('.pageNumber').val();
        pageNumber++;
    
        let selectedTags = [];
        let selectedFormats = [];
        let resourceSearchTerm = '';

        resourceSearchTerm = $('#resource-search-input').val();

        $( ".explore.topics .filter-tag").each(function(index) {
           if($(this).hasClass("tags")) {
            tag = $(this).data("tag");
            selectedTags.push(tag);
            }
        });
        $( ".explore.formats .explore-format").each(function(index) {
            if($(this).hasClass("tags")) {
                format = $(this).data("format");
                selectedFormats.push(format);
            }
            });
    
            var str = 'selectedFormats=' + selectedFormats + '&selectedTags=' + selectedTags + '&action=resources_search_filter_load_more&resource_keyword=' + resourceSearchTerm+'&paged='+pageNumber+'&ppp='+ppp;

        $.ajax({
            type: "POST",
            dataType: "html",
            url: hso_ajax.ajaxurl,
            data: str,
            success: function( response ){
                if(response === ''){
                    $(".all-resources .resource-featured-post").append(response);
                    $(".no-more-posts").css({"display": "block"});
                    $(".more_posts_btn").css({"display": "none"});
                } else {
                        $('#pageno').val( pageNumber );
                        $(".all-resources .resource-featured-post").append(response);
                        $(".more_posts_btn").css({"display": "inline-block"});
                }
               
            },
        });
    }

    function filter_resource_posts(selectedTags = [], selectedFormats = [], search_keyword = '' ){
        
        var pageNumber = $('.pageNumber').val();

        var str = 'selectedFormats=' + selectedFormats + '&selectedTags=' + selectedTags + '&action=resources_filter_tags&search_keyword=' + search_keyword+'&pagenumber='+pageNumber;

        $.ajax({
            type: "POST",
            dataType: "html",
            url: hso_ajax.ajaxurl,
            data: str,
            success: function(data){
                pageNumber++;
                if(data === ''){
                    $(".all-resources .resource-featured-post").html(data);
                    $(".no-more-posts").css({"display": "block"});
                    $(".more_posts_btn").css({"display": "none"});
                } else {
                    $('pageNumber').val(pageNumber);
                    $(".all-resources .resource-featured-post").html(data);
                    $('.hiddentags').val(selectedTags);
                    $(".more_posts_btn").css({"display": "inline-block"});
                }
            },
        });
    }
    
    $( document ).on( "click", "#resource-topic-select", function(e) {
        e.preventDefault();
        if($(this).hasClass('tags')){
            $('.explore-topic').removeClass('tags');
        }
        else{
            $('.explore-topic').addClass('tags');
        }
    });

    $( document ).on( "click", "#resource-format-select", function(e) {
        e.preventDefault();
        if($(this).hasClass('tags')){
            $('.explore-format').removeClass('tags');
        }
        else{
            $('.explore-format').addClass('tags');
        }
    });

    $( document ).on( "click", ".filter-tag", function(e) {
        e.preventDefault();
        $(".no-more-posts").css({"display": "none"});
        let selectedTags = [];
        let selectedFormats = [];
        let search_keyword = '';
        
        $('.pageNumber').val(1);
        $('.more_posts_btn').data('action', 'resources_filter_tags');

        if($(this).hasClass('tags')){
            $(this).removeClass('tags');
        }
        else{
            $(this).addClass('tags');
        }
        $( ".explore.topics .filter-tag").each(function(index) {
            if($(this).hasClass("tags")) {
                tag = $(this).data("tag");
                selectedTags.push(tag);
          }
        });
        $( ".explore.formats .explore-format").each(function(index) {
            
            if($(this).hasClass("tags")) {
               format = $(this).data("format");
               selectedFormats.push(format);
            }
          });

          search_keyword = $('#resource-search-input').val();
          filter_resource_posts( selectedTags, selectedFormats, search_keyword );
    });

    $( document ).on( "click touch", ".resource-filters .input-wrap span", function() {
        var resourceSearchTerm = $('#resource-search-input').val();
        resourceSearch(resourceSearchTerm);
    });
    $( document ).on( "keypress", "#resource-search-input", function(e) {
        if(e.keyCode == 13 ) {
            var resourceSearchTerm = $(this).val();
            $(this).blur();
            resourceSearch(resourceSearchTerm);
        }
    });

    $('.zip_search_form').submit(function(e){
        e.preventDefault();
        var zip = $(this).find('input[name=zip]').val();
        $zipInput = $(this).find('input[name=zip]');
        var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(zip);
        if (!isValidZip){
            $zipInput.val('');
            $zipInput.attr('placeholder', 'Zipcode not found');
            $zipInput.addClass('zip-error');
            return false; 
        }
        if ($(this).find('.check-list').length){
            $(this).find('.check-list input').removeClass('checked');
            $(this).find('.check-list input').each(function( index ) {
                if ($(this).prop('checked')){
                    $(this).addClass('checked');
                }
            });
            var type = $(this).find('input[name=type].checked').val();   
        } else {
            type = $(this).find('input[name=type]').val();
        }
        if (type == ''){
            var type = 'internet';
        }
        zipSearchRedirect(type, zip);
    });
    //if we're on the home page and zip is present in url param then redirect to city page
     if ($('#home-page-zip-search-redirect').length){
        var zip = getUrlParameter('zip');
        //check if is valid zip
        var isValidZip = /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(zip);
        if (isValidZip){
            var type = getUrlParameter('type');
            if (type == ''){
                type = 'internet';
            }
            zipSearchRedirect(type, zip);
        } else {
            $('.preloader').remove();
        }
    }

    
    function zipSearchRedirect(type, zip){

        $.post(hso_ajax.ajaxurl, {
            action:    'zip_to_city',
            dataType: "json",
            zip: zip,
        }, function(data) {
            //unwrap data
            data2 = JSON.parse(data);
            var url = data2.url;
            var city = data2.city;
            var state = data2.state;
            var gclid = getUrlParameter('gclid');
            var is_programmatic_page = getUrlParameter('is_programmatic_page');
            if ( url != '' ){
                url = url.replace(/\\/g, '');
                if (is_programmatic_page){
                    url = url+'&zip='+zip+'&type='+type;
                } else {
                    url = url+'?zip='+zip+'&type='+type;
                }
                //display data
            } else {
                var getUrl = window.location;
                var baseUrl = getUrl .protocol + "//" + getUrl.host +"/zip-search";
                url = baseUrl+'?type='+type+'&zip='+zip;
            }
            if (gclid){
                url = url+'&gclid='+gclid;
            }
            window.location.href = url;
        });
    }

    //function to just save the bd api data for a given zip code (to have in case BD API shuts off service)
    function saveBDAPIDataforZip(zip=null){
        $.post(hso_ajax.ajaxurl, {
            action:    'saveBDAPIData',
            dataType: "json",
            zip: zip,
        });
    }

});

function resourceSearch( resourceSearchTerm ){
    
    $('.pageNumber').val(1);
    $('.more_posts_btn').data('action', 'resources_search_filter');

    var ppp = 10;
    var pageNumber = $('#pageno').val();

    let selectedTags = [];
    let selectedFormats = [];

    $( ".explore.topics .filter-tag").each(function(index) {
       if($(this).hasClass("tags")) {
        tag = $(this).data("tag");
        selectedTags.push(tag);
        }
    });
    $( ".explore.formats .explore-format").each(function(index) {
        if($(this).hasClass("tags")) {
            format = $(this).data("format");
            selectedFormats.push(format);
        }
        });

    $.ajax({
        type: "POST",
        dataType: "html",
        url: hso_ajax.ajaxurl,
        data: {
            'action': $('.more_posts_btn').data('action'),
            'resource_keyword': resourceSearchTerm,
            'paged': pageNumber,
            'ppp': ppp,
            'selectedTags': selectedTags,
            'selectedFormats': selectedFormats
        },
        success: function( response ){
            if(response === ''){
                $(".no-more-posts").css({"display": "block"});
                $(".more_posts_btn").css({"display": "none"});
                $(".all-resources .resource-featured-post").html(response);
            } else { 
                $('#pageno').val( pageNumber );
                $(".all-resources .resource-featured-post").html(response);
                $(".more_posts_btn").css({"display": "inline-block"});
        }
        },
    });

}

// Comparisons Load More Ajax
$( document ).on( "click", ".comp-view-more", function(e) {
    e.preventDefault();
    comparisons_load_more();
});
comparisons_load_more();

  
var compPageNumber = 0;
function comparisons_load_more(){
    var postperpage = 5; // Post per page
    var compPageNumber = $('.compPageNumber').val();
    
    var str = 'compPageNumber=' + compPageNumber + '&postperpage=' + postperpage + '&action=comparisons_load_more_posts';

    $.ajax({
        type: "POST",
        dataType: "json",
        url: hso_ajax.ajaxurl,
        data: str,
        success: function( response ){
            if(response === ''){
                $(".comp-no-more-posts").css({"display": "block"});
                $(".comp-view-more").css({"display": "none"});
            } else {
                $('.compPageNumber').val(compPageNumber);
                $(".comparison-posts-inner").append(response.comparison);
                $(".comparisons-loadmore").html(response.loadmore);
                $(".comp-view-more").css({"display": "inline-block"});
                compPageNumber++;
                $('.compPageNumber').val(compPageNumber);
            }
        },
    });
}

$( document ).on( "click", "#select-provider-box1 li", function() {
    $('#select-provider-box2 .gif-loader').css('display','block');
    $('.comparison-providers-box-inner').addClass('loading');
    if($(this).hasClass('selected')) {
        var pid = $(this).attr("data-value");
    }

    var str = 'pid=' + pid + '&action=get_select_providers';
    $.ajax({
        type: "POST",
        dataType: "html",
        url: hso_ajax.ajaxurl,
        data: str,
        success: function( response ){
            $("#select-provider-box2 .main-list").html(response);
            $('#select-provider-box2 .gif-loader').css('display','none');
            $('.comparison-providers-box-inner').removeClass('loading');
            var secondProviderLength = $('#select-provider-box2 .main-list li').length;
            if (secondProviderLength >= 1){
                $('#select-provider-box2').removeClass('default');
                //alert ('> 1');
            }
            else{
                $('#select-provider-box2').addClass('default');
                $('.comparison-providers-box .cta_btn').removeClass('cta_active');
            }
        },
    });
});

$( document ).on( "click", ".comparison-providers-box .cta_btn", function(e) {
    e.preventDefault();
    if($('#select-provider-box1 li').hasClass('selected')) {
        var pid1 = $('#select-provider-box1 li.selected').data("value");
    }
    if($('#select-provider-box2 li').hasClass('selected')) {
        var pid2 = $('#select-provider-box2 li.selected').data("value");
    }

    var str = 'pid1=' + pid1 + '&pid2=' + pid2 + '&action=get_search_comparisons';

    $.ajax({
        type: "POST",
        dataType: "html",
        url: hso_ajax.ajaxurl,
        data: str,
        success: function( response ){
            window.location.href= response;
        },
    });
});