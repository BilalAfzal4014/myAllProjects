<template>
    <div>
        <div class="page_content_holder">
            <div class="padding_content">
                <div class="white_boxed_content">
                    <div class="list_holder inner_padding full_scroll">
                        <div class="center_block no_b_40" id="tab-content">
                            <div class="tab active custom_form" id="general_tab" style="border: none">
                                <h2 class="section_heading">Location</h2>
                                <div class="input_row">
                                    <div class="col">
                                        <input placeholder="Name" type="text" v-model="locationObj.name"
                                               v-on:keydown="getCode" v-on:keyup="getCode">
                                        <p class="error" v-if="locationObjerror.name">{{ locationObjerror.name }}</p>
                                    </div>
                                </div>
                                <div class="input_row">
                                <textarea class="fluid" placeholder="Description"
                                          v-model="locationObj.description"></textarea>
                                    <p class="error" v-if="locationObjerror.description">{{ locationObjerror.description
                                        }}</p>
                                </div>
                                <div class="input_row">
                                    <!--$route.params.id == null-->
                                    <input class="right location_save" type="button"
                                           v-bind:value="locationAreaObj.location_id == '' ? 'Next': 'Update'"
                                           v-on:click="submitlocation">
                                </div>
                            </div>
                            <div class="hum_tumharay_hain" v-show="submitPressed || $route.params.id != null">
                                <!--this is the area div-->
                                <h2 class="section_heading">Manage Area</h2>
                                <div class="areas_sidebar">
                                    <a class="area_adding_btn" v-bind:class="addArea == 'addarea' ? 'active': ''"
                                       v-on:click="showToolBox"><i
                                            class="fas fa-plus-circle"></i>
                                        Add
                                        Area</a>
                                    <ul class="list_none">
                                        <li v-for="row in this.locationListing">
                                            <span v-bind:title="row['address']">{{row['address']}}</span>
                                            <div class="right_icons">
                                                <a class="edit_icon" v-on:click="editLocationArea(row)"><i
                                                        class="fas fa-edit"></i></a>
                                                <a class="edit_icon" v-on:click="deleteLocationArea(row)"><i
                                                        class="fas fa-trash"></i></a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="area_inner_content">
                                    <div class="map_holder">
                                        <div class="map_caption">
                                            <ul class="list_none maplist">
                                                <li v-bind:class="mapActive == 'Draw' ? 'active': ''">
                                                    <a title="Draw" v-on:click="drawCircle">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a title="Save" v-on:click="submitLocationArea">
                                                        <i class="fas fa-save"></i>
                                                    </a>
                                                </li>
                                                <!--<li v-bind:class="mapActive == 'Location Picker' ? 'active': ''">-->
                                                <!--<a title="Location Picker" v-on:click="locationPicker">-->
                                                <!--<img alt="#" src="../../../../assets/images/location-icon.png">-->
                                                <!--</a>-->
                                                <!--</li>-->
                                                <li v-bind:class="mapActive == 'Search' ? 'active': ''">
                                                    <a title="Search" v-on:click="showSearchBox">
                                                        <i class="fa fa-search"></i>
                                                    </a>
                                                </li>
                                                <li v-bind:class="mapActive == 'Info' ? 'active': ''">
                                                    <a class="info_opener" title="Info" v-on:click="infoOpener">
                                                        <i aria-hidden="true" class="fa fa-info-circle"></i>
                                                    </a>
                                                </li>
                                                <li v-bind:class="mapActive == 'Dragger' ? 'active': ''">
                                                    <a title="Dragger" v-on:click="stopDrawing">
                                                        <i class="fas fa-hand-paper"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="map_form">
                                            <i aria-hidden="true" class="fa fa-times info_closer"
                                               v-on:click="closeInfo"></i>
                                            <div class="d-table">
                                                <div class="v-middle">
                                                    <div class="tab active">
                                                        <div class="input_row multiple">
                                                            <div class="col">
                                                                <strong class="label_title">Address</strong>
                                                                <input readonly type="text"
                                                                       v-model="locationAreaObj.default_name"/>
                                                                <p class="error"
                                                                   v-if="locationAreaObjerrors.default_name">
                                                                    {{locationAreaObjerrors.default_name }}</p>
                                                            </div>
                                                            <div class="col">
                                                                <strong class="label_title">Latitude</strong>
                                                                <input readonly type="text"
                                                                       v-model="locationAreaObj.lat"/>
                                                                <p class="error" v-if="locationAreaObjerrors.lat">{{
                                                                    locationAreaObjerrors.lat }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="input_row multiple">
                                                            <div class="col">
                                                                <strong class="label_title">Longitude</strong>
                                                                <input readonly type="text"
                                                                       v-model="locationAreaObj.lng">
                                                                <p class="error" v-if="locationAreaObjerrors.lng">{{
                                                                    locationAreaObjerrors.lng }}</p>
                                                            </div>
                                                            <div class="col">
                                                                <strong class="label_title">Radius</strong>
                                                                <input readonly type="text"
                                                                       v-model="locationAreaObj.radius">
                                                                <p class="error" v-if="locationAreaObjerrors.radius">{{
                                                                    locationAreaObjerrors.radius }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input class="controls" id="pac-input" placeholder="Search Box"
                                               style="display:none;" type="text"/>
                                        <div id="myMap" style="width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons_strip">
            <router-link class="right" tag="button" v-bind:to="'/dashboard/location-Listing'">Back to listing
            </router-link>
        </div>
    </div>
</template>
<script>
    export default {
        name: "CreateLocation",
        mounted: function () {

            $('.maplist').css('display', 'none');
            if (this.$route.params.id != null) {
                this.locationAreaObj.location_id = this.$route.params.id;
                this.getLocationdata(this.$route.params.id);
            } else {
                this.getMap();
            }
        },
        data() {
            return {
                locationObj: {
                    id: '',
                    code: '',
                    name: '',
                    description: ''

                },
                locationAreaObj: {
                    id: '',
                    location_id: '',
                    default_name: '',
                    lat: '',
                    lng: '',
                    radius: ''
                },
                locationObjerror: {
                    name: '',
                    code: '',
                    description: ''
                },
                locationAreaObjerrors: {
                    default_name: '',
                    lat: '',
                    lng: '',
                    radius: ''
                },
                drawingManager: '',
                drawingControl: false,
                searchbar: '',
                inputVal: '',
                locationListing: [],
                mapVal: '',
                mapActive: '',
                addArea: '',
                multiCircleList: [],
                submitPressed: false,
                circle: null,
                lat: 31.5204,
                lng: 74.3587,
                mapGlobal: {},
                globalSetCircle: null,
            };
        },
        methods: {
            getLocationdata(id) {
                this.api(
                    "post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "location",
                        action: "get",
                        id: id
                    },
                    this.authHeaders
                ).then((response) => {
                    console.log(response);
                    if (response.data.meta.code == 405) {
                        this.$router.push('/notfound');
                    }
                    this.locationObj.id = response.data.data[0].id;
                    this.locationObj.code = response.data.data[0].code;
                    this.locationObj.name = response.data.data[0].name;
                    this.locationObj.description = response.data.data[0].description;
                    this.locationListing = response.data.data[0].getlocation_area;
                    console.log("kion ankh milai thi", this.locationListing.length);
                    if (this.locationListing.length == 0) {
                        // if (navigator.geolocation) {
                        //     navigator.geolocation.getCurrentPosition(this.showPosition);
                        // } else {
                        this.getMap();
                        // }
                    } else {
                        this.getMap();
                    }
                }).catch((error) => {
                    console.log('error', error);
                });
            },
            submitlocation() {
                var name = this.locationObj.name;
                var description = this.locationObj.description;
                var locationId = this.locationObj.id;
                if (name == '') {
                    this.locationObjerror.name = 'Please enter the name'
                }
                if (description == '') {
                    this.locationObjerror.description = 'Please enter the description'
                }
                if (name != '' && description != '') {
                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        {
                            resource: "location",
                            action: "create",
                            data: this.locationObj
                        },
                        this.authHeaders
                    ).then((response) => {
                        if (response.data.meta.code === 422) {
                            var resp = this.$swal({
                                position: 'top-end',
                                type: 'error',
                                title: response.data.error,
                                showConfirmButton: false,
                                timer: 1500
                            });

                            return false;
                        }
                        if (locationId != 0) {
                            var resp = this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Location has been Updated',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Location has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        //this.$router.push('/dashboard/edit-location/' + response.data.data.id);
                        this.submitPressed = true;
                        this.locationAreaObj.location_id = response.data.data.id;
                        this.locationObj.id = response.data.data.id;
                    }).catch((error) => {
                        console.log('error', error);
                    });
                }
            },
            submitLocationArea() {
                var radius = this.locationAreaObj.radius;
                var lat = this.locationAreaObj.lat;
                var long = this.locationAreaObj.lng;
                var default_name = this.locationAreaObj.default_name;
                var location_id = this.locationAreaObj.location_id;
                var id = this.locationAreaObj.id;
                if (default_name == '') {
                    this.locationAreaObj.default_name = 'unIndentified name';
                    default_name = 'unIndentified name';
                }
                if (lat == '') {
                    this.locationAreaObjerrors.lat = 'Please Enter the value'
                }
                if (long == '') {
                    this.locationAreaObjerrors.long = 'Please Enter the value'
                }
                if (radius == '') {
                    this.locationAreaObjerrors.radius = 'Please Enter the value'
                }
                if (default_name != '' && long != "" && lat != "" && radius != "" && location_id != "") {
                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        {
                            resource: "location/area",
                            action: "create",
                            data: this.locationAreaObj
                        },
                        this.authHeaders
                    ).then((response) => {
                        console.log('Response', response);
                        if (id != 0) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Location Area has been Updated',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'Location Area has been added',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        this.locationListing = response.data.data;
                        $('.maplist').css('display', 'none');
                        $("#pac-input").val('');
                        this.stopDrawing();
                        this.addArea = '';
                        var vueScope = this;
                        if (vueScope.circle != null) {
                            vueScope.circle.setMap(null);
                        }
                        vueScope.makeCircles();
                    }).catch((error) => {
                        console.log('error', error);
                    });
                } else {
                    this.$swal('error', 'PLease add Area first', 'error');
                }
            },
            drawCircle() {
                this.mapActive = 'Draw';
                this.drawingManager.setOptions({
                    drawingMode: google.maps.drawing.OverlayType.CIRCLE
                });
            },
            showToolBox() {
                var vueScope = this;
                console.log('mapGLobal', vueScope.mapGlobal);
                console.log('circle', vueScope.circle);
                console.log('setCircle', vueScope.globalSetCircle);
                this.addArea = 'addarea';
                $('.maplist').css('display', 'block');
                this.locationAreaObj.radius = '';
                this.locationAreaObj.id = '';
                this.locationAreaObj.default_name = '';
                this.locationAreaObj.lat = '';
                this.locationAreaObj.lng = '';
            },
            editLocationArea(row) {
                console.log('row', row);
                var vueScope = this;
                $('.areas_sidebar li').removeClass('active');
                $(this).addClass('active');
                $("#pac-input").val('');
                $('.maplist').css('display', 'block');
                this.addArea = 'editarea';
                this.locationAreaObj = row;
                this.locationAreaObj.lat = row['lattitude'];
                this.mapGlobal.setCenter(new google.maps.LatLng(parseFloat(row['latitude']), parseFloat(row['longitude'])));
                this.editCircle();
            },
            deleteLocationArea(row) {
                console.log('delete', row);
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "location/area/delete",
                        action: "post",
                        data: row
                    },
                    this.authHeaders
                ).then((response) => {
                    console.log('DeleteResponse', response);
                    this.$swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Location Area has been deleted',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.locationListing = response.data.data;
                    console.log('lisiting', response.data.data);
                    this.makeCircles();

                }).catch((error) => {
                    console.log('error', error);
                });
            },
            makeCircles() {
                var vueScope = this;
                var locationListing = vueScope.locationListing;
                console.log('locationListing', locationListing.length);
                console.log('multiCircleList', vueScope.multiCircleList);
                var cityCircle = '';
                var lat = '';
                var lng = '';
                var latLong = '';
                var rad = '';
                if (locationListing.length != 0) {
                    if (vueScope.multiCircleList.length != 0) {
                        for (var multiVal = 0; multiVal < vueScope.multiCircleList.length; multiVal++) {
                            vueScope.multiCircleList[multiVal].setMap(null);
                        }
                    }
                    for (var val = 0; val < locationListing.length; val++) {
                        lat = parseFloat(locationListing[val]['latitude']);
                        lng = parseFloat(locationListing[val]['longitude']);
                        latLong = {lat: lat, lng: lng};
                        rad = parseFloat(locationListing[val]['radius']) * 1000;
                        vueScope.globalSetCircle = new google.maps.Circle({
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35,
                            map: this.mapGlobal,
                            center: latLong,
                            radius: rad
                        });
                        vueScope.multiCircleList.push(vueScope.globalSetCircle);
                    }
                } else {
                    if (vueScope.multiCircleList.length != 0) {
                        for (var i = 0; i < vueScope.multiCircleList.length; i++) {
                            vueScope.multiCircleList[i].setMap(null);
                        }
                    }
                }
            },
            editCircle() {
                if (this.multiCircleList.length != 0) {
                    for (var i = 0; i < this.multiCircleList.length; i++) {
                        this.multiCircleList[i].setMap(null);
                    }
                }
                let lat = parseFloat(this.locationAreaObj.latitude);
                let lng = parseFloat(this.locationAreaObj.longitude);
                let latLong = {lat: lat, lng: lng};
                let rad = parseFloat(this.locationAreaObj.radius) * 1000;
                this.globalSetCircle = new google.maps.Circle({
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                    map: this.mapGlobal,
                    center: latLong,
                    radius: rad
                });
                this.multiCircleList.push(this.globalSetCircle);
                console.log('locationAreaObj', this.locationAreaObj.address);
            },
            getMap() {
                var vueScope = this;
                var circle = null;
                var lat = this.lat;
                var lng = this.lng;
                var latLong = null;
                var rad = null;
                latLong = {lat: lat, lng: lng};
                rad = parseFloat(rad) * 1000;
                var locationListing = vueScope.locationListing;
                var mapOptions = {
                    center: new google.maps.LatLng(lat, lng),
                    streetViewControl: false,
                    mapTypeControl: false,
                    zoom: 8
                };
                var map = new google.maps.Map(document.getElementById('myMap'), mapOptions);
                vueScope.mapGlobal = map;
                if (locationListing.length != 0) {
                    for (var val = 0; val < locationListing.length; val++) {
                        lat = parseFloat(locationListing[val]['latitude']);
                        lng = parseFloat(locationListing[val]['longitude']);
                        latLong = {lat: lat, lng: lng};
                        rad = parseFloat(locationListing[val]['radius']) * 1000;
                        vueScope.globalSetCircle = new google.maps.Circle({
                            strokeColor: '#FF0000',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#FF0000',
                            fillOpacity: 0.35,
                            map: map,
                            center: latLong,
                            radius: rad
                        });
                        vueScope.multiCircleList.push(vueScope.globalSetCircle);
                    }
                }
                vueScope.drawingManager = new google.maps.drawing.DrawingManager({
                    // drawingMode: google.maps.drawing.OverlayType.MARKER,
                    drawingControlOptions: {
                        position: google.maps.ControlPosition.TOP_CENTER,
                        drawingModes: [
                            google.maps.drawing.OverlayType.CIRCLE
                        ]
                    },
                    circleOptions: {
                        strokeWeight: 1,
                        clickable: true,
                        editable: false,
                        zIndex: 1,
                    }
                });
                vueScope.drawingManager.setMap(map);
                if (this.locationAreaObj.id > 0) {
                    // this.drawingControl = true;
                }
                vueScope.drawingManager.setOptions({
                    drawingControl: this.drawingControl
                });
                google.maps.event.addListener(vueScope.drawingManager, 'overlaycomplete', function (event) {
                    if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
                        if (circle != null) {
                            //  alert('called');
                            circle.setMap(null);
                        }
                        console.log('event.overlay', event.overlay);
                        circle = event.overlay;
                        var rad = circle.getRadius() / 1000;
                        var latitude = circle.getCenter().lat();
                        var longitude = circle.getCenter().lng();
                        vueScope.locationAreaObj.lat = latitude;
                        vueScope.locationAreaObj.lng = longitude;
                        vueScope.locationAreaObj.radius = rad;
                        vueScope.codeLatLng(latitude, longitude);
                        vueScope.circle = circle;
                        console.log('circle', vueScope.circle);
                    }
                });
                if (this.locationAreaObj.id == '') {
                    // Create the search box and link it to the UI element.
                    this.inputVal = document.getElementById('pac-input');
                }
                console.log('input', this.inputVal);
                var searchBox = new google.maps.places.SearchBox(this.inputVal);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(this.inputVal);
                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function () {
                    searchBox.setBounds(map.getBounds());
                });
                var markers = [];
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function () {
                    var places = searchBox.getPlaces();
                    if (places.length == 0) {
                        return;
                    }// Clear out the old markers.
                    markers.forEach(function (marker) {
                        marker.setMap(null);
                    });
                    console.log('marker', markers);
                    markers = [];
                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function (place) {
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
                        markers.push(new google.maps.Marker({}));

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            },
            codeLatLng(lat, lng) {
                var vueScope = this;
                vueScope.locationAreaObj.default_name = '';
                var geocoder = new google.maps.Geocoder();
                var city = "";
                var latlng = new google.maps.LatLng(lat, lng);
                geocoder.geocode({'latLng': latlng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            console.log('address', results[0].formatted_address);
                            vueScope.locationAreaObj.default_name = results[0].formatted_address;
                        } else {

                            return null;
                        }
                    } else {

                        return null;
                    }
                });
            },
            getCode() {
                var name = this.locationObj.name;
                name = name.toUpperCase();
                this.locationObj.code = name.replace(" ", "_", name)
            },
            infoOpener() {
                this.mapActive = 'Info';
                setTimeout(function () {
                    $('.map_form').addClass('active');
                }, 500);
            },
            stopDrawing() {
                this.mapActive = 'Dragger';
                this.drawingManager.setDrawingMode(null);
            },
            showSearchBox() {
                this.mapActive = 'Search';
                $("#pac-input").css('display', 'block');
                this.inputVal = document.getElementById('pac-input');
            },
            closeInfo() {
                $('.map_form').removeClass('active')
            }
        },
        watch: {
            locationObj: {
                handler(locationObj) {
                    console.log('locationObj', locationObj);
                    if (locationObj.name != "") {
                        this.locationObjerror.name = '';
                    }
                    if (locationObj.description != "") {
                        this.locationObjerror.description = '';
                    }
                },
                deep: true
            }
        }

    }
    $(document).click(function (e) {
        var info_opener = $('.info_closer');
        if (!info_opener.is(e.target) && info_opener.has(e.target).length === 0) {
            info_opener.closest('.map_form').removeClass('active');
        }
    });
    // $('.info_opener').click(function(){
    //     $('.map_form').toggleClass('active');
    // });
</script>

<style>
    .info_closer {
        float: right;
        color: #fff;
        font-size: 18px;
    }

    #footer {
        display: none;
    }
</style>

<style scoped>
    .maplist .active {
        background: gainsboro;
    }

    .location_save {
        float: right;
        background: #2a8689;
        color: #fff;
    }
</style>