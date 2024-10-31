/**
 * Rundiz events date picker
 */


/**
 * Activate date picker on the web browser that is not support date input.
 * 
 * @returns {Boolean} Return false if browser is already support date input.
 */
function rdEventsActivateDatepicker() {
    if (rdEventsCheckDateInput() === true) {
        console.log('This browser is already support date input, no need to activate date picker.');
        return false;
    }

    $ = jQuery.noConflict();

    $('.rd-events-date-field').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        showOtherMonths: true,
        selectOtherMonths: true
    });
}// rdEventsActivateDatepicker


/**
 * Activate time picker.
 * 
 * @link https://fgelinas.com/code/timepicker/ Reference document.
 * @returns {undefined}
 */
function rdEventsActivateTimepicker() {
    if (rdEventsCheckDateInput() === true) {
        console.log('This browser is already support time input, no need to activate date picker.');
        return false;
    }

    $ = jQuery.noConflict();

    $('.rd-events-time-field').timepicker();
}// rdEventsActivateTimepicker


/**
 * Toggle time input for all day event. This will be hide the time input if all day event was checked.
 * 
 * @returns {undefined}
 */
function rdEventsAlldayToggle() {
    $ = jQuery.noConflict();

    $('#rd-events-time-allday').on('click', function() {
        if ($(this).is(':checked') === true) {
            $('.rd-events-time-input').addClass('hidden');
            $('.rd-events-time-field').val('');
        } else {
            $('.rd-events-time-input').removeClass('hidden');
        }
    });
}// rdEventsAlldayToggle


/**
 * Check for date input support
 * 
 * @link http://stackoverflow.com/questions/10193294/how-can-i-tell-if-a-browser-supports-input-type-date Reference.
 * @returns {Boolean} Return true on supported, false for otherwise.
 */
function rdEventsCheckDateInput() {
    var input = document.createElement('input');
    input.setAttribute('type','date');

    var notADateValue = 'not-a-date';
    input.setAttribute('value', notADateValue); 

    return (input.value !== notADateValue);
}// rdEventsCheckDateInput


/**
 * Check for time input support.<br>
 * Adapt from check for date input support.
 * 
 * @link http://stackoverflow.com/questions/10193294/how-can-i-tell-if-a-browser-supports-input-type-date Reference.
 * @returns {Boolean} Return true on supported, false for otherwise.
 */
function rdEventsCheckTimeInput() {
    var input = document.createElement('input');
    input.setAttribute('type','time');

    var notATimeValue = 'not-a-time';
    input.setAttribute('value', notATimeValue); 

    return (input.value !== notATimeValue);
}// rdEventsCheckTimeInput


// run on page loaded ----------------------------------------------------------------------
jQuery(function($) {
    rdEventsActivateDatepicker();
    rdEventsActivateTimepicker();
    rdEventsAlldayToggle();
});