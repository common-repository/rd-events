/**
 * Rundiz Events calendar for archive/category page.
 */


function rdEventsActivateCalendar() {
    var $ = jQuery.noConflict();

    if (typeof(RundizEvents) != 'undefined') {
        if (typeof(RundizEvents.ajaxurl) != 'undefined') {
            calendar_events = {
                url: RundizEvents.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'rundiz_events_get_events',
                    'nonce': RundizEvents.nonce,
                    'lang': RundizEvents.locale
                }
            };
        } else if (typeof(RundizEvents.all_events) != 'undefined') {
            calendar_events = JSON.parse(RundizEvents.all_events);
        } else {
            calendar_events = [];
        }
    } else {
        calendar_events = [];
    }

    $('.rundiz-events-calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'listDay,listWeek,month'
        },
        views: {
            listDay: { buttonText: RundizEvents.txtListDay },
            listWeek: { buttonText: RundizEvents.txtListWeek },
            month: { buttonText: RundizEvents.txtListMonth }
        },
        buttonText: {
            today: RundizEvents.txtToday
        },
        timeFormat: 'hh:mm a',
        eventLimit: true,
        events: calendar_events,
        eventRender: function(event, element) {
            element.attr('title', event.title);
        },
        loading: function(isLoading, view) {
            if (!$('.rundiz-events-calendar .fc-view-container').find('.rundiz-events-loading-events').length) {
                $('.rundiz-events-loading-events-template').clone().prependTo('.rundiz-events-calendar .fc-view-container');
                $('.rundiz-events-calendar .fc-view-container .rundiz-events-loading-events-template').removeClass('rundiz-events-loading-events-template').addClass('rundiz-events-loading-events');
            }

            if (isLoading === true) {
                $('.rundiz-events-loading-events').removeClass('hide');
            } else {
                $('.rundiz-events-loading-events').addClass('hide');
            }
        },
        locale: RundizEvents.locale
    });
}// rdEventsActivateCalendar


// on page loaded ---------------------------------------------------------------------------
jQuery(function($) {
    rdEventsActivateCalendar();
});