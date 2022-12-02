let $ = jQuery;

let currStep = 0;
let currSpeed = 0;
let speedSelect = 0;
let prevSpeed = 0;

function animateProgressBar(progress){
    let newWidth = 100 * (progress/6);
    $('#progress-bar-fill').animate({width: newWidth + '%'})
}

function hmsdinStartForm(){
    $('#hmsdin-hero').hide();
    $('#question-1-cont').show().addClass('active');
    $('#row-form-cont').fadeIn();
    // add class for background color
    $('#howmuchspeed_page_template').addClass('form-start')

    // update step counter
    currStep = 0;
    currStep++
    $('#curr-step').html(currStep)

    // animate progress bar
    animateProgressBar(currStep)

    // change hmsdin-restart button text
    $('#hmsdin-next').show()
    $('#complete_HMSDIN').hide()

    // clear form and values on start
    currSpeed = 0;
    speedSelect = 0;
    $('#hmsdin-form input')
        .prop('checked', false)
        .prop('selected', false)
    $('.hmsdin-label').removeClass('active')
    $('#curr-speed').html('0');
}

function hmsdinResetForm(){
    $('#row-form-cont').hide();
    $('.question-cont').hide().removeClass('active');
    $('#section-form-complete').hide().removeClass('active')
    $('#hmsdin-hero').fadeIn();
    
    currStep = 0;
    // remove class for background color
    $('#howmuchspeed_page_template').removeClass('form-start')
    // animate progress bar
    animateProgressBar(currStep)

    $('#howmuchspeed_page_template').removeClass('complete');

    // clear form and values
    currSpeed = 0;
    speedSelect = 0;
    $('#hmsdin-form input')
        .prop('checked', false)
        .prop('selected', false)
    $('.hmsdin-label').removeClass('active')
    $('#curr-speed').html('0');
}


$(function() {
  

    currStep = 0;
    currSpeed = 0;
    speedSelect = 0;
    prevSpeed = 0;

    // hide all questions on page load
    $('.question-cont').hide();

    // start button
    $('#hmsdin-start').on('click', hmsdinStartForm)
    $('#hmsdin-restart').on('click', hmsdinResetForm)

    // back button
    $('#hmsdin-back').on('click', function(){
        if(currStep == 1){
            hmsdinResetForm()
        } else {
            if( currStep == 5 ){
                // change complete button text
                 $('#hmsdin-next').show()
                $('#complete_HMSDIN').hide()
            }
            // hide current question
            $('#question-' + currStep + '-cont').hide()
            $('#question-' + currStep + '-cont').removeClass('active');
            // udpate step
            currStep--
            // udpate speed counter
            prevSpeed = parseInt($('#curr-speed').html()) - parseInt($('#hmsdin-back').attr('data-step-' + currStep))
            $('#curr-speed').html(prevSpeed)
            // update current step
            $('#curr-step').html(currStep)
            // show previous step
            $('#question-' + currStep + '-cont').fadeIn().addClass('active');
            // animate progress bar
            animateProgressBar(currStep)
        }
    })
    
    function nextBtnClick() {
    // check for selected value or throw error
        if( $('#question-'+currStep+'-cont').find('.hmsdin-label.active').length > 0 ){
            $('#error-notice').animate({ opacity: 0 });
        } else{
            $('#error-notice').css('opacity', 0)
            $('#error-notice').animate({ opacity: 1 });
            return;
        }
        // check for last step
        if(currStep == 5){
            $('.question-cont').hide().removeClass('active')
            $('#row-form-cont').hide()
            $('#section-form-complete').fadeIn().addClass('active');
            $('#howmuchspeed_page_template').addClass('complete');
            // $('#progress-bar-fill').css('width', 0);
        }
        // collect points related to selected items
        $('#question-'+currStep+'-cont').find('.hmsdin-label.active').each(function(){
            speedSelect += parseInt($(this).children('input').val());
        })
        // update speed counter
        let liveCurrSpeed = parseInt($('#curr-speed').html());
        currSpeed = liveCurrSpeed + speedSelect;
        $('#curr-speed').html(currSpeed);
        // add speed to history
        $('#hmsdin-back').attr('data-step-' + currStep, speedSelect)

        // hide current question
        $('#question-' + currStep + '-cont').hide();
        $('#question-' + currStep + '-cont').removeClass('active');

        // update step counter
        currStep++
        $('#curr-step').html(currStep)
        // show next step
        $('#question-' + currStep + '-cont').fadeIn();
        $('#question-' + currStep + '-cont').addClass('active');
        speedSelect = 0;
        // animate progress bar
        animateProgressBar(currStep)

        // updated recommended speed
        $('#recommended-speed').html(currSpeed)

        // change button text to complete form
        if(currStep == 5){
            $('#hmsdin-next').hide();
            $('#complete_HMSDIN').css('display', 'inline-block');
        }
    }

    // next button
    $('#hmsdin-next').on('click', function(){
        nextBtnClick();
    })
    
    //complete button
    
    $('#complete_HMSDIN').on('click', function(){
        nextBtnClick();
    })

    // add active class to clicked form item RADIO
    $('.label-radio').on('click', function(){
        $(this).siblings('.hmsdin-label').removeClass('active');
        $(this).addClass('active');
    })

    // add active class to clicked form item - CHECKBOX
    $('.label-checkbox input').on('click', function(e){
        if( $(this).parent('.label-checkbox').hasClass('active') ){
            $(this).parent('.label-checkbox').removeClass('active');
        } else{
            $(this).parent('.label-checkbox').addClass('active');
        }
    })


});