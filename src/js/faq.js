// Variables
let $ = jQuery;

$(() => {
    $('.faq-question-container').on('click', e => {
        $(e.currentTarget).parent().find('.faq-answer-container').slideToggle(400);
        $(e.currentTarget).find('.material-icons').toggleClass('rotate-180');
    });
});