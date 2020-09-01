@extends('layouts.master')



@section('content')
    <style>
        .edit_user_auto{
            width: 80%;
        }
        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 22px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 210px;
            top: 10px !important;
        }
        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }
        #target {
            width: 345px;
        }
    </style>
    <div class="tab1 edit_user_form_sec">
        <div class="edit_user_auto clearfix">

            <form id="cpa-form" class="edit-user-form clearfix"  action="{{route('location.store')}}" method="post">

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="currency" id="currency" value="">

                @if($locationObj)
                    <input type="hidden" id="id"  value="@if($locationObj){{$locationObj->id}} @endif" name="id">

                @endif
                <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                <div id="map" style="width: 100%;height: 500px;"></div>
                <label for="">
                    <strong> Label: </strong>
                    <input   style="color: #000;" class="country_name"  name="default_name" id="default_name" value="@if($locationObj){{$locationObj->default_name}}@endif" placeholder="" type="text">
                    <span id="codeError" style="color: #F99; position: relative;"></span>

                </label>
                <label for="">
                    <strong> Address: </strong>
                    <input disabled required style="color: #000;" class="address"  name="address" value="@if($locationObj){{$locationObj->address}}@endif" placeholder="" type="text">
                    <input required style="color: #000;" class="address" name="address" value="@if($locationObj){{$locationObj->address}}@endif" placeholder="" type="hidden">
                    <span id="codeError" style="color: #F99; position: relative;"></span>

                </label>
                @if( in_array('SUPER-ADMIN', $availRoleArr))
                <label for="">
                    <strong> Company : </strong>
                    <div class="Campaigns_type_sec inp_select  b_r">
                        <select id="name" name="company_id" required>
                            <option value="">Please select a company</option>
                            @foreach($users as $user)
                                <option @if($locationObj)@if($locationObj->company_id == $user->id) selected @endif @endif   value="{{$user->id}}">{{$user->id}} - {{$user->email}}</option>
                            @endforeach
                        </select>
                    </div>
                </label>
                    @else
                    <input type="hidden" name="company_id" value="{{ \Auth::user()->id }}">
                @endif
                <label for="">
                    <strong> currency: </strong>
                    <div class="Campaigns_type_sec inp_select  b_r">
                        <select id="name" name="currency" required>
                            <option value="">Please select a Currency</option>
                            @foreach($currencies as $currency)
                                <option @if($locationObj)@if(isset($currency['Ccy']) && $locationObj->currency == $currency['Ccy']) selected @endif @endif   value="@if(isset($currency['Ccy']) && $currency['Ccy']!=""){{$currency['Ccy']}}@endif">@if(isset($currency['Ccy'] ) && $currency['Ccy']!=""){{$currency['Ccy']}}@endif</option>
                            @endforeach
                        </select>
                    </div>
                </label>
                <label for="">
                    <strong> Latitude: </strong>
                    <input disabled required style="color: #000;" class="latitude"  name="latitude" value="@if($locationObj){{$locationObj->lat}} @endif" placeholder="" type="text">
                    <input required style="color: #000;" class="latitude" name="lat" value="@if($locationObj){{$locationObj->lat}} @endif" placeholder="" type="hidden">
                </label>
                <label for="">
                    <strong> Longitude: </strong>
                    <input disabled="" required style="color: #000;" class="longitude"  name="longitude" value="@if($locationObj){{$locationObj->lng}}@endif" placeholder="" type="text">
                    <input required style="color: #000;" class="longitude"  name="lng"  value="@if($locationObj){{$locationObj->lng}}@endif" type="hidden">
                </label>
                <label for="">
                    <strong> Radius: </strong>
                    <input disabled="" required style="color: #000;" class="Radius"  name="Radius" value="@if($locationObj){{$locationObj->radius}} @endif" placeholder="" type="text">
                    <input required style="color: #000;" class="Radius"  name="radius"  value="@if($locationObj){{$locationObj->radius}} @endif" type="hidden">
                    <span id="codeError" style="color: #F99; position: relative;"></span>
                </label>

                <label for="">
                    <button class="sub_btn" type="submit" name="button">Add</button>
                </label>
            </form>
        </div>
    </div>
@stop



