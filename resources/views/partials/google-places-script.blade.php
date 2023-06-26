@push('bottom')
<script async
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.google_place_key') }}&libraries=places&callback=initMap&language=de">
</script>
<script>
    function initMap() {
        const options = {
            componentRestrictions: {country: "de"},
            offset: 3,
            fields: ["geometry", "address_components"], //"address_components", 
            //types: ['(cities)'],
            //types: ["locality","administrative_area_level_3"],
            types: ["geocode"],
        };

        const ortOrderPlz = document.getElementById('ort-order-plz');
        if (ortOrderPlz) {
            const autocomplete = new google.maps.places.Autocomplete(ortOrderPlz, options);

            autocomplete.addListener("place_changed", function () {
                let place = autocomplete.getPlace();
                locationChangeHandler();
                if (place.geometry) {
                    $('#geo_location').val('POINT(' + place.geometry.location.lat() + ' ' + place.geometry.location.lng() + ')');
                }
                updateSearchLocation();
            });

            ortOrderPlz.addEventListener("change", function () {
                //if (isNaN(ortOrderPlz.value)) {
                    ortOrderPlz.value = "";
                    $('#geo_location').val('');
                    locationChangeHandler();
                    updateSearchLocation();
                //}
            });
        }

        const inputLocation = document.getElementById('location');
        if (inputLocation) {
            const autocomplete = new google.maps.places.Autocomplete(inputLocation, options);

            autocomplete.addListener("place_changed", function () {
                let place = autocomplete.getPlace();
                if (place.geometry) {
                    $('#geo_location').val('POINT(' + place.geometry.location.lat() + ' ' + place.geometry.location.lng() + ')');
                }
            });

            inputLocation.addEventListener("change", function () {
                inputLocation.value = "";
                $('#geo_location').val('');
            });
        }


        const fundusLocationElement = document.getElementById('fundus_location');
        if (fundusLocationElement) {
            const autocompleteFundus = new google.maps.places.Autocomplete(fundusLocationElement, options);

            autocompleteFundus.addListener("place_changed", function () {
                let place = autocompleteFundus.getPlace();
                let country_name = '';
                let postal_code = '';
                let screenObject = $('#registeration-popup, #upgrade-store-popup, #edit-store-screen');

                if (place.address_components) {

                    $.each(place.address_components, function (key, addressComponent) {
                        $.each(addressComponent.types, function (key, componentType) {
                            if (componentType == 'country') {
                                country_name = addressComponent.long_name;
                            }
                            if (componentType == 'postal_code') {
                                postal_code = addressComponent.long_name;
                            }
                        });
                    });

                    screenObject.find('#fundus_country').val(country_name);
                    screenObject.find('#fundus_country').valid();
                    if (country_name != '') {
                        fundusLocationElement.value = fundusLocationElement.value.replace(', ' + country_name, '');
                        screenObject.find('#fundus_location').valid();
                    }

                    //if(postal_code != '') {
                    //$('#registeration-popup, #upgrade-store-popup, #edit-store-screen').find('#fundus_postal_code').val(postal_code);
                    //}
                }
                if (place.geometry) {
                    screenObject.find('#fundus_geo_location').val('POINT(' + place.geometry.location.lat() + ' ' + place.geometry.location.lng() + ')');
                }
            });

            fundusLocationElement.addEventListener("change", function () {
                fundusLocationElement.value = "";
                $('#registeration-popup, #upgrade-store-popup, #edit-store-screen').find('#fundus_geo_location').val('');
            });
        }

    }
</script>
@endpush