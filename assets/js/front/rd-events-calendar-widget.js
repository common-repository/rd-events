/**
 * Rundiz Events calendar widget.
 */


function rdEventsActivateCalendarWidget() {
    var $ = jQuery.noConflict();

    if (typeof(RundizEventsCalendarWidget) != 'undefined') {
        if (typeof(RundizEventsCalendarWidget.ajaxurl) != 'undefined') {
            calendar_events = {
                url: RundizEventsCalendarWidget.ajaxurl,
                type: 'POST',
                data: {
                    'action': 'rundiz_events_get_events',
                    'nonce': RundizEventsCalendarWidget.nonce,
                    'lang': RundizEventsCalendarWidget.locale
                }
            };
        } else if (typeof(RundizEventsCalendarWidget.all_events) != 'undefined') {
            calendar_events = JSON.parse(RundizEventsCalendarWidget.all_events);
        } else {
            calendar_events = [];
        }
    } else {
        calendar_events = [];
    }

    $('.rundiz-events-calendar-widget').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        views: {
            month: { buttonText: RundizEventsCalendarWidget.txtListMonth }
        },
        buttonText: {
            today: RundizEventsCalendarWidget.txtToday
        },
        height: 'auto',
        columnFormat: 'dd',
        timeFormat: 'hh:mm a',
        eventLimit: true,
        events: calendar_events,
        eventRender: function(event, element) {
            element.attr('title', event.title);
        },
        loading: function(isLoading, view) {
            if (!$('.rundiz-events-calendar-widget .fc-view-container').find('.rundiz-events-loading-events-widget').length) {
                $('.rundiz-events-loading-events-template-widget').clone().prependTo('.rundiz-events-calendar-widget .fc-view-container');
                $('.rundiz-events-calendar-widget .fc-view-container .rundiz-events-loading-events-template-widget').removeClass('rundiz-events-loading-events-template-widget').addClass('rundiz-events-loading-events-widget');
            }

            if (isLoading === true) {
                $('.rundiz-events-loading-events-widget').removeClass('hide');
            } else {
                $('.rundiz-events-loading-events-widget').addClass('hide');
            }
        },
        locale: RundizEventsCalendarWidget.locale
    });
}// rdEventsActivateCalendarWidget


// on page loaded ---------------------------------------------------------------------------
jQuery(function($) {
    rdEventsActivateCalendarWidget();
});