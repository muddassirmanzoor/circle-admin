<?php $freelancer_locations = $data['freelancer_locations']; ?>
<div class="row">
    <div class="col-md-12">
        @if (\Session::has('success_message'))
            <div class="alert alert-success">
                <button class="close" data-close="alert"></button>
                <span> {{ \session::get('success_message') }} </span>
            </div>
        @endif
        @if (\Session::has('error_message'))
            <div class="alert alert-danger">
                <button class="close" data-close="alert"></button>
                <span> {{ \session::get('error_message') }} </span>
            </div>
        @endif
        @if ($freelancer_locations)
            @foreach ($freelancer_locations as $key => $location)
                <div class="col-md-6" style="padding-top:10px ; padding-bottom:10px;">
                    <h3 class="control-label col-md-12 row">Location <small>({{ $location['type'] }})</small></h3>
                    <span class="row col-md-12">{{ $location['address'] }} </span> <br />
                    <div id="map{{ $key }}" class="col-md-6"
                        style="height: 400px; width: 100%; margin-bottom:10px;">
                    </div>
                    <form action="{{ route('updateLocationByAdmin') }}" id="freelancerLocationForm"
                        class="form-horizontal" method="post">
                        @csrf
                        <input style="margin-top:10px ; margin-bottom:10px;" id="autocomplete" class="form-control"
                            type="text" placeholder="Search Location">
                        <input type="hidden" id="freelancer_id" name="freelancer_id"
                            value="{{ $freelancer_locations[$key]['freelancer_id'] }}">
                        <input type="hidden" id="location_id" name="location_id"
                            value="{{ $freelancer_locations[$key]['location_id'] }}">
                        <input type="hidden" id="freelancer_location_uuid" name="freelancer_location_uuid"
                            value="{{ $freelancer_locations[$key]['freelancer_location_uuid'] }}">
                        <input type="hidden" id="location_type" name="location_type"
                            value="{{ $freelancer_locations[$key]['type'] }}">
                        <input type="hidden" id="can_travel" name="can_travel"
                            value="{{ $freelancer_locations[$key]['can_travel'] }}">
                        <input type="hidden" id="lat" name="lat" value="">
                        <input type="hidden" id="lng" name="lng" value="">
                        <input type="hidden" id="route" name="address" value="">
                        <input type="hidden" id="place_id" name="location_id" value="">
                        <input type="hidden" id="country" name="country" value="">
                        <input type="hidden" id="street_number" name="street_number" value="">
                        <input type="hidden" id="administrative_area_level_1" name="state" value="">
                        <input type="hidden" id="locality" name="city" value="">
                        <input type="hidden" id="postal_code" name="zip_code" value="">
                        <input type="hidden" id="formatted_address" name="formatted_address" value="">
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-9 col-md-3">
                                    <button disabled type="submit" class="btn green">Submit</button>
                                    <a href="{{ route('getAllFreelancers') }}" class="btn red">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
        @else
            <span class="row col-md-12">No Location Found. </span>
        @endif
    </div>




</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBqWEYAVLOFMwSEVfRqxP6CsEivmz785R4&callback=initMap" async
defer></script>
<script>
    var locations = [
        @foreach ($freelancer_locations as $key => $location)
            [ {{ $location['lat'] }}, {{ $location['lng'] }}, "{{ $location['address'] }}"],
        @endforeach
    ];

    function initMap() {
        for (i = 0; i < locations.length; i++) {
            var location_point = {
                lat: locations[i][0],
                lng: locations[i][1]
            };

            var map = new google.maps.Map(
                document.getElementById('map' + i), {
                    zoom: 15,
                    center: location_point
                });
            var marker = new google.maps.Marker({
                position: location_point,
                map: map
            });

            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][2]);
                    infowindow.open(map, marker);
                }
            })(marker, i));

            // Create the initial InfoWindow.
            // var infoWindow = new google.maps.InfoWindow(
            //     {content: locations[i][2], position: location_point});
            // infoWindow.open(map);
            // Configure the click listener.
            google.maps.event.addListener(marker, 'click', function() {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();

                // $('#lat').val(lat);
                // $('#lng').val(lng);
                var freelancer_id = $('#freelancer_id').val();
                var location_id = $('#location_id').val();
                var can_travel = $('#can_travel').val();
                var freelancer_location_uuid = $('#freelancer_location_uuid').val();

                var location_type = $('#location_type').val();
                // jQuery.post(
                //     siteUrl + 'updateLocationByAdmin'
                //     , {
                //         freelancer_id: freelancer_uuid
                //         , location_id: location_id
                //         , freelancer_location_uuid:freelancer_location_uuid
                //         , lat:lat
                //         , lng:lng
                //         , location_type:location_type
                //         , can_travel:can_travel

                //     }, function(data, status){
                //         if(data.success == true){
                //             toastr.success(data.message, 'Location Update', {timeOut: 2500})
                //         }else{
                //             toastr.error(data.message, 'Location Update', {timeOut: 2500});
                //         }
                //     });
                //infoWindow.close();
                // Create a new InfoWindow.
                // infoWindow = new google.maps.InfoWindow({position: mapsMouseEvent.latLng});
                // infoWindow.setContent(mapsMouseEvent.latLng.toString());
                // infoWindow.open(map);
            });
        }

    }
</script>
