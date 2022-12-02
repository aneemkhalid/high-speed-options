// Imports
import InputMask from 'inputmask';

// Variables
let $ = jQuery;

// Functions
const validateEmail = val => {
    const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(val);
}

const validateForm = () => {
    let validatedEmails = true;
    $.each($('.frm_form_field input[type="email"]'), (i, el) => {
        const isValidEmail = validateEmail($(el).val());
        if(!isValidEmail) validatedEmails = false;
    })

    // return true if the number of trimmed value lengths equal to 0
    // create a filtered array with a length of 0 and all emails are valid
    return $('.frm_required_field input').filter((i, el) => $.trim($(el).val()).length === 0).length === 0 && validatedEmails;
}

const handleEmailField = (target, eventType) => {
    let validEmail = validateEmail(target.val());
    const $parent = target.parent();
    const $descriptionEl = $parent.find('.frm_description');
    const $invalidEl = $parent.find('.frm_invalid');

    if(eventType === 'blur') {
        if(!target.val()) {
            $invalidEl.hide();
            $descriptionEl.show();
            $parent.addClass('frm_blank_field');
        }

        if(target.val() && !validEmail) {
            $descriptionEl.hide();
            $parent.addClass('frm_blank_field');

            if(!$invalidEl.length || ($invalidEl.length && !$invalidEl.is(':visible'))) {
                $parent.append('<div class="frm_invalid">Invalid email address</div>');
            }
        }
    }

    if(eventType === 'keyup') {
        if($parent.hasClass('frm_blank_field')) {

            if($invalidEl.is(':visible') && !validEmail) {
                validEmail = validateEmail(target.val());
            }

            if($invalidEl.is(':visible') && validEmail) {
                $invalidEl.hide();
                $descriptionEl.show();
            }

            if($descriptionEl.is(':visible') || validEmail) {
                $parent.removeClass('frm_blank_field');
            }
        }
    }
}

const handleErrorMessages = errorMessages => {
    // Replace error messages with corresponding field descriptions
    // and mark all error fields in red
    errorMessages.each((i, message) => {
        $(message).parent().addClass('frm_blank_field');
        $(message).hide();
    })
}

const applyErrorFormatting = () => {
    // If user attempts to submit non-valid form, apply error
    // styling to all required, non-valid inputs
    $('.frm_required_field input').each((i, message) => {
        if(!$.trim($(message).val())) $(message).parent().addClass('frm_blank_field');
    })
}

$(() => {
    // For tel input types to use (999) 999-9999 format
    InputMask({'mask': '(999) 999-9999'}).mask('input[type="tel"]');

    // const $submitButton = $('.frm-show-form .frm_button_submit');
    // if($submitButton.length) $submitButton.prop('disabled', true);
    $('.frm-show-form .frm_button_submit').on('click', e => {
        applyErrorFormatting()
        return validateForm();
    });

    const $errorMessages = $('.frm_error');
    if($errorMessages.length) handleErrorMessages($errorMessages);

    // Add error styling if input is blank on blur
    $('.frm_form_field.frm_required_field input').on('blur', e => {
        const target = $(e.currentTarget);

        if(target.attr('type') === 'email') handleEmailField(target, 'blur');

        if(!target.val()) target.parent().addClass('frm_blank_field');
    });
    
    // Remove error styling if input value is defined
    $('.frm_form_field.frm_required_field input').on('keyup', e => {
        const target = $(e.currentTarget);

        if(target.attr('type') === 'email') handleEmailField(target, 'keyup');

        if(target.val() && target.attr('type') !== 'email') {
            target.parent().find('.frm_invalid').hide();
            target.parent().find('.frm_description').show();
            target.parent().removeClass('frm_blank_field');
        }
    });
});