@section('jsSection')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('engagement.google_map_key')}}&region=GB&v=3&amp;sensor=false&amp;libraries=drawing,places"></script>

    <script>


        @if($locationObj)

        var mapSelected = true;

            @else
            var mapSelected = false;

            @endif
        $.validator.addMethod("regx", function (value, element, regexpr) {
            return regexpr.test(value);
        }, "Please enter alpha numeric only.");
        $(document).ready(function() {

            $("#default_name").on("change",function () {

                var dName = $(this).val();
                $.post("{{route('location.duplication')}}?name="+dName,function (data) {

                    if(data.status === 500){
                        toastr.error(data.message);
                        $("#default_name").val("");
                    }
                })
            });

            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    $("#name").trigger("change");
                    return false;

                }
            });
            $("#cpa-form").validate({
                rules: {
                    default_name: {
                        required: true,
                        regx: /^[a-zA-Z]+[a-zA-Z0-9-_ ]*[a-zA-Z0-9]$/,
                    },
                }

            });

            $(document).on('submit','form#cpa-form',function(e){

                if(!mapSelected){

                    toastr.error("please draw a circle");
                    e.stopPropagation();
                    return false;
                }
                    if(!$("#cpa-form").valid()){


                        e.stopPropagation();
                        return false;
                    }

            });
        });

    </script>
    <script>
        /*
   * declare map as a global variable
   */
        var map;
        var circle = null;

        /*
         * use google maps api built-in mechanism to attach dom events
         */
        google.maps.event.addDomListener(window, "load", function () {

            var lat = 25.276987;
            var lng = 55.296249;
            var latLong = null;
            var rad = null;
            @if($locationObj)

                lat = parseFloat("{{$locationObj->lat}}");
                lng = parseFloat("{{$locationObj->lng}}");
                latLong = {lat: lat, lng: lng};
            rad = parseFloat("{{$locationObj->radius}}") * 1000;
                    @endif

            var mapOptions = {
                center: new google.maps.LatLng(lat, lng),
                zoom : 8,
            };

            map = new google.maps.Map(document.getElementById('map'), mapOptions);
            var cityCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: latLong,
                radius: rad
            });

            var drawingManager = new google.maps.drawing.DrawingManager({
                // drawingMode: google.maps.drawing.OverlayType.RECTANGLE,
                drawingControl : true,
                drawingControlOptions : {
                    position : google.maps.ControlPosition.TOP_CENTER,
                    drawingModes : [
                        // google.maps.drawing.OverlayType.POLYGON,
                        google.maps.drawing.OverlayType.CIRCLE ]
                },
                circleOptions : {
                    strokeWeight : 1,
                    clickable : true,
                    editable : false,
                    zIndex : 1
                }
            });
            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
                if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
                    if(circle != null) {
                        circle.setMap(null);
                    }
                    cityCircle.setMap(null);
                    circle = event.overlay;
                    var rad = circle.getRadius() / 1000;
                    var latitude = circle.getCenter().lat();
                    var longitude = circle.getCenter().lng();

                    if(rad>{{config('engagement.allowed_radius_location')}}) {
                        $(".latitude").val(latitude);
                        $(".longitude").val(longitude);
                        $(".Radius").val(rad);
                        mapSelected = true;
                        codeLatLng(latitude, longitude);
                    }else{

                        $(".latitude").val("");
                        $(".longitude").val("");
                        $(".Radius").val("");
                        $(".country_name").val("");
                        $(".address").val("");
                        toastr.error("radius is too small");
                        console.log(rad)
                        return false;
                    }
                }
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);

            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };

                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({

                    }));

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });

        });

        geocoder = new google.maps.Geocoder();
        function codeLatLng(lat, lng) {
            var city = "";
            var latlng = new google.maps.LatLng(lat, lng);
            geocoder.geocode({'latLng': latlng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {

                        $(".address").val(results[0].formatted_address);
                        //find country name
                        // for (var i=0; i<results[0].address_components.length; i++) {
                        //     for (var b=0;b<results[0].address_components[i].types.length;b++) {
                        //             city= results[0].address_components[i];
                        //     }
                        //
                        // }

                        // $(".country_name").val(city.long_name);
                    } else {

                        return null;
                    }
                } else {

                    return null;
                }
            });
        }
    </script>


@stop

