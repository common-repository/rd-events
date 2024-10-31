/**
 * Rundiz events map for front-end only.
 */


/**
 * Initialize the Google Map.
 * 
 * @param {string} map_id
 * @returns {undefined}
 */
function rdEventsFrontInitMap(map_id) {
    let defaultLatLng = {lat: 13.736717, lng: 100.523186};// Bangkok.
    const mapHTML = document.getElementById(RdEventsMap.mapHTMLId);
    let mapZoom = 12;

    if (typeof(mapHTML) !== 'object') {
        // if no map showup, no more works here.
        console.warn('No maps here.');
        return ;
    }

    // verify and correct map zoom level.
    mapZoom = rdEventsFrontVerifyMapZoom(mapHTML, mapZoom);

    // setup maps.
    RdEventsMap.map = new google.maps.Map(mapHTML, {
        'center': defaultLatLng,
        'zoom': mapZoom,
    });

    // setup marker variable (object) for use later.
    RdEventsMap.marker = new google.maps.Marker({
        map: RdEventsMap.map
    });

    // check that mapHTML has dataset about marker then add marker.
    rdEventsFrontSetMarker(mapHTML);
}// rdEventsFrontInitMap


/**
 * Check that mapHTML has dataset about marker then add marker.
 * 
 * @param {object} mapHTML
 * @returns {undefined}
 */
function rdEventsFrontSetMarker(mapHTML) {
    if (
        typeof(mapHTML) !== 'undefined' &&
        typeof(mapHTML.dataset) !== 'undefined' &&
        typeof(mapHTML.dataset.markerlat) !== 'undefined' &&
        typeof(mapHTML.dataset.markerlng) !== 'undefined'
    ) {
        // there are values of lat, lng. add the marker.
        const markerPosition = new google.maps.LatLng(mapHTML.dataset.markerlat, mapHTML.dataset.markerlng);

        RdEventsMap.marker.setPosition(markerPosition);
        RdEventsMap.marker.setVisible(true);
        RdEventsMap.marker.setClickable(true);
        RdEventsMap.map.setCenter(markerPosition);
    }
}// rdEventsFrontSetMarker


/**
 * Verify and correct map zoom level.
 * 
 * @private This method was called from `rdEventsFrontInitMap()`.
 * @param {object} mapHTML 
 * @param {int} mapZoom 
 * @returns {int} Return verified and corrected mapZoom.
 */
function rdEventsFrontVerifyMapZoom(mapHTML, mapZoom) {
    if (
        typeof(mapHTML) === 'object' &&
        typeof(mapHTML.dataset) !== 'undefined' &&
        typeof(mapHTML.dataset.mapzoom) !== 'undefined'
    ) {
        if (parseInt(mapHTML.dataset.mapzoom) < 1 && parseInt(mapHTML.dataset.mapzoom) > 20) {
            mapZoom = 12;
        } else {
            mapZoom = parseInt(mapHTML.dataset.mapzoom);
        }
    }

    return mapZoom;
}// rdEventsFrontVerifyMapZoom


/**
 * Initialize Google Maps base.
 * 
 * This function must be called first after Google Maps JS loaded.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsFrontInitMap()`.
 * @returns {undefined}
 */
function rdEventsInitMapBase() {
    let defaultLatLng = {lat: 13.736717, lng: 100.523186};// Bangkok.
    const mapHTML = document.getElementById(RdEventsMap.mapHTMLId);

    RdEventsMap.map = new google.maps.Map(mapHTML, {
        center: defaultLatLng,
        zoom: 12,
    });
}// rdEventsInitMapBase


// on page loaded ---------------------------------------------------------------------------
jQuery(function($) {

});