/**
 * Rundiz events map
 */


/**
 * Clear location.
 * 
 * @returns {undefined}
 */
function rdEventsClearLocation() {
    $('#rd-events-location').val('');
    $('#rd-events-location-lat').val('');
    $('#rd-events-location-lng').val('');
    marker.setVisible(false);
    infowindow.close();
}// rdEventsClearLocation


/**
 * Disable press enter on input.
 * 
 * @returns {Boolean} Return false on enter.
 */
function rdEventsDisableEnterInput() {
    $ = jQuery.noConflict();

    $('#rd-events-location').on('keyup keypress', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
}// rdEventsDisableEnterInput


/**
 * Initialize the Google Map.
 * 
 * @link https://developers.google.com/maps/documentation/javascript/reference Reference.
 * @link https://developers.google.com/maps/documentation/javascript/overview Google Maps JS API.
 * @link https://developers.google.com/maps/documentation/javascript/place-autocomplete Place auto complete.
 * @returns {undefined}
 */
function rdEventsInitMap() {
    $ = jQuery.noConflict();

    // initialize Google Maps base.
    rdEventsInitMapBase();

    // check if there is no saved data, detect location by ask for user's current location.
    rdEventsMapDetectLocation();

    // activate auto complete.
    rdEventsMapActivateAutoComplete();
    // activate info window. also listen click on the marker and open info window.
    rdEventsMapActivateInfoWindow();

    // click to add marker.
    rdEventsMapListenClickMapAddMarker();

    // drag marker to change info including lat, lng.
    rdEventsMapListenDragMarkerChangeLatLng();

    // autocomplete add marker.
    rdEventsMapListenAutocompletePlaceChanged();

    // check that this page already set marker or not, if yes then add marker.
    rdEventsMapSetMarkerFromSaved();
}// rdEventsInitMap


/**
 * Initialize Google Maps base.
 * 
 * This function must be called first after Google Maps JS loaded.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsInitMapBase() {
    let bangkok = {lat: 13.736717, lng: 100.523186};

    RdEventsMap.map = new google.maps.Map(document.getElementById('map'), {
        center: bangkok,
        zoom: 12,
    });
}// rdEventsInitMapBase


/**
 * Activate auto complete.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapActivateAutoComplete() {
    const input = document.getElementById('rd-events-location');

    const mapCenter = RdEventsMap.map.getCenter();
    const center = {'lat': mapCenter.lat(), 'lng': mapCenter.lng()};

    // Create a bounding box with sides ~10km away from the center point
    const defaultBounds = {
      north: center.lat + 0.1,
      south: center.lat - 0.1,
      east: center.lng + 0.1,
      west: center.lng - 0.1,
    };

    const options = {
      bounds: defaultBounds,
      fields: ['place_id', 'name', 'types'],
      strictBounds: false,
    };

    RdEventsMap.autocomplete = new google.maps.places.Autocomplete(input, options);

    RdEventsMap.autocomplete.bindTo('bounds', RdEventsMap.map);
}// rdEventsMapActivateAutoComplete


/**
 * Activate info window.
 * 
 * Also listen click on the marker and open info window.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapActivateInfoWindow() {
    RdEventsMap.infowindow = new google.maps.InfoWindow();

    RdEventsMap.infowindowContent = document.getElementById('rd-events-infowindow-content');
    RdEventsMap.infowindow.setContent(RdEventsMap.infowindowContent);
    RdEventsMap.geocoder = new google.maps.Geocoder;

    RdEventsMap.marker = new google.maps.Marker({
        'map': RdEventsMap.map,
        'draggable': true
    });

    // listen "click" on the marker and open info window.
    RdEventsMap.marker.addListener('click', function() {
        RdEventsMap.infowindow.open(RdEventsMap.map, RdEventsMap.marker);
    });
}// rdEventsMapActivateInfoWindow


/**
 * Check if there is no saved data, detect location by ask for user's current location.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapDetectLocation() {
    let $ = jQuery.noConflict();

    if (navigator.geolocation && $('#rd-events-location-lat').val() == '' && $('#rd-events-location-lng').val() == '') {
        // if browser support for geolocation and there are no any saved lat, lng values.
        navigator.geolocation.getCurrentPosition(function(position) {
            let pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            RdEventsMap.map.setCenter(pos);
        });
    }
}// rdEventsMapDetectLocation


/**
 * Listen on auto complete place has changed.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapListenAutocompletePlaceChanged() {
    RdEventsMap.autocomplete.addListener('place_changed', function () {
        RdEventsMap.infowindow.close();
        const place = RdEventsMap.autocomplete.getPlace();

        if (!place.place_id) {
            console.warn('There is no place_id in the result.', place);
            return;
        }

        RdEventsMap.geocoder.geocode({'placeId': place.place_id}, function (results, status) {
            if (status !== 'OK') {
                window.alert(status);
                return;
            }

            RdEventsMap.map.setCenter(results[0].geometry.location);
            // Set the position of the marker using the location.
            RdEventsMap.marker.setPosition(results[0].geometry.location);
            RdEventsMap.marker.setVisible(true);
            RdEventsMap.infowindowContent.children['rd-events-place-name'].textContent = place.name;
            RdEventsMap.infowindowContent.children['rd-events-place-address'].textContent = results[0].formatted_address;
            RdEventsMap.infowindow.open(RdEventsMap.map, RdEventsMap.marker);

            $('#rd-events-location-lat').val(results[0].geometry.location.lat());
            $('#rd-events-location-lng').val(results[0].geometry.location.lng());
        });
    });
}// rdEventsMapListenAutocompletePlaceChanged


/**
 * Listen click on the map to add marker and set lat, lng.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapListenClickMapAddMarker() {
    RdEventsMap.map.addListener('click', function(event) {
        RdEventsMap.marker.setPosition(event.latLng);
        RdEventsMap.marker.setVisible(true);

        RdEventsMap.geocoder.geocode({'latLng': RdEventsMap.marker.getPosition()}, function (results, status) {
            if (status !== 'OK') {
                window.alert(status);
                return ;
            }

            RdEventsMap.infowindowContent.children['rd-events-place-name'].textContent = '';
            RdEventsMap.infowindowContent.children['rd-events-place-address'].textContent = results[0].formatted_address;
            RdEventsMap.infowindow.open(RdEventsMap.map, RdEventsMap.marker);

            $('#rd-events-location').val(results[0].formatted_address);
            $('#rd-events-location-lat').val(event.latLng.lat());
            $('#rd-events-location-lng').val(event.latLng.lng());
        });
    });
}// rdEventsMapListenClickMapAddMarker


/**
 * Listen on drag marker and then update lat, lng.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapListenDragMarkerChangeLatLng() {
    RdEventsMap.marker.addListener('dragend', function(event) {
        RdEventsMap.marker.setPosition(event.latLng);
        RdEventsMap.marker.setVisible(true);

        RdEventsMap.geocoder.geocode({'latLng': RdEventsMap.marker.getPosition()}, function (results, status) {
            if (status !== 'OK') {
                window.alert(status);
                return ;
            }

            console.debug(results);

            RdEventsMap.infowindowContent.children['rd-events-place-name'].textContent = '';
            RdEventsMap.infowindowContent.children['rd-events-place-address'].textContent = results[0].formatted_address;
            RdEventsMap.infowindow.open(RdEventsMap.map, RdEventsMap.marker);

            $('#rd-events-location').val(results[0].formatted_address);
            $('#rd-events-location-lat').val(event.latLng.lat());
            $('#rd-events-location-lng').val(event.latLng.lng());
        });
    });
}// rdEventsMapListenDragMarkerChangeLatLng


/**
 * Check that this page already set marker or not, if yes then add marker.
 * 
 * @since 1.0.2
 * @private This method was called from `rdEventsInitMap()`.
 * @returns {undefined}
 */
function rdEventsMapSetMarkerFromSaved() {
    let $ = jQuery.noConflict();

    if ($('#rd-events-location-lat').val() != '' && $('#rd-events-location-lng').val() != '') {
        // there are values of lat, lng. add the marker.
        const savedMarkerPosition = new google.maps.LatLng($('#rd-events-location-lat').val(), $('#rd-events-location-lng').val());

        RdEventsMap.marker.setPosition(savedMarkerPosition);
        RdEventsMap.marker.setVisible(true);
        RdEventsMap.map.setCenter(savedMarkerPosition);

        // set info window data.
        if ($('#rd-events-location').val() != '') {
            RdEventsMap.infowindowContent.children['rd-events-place-name'].textContent = $('#rd-events-location').val();
        } else {
            RdEventsMap.infowindowContent.children['rd-events-place-address'].textContent = $('#rd-events-location-lat').val()+', '+$('#rd-events-location-lng').val();
        }
        RdEventsMap.infowindow.open(RdEventsMap.map, RdEventsMap.marker);
    }
}// rdEventsMapSetMarkerFromSaved


// on page loaded ---------------------------------------------------------------------------
jQuery(function($) {
    rdEventsDisableEnterInput();
});