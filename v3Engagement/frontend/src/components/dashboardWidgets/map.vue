<template>
    <div class="timeline">
        <img class="hidden" id="ios_user" src="../../assets/images/blue-point.png">
        <img class="hidden" id="android_user" src="../../assets/images/green-point.png">
        <header class="timeline_header">
            <div class="align_left">
                <h3 class="m_right_85">Platform</h3>
                <ul class="list_none">
                    <li>
                        <span class="circle android"></span> Android
                    </li>
                    <li>
                        <span class="circle ios"></span> iOS
                    </li>
                </ul>
            </div>
            <div class="align_right">
                <span>Platform</span>
                <select v-model="mapPlatForm.selectedPlatForm" v-on:change="initializeMap">
                    <option v-for="platform in mapPlatForm.platform" v-bind:value="platform">
                        {{platform}}
                    </option>
                </select>
            </div>
        </header>

        <div id="dashboardMap" class="timeline_map">

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                mapPlatForm: {
                    platform: [
                        'All', 'IOS', 'Android'
                    ],
                    selectedPlatForm: 'All'
                },
                mapUsers: [],
            };
        },
        mounted() {
            this.initializeMap();
        },
        methods: {
            initializeMap() {
                const payLoad = {
                    resource: "dashboard/campaign-user/map/" + this.mapPlatForm.selectedPlatForm,
                    action: "get",
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.mapUsers = response.data.data;
                    this.loadDataToMap();
                }).catch((error) => {

                });
            },
            loadDataToMap() {
                if (this.mapUsers.length > 0) {
                    var map = new google.maps.Map(document.getElementById('dashboardMap'), {
                        zoom: 1,
                        center: new google.maps.LatLng(this.mapUsers[0].latitude, this.mapUsers[0].longitude),
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        /*disableDefaultUI: true,*/
                        styles: [
                            {
                                "featureType": "administrative",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": "-100"
                                    }
                                ]
                            },
                            {
                                "featureType": "administrative.province",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "visibility": "off"
                                    }
                                ]
                            },
                            {
                                "featureType": "landscape",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": -80
                                    },
                                    {
                                        "lightness": 65
                                    },
                                    {
                                        "visibility": "on"
                                    }
                                ]
                            },
                            {
                                "featureType": "poi",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": -80
                                    },
                                    {
                                        "lightness": "50"
                                    },
                                    {
                                        "visibility": "simplified"
                                    }
                                ]
                            },
                            {
                                "featureType": "road",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": "-80"
                                    }
                                ]
                            },
                            {
                                "featureType": "road.highway",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "visibility": "simplified"
                                    }
                                ]
                            },
                            {
                                "featureType": "road.arterial",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "lightness": "30"
                                    }
                                ]
                            },
                            {
                                "featureType": "road.local",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "lightness": "40"
                                    }
                                ]
                            },
                            {
                                "featureType": "transit",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": -80
                                    },
                                    {
                                        "visibility": "simplified"
                                    }
                                ]
                            },
                            {
                                "featureType": "water",
                                "elementType": "geometry",
                                "stylers": [
                                    {
                                        "hue": "#6390ff"
                                    },
                                    {
                                        "lightness": -25
                                    },
                                    {
                                        "saturation": -80
                                    }
                                ]
                            },
                            {
                                "featureType": "water",
                                "elementType": "labels",
                                "stylers": [
                                    {
                                        "lightness": -25
                                    },
                                    {
                                        "saturation": -80
                                    }
                                ]
                            }
                        ]
                    });

                    var infowindow = new google.maps.InfoWindow();
                    var marker, i;

                    for (i = 0; i < this.mapUsers.length; i++) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(this.mapUsers[i].latitude, this.mapUsers[i].longitude),
                            map: map,
                            icon: {
                                url: this.mapUsers[i].device_type == "ios" ? $("#ios_user").attr("src") : $("#android_user").attr("src"),
                                scaledSize: new google.maps.Size(15, 15), // scaled size
                                origin: new google.maps.Point(0, 0), // origin
                                anchor: new google.maps.Point(0, 0) // anchor
                            }
                        });

                        google.maps.event.addListener(marker, 'click', ((marker, i) => {
                            return () => {
                                infowindow.setContent(this.mapUsers[i].indentifier);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    }
                } else {
                    var map = new google.maps.Map(document.getElementById('dashboardMap'), {
                        zoom: 1,
                        center: new google.maps.LatLng(30.3753, 69.3451),
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        /*disableDefaultUI: true,*/
                        styles: [
                            {
                                "featureType": "administrative",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": "-100"
                                    }
                                ]
                            },
                            {
                                "featureType": "administrative.province",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "visibility": "off"
                                    }
                                ]
                            },
                            {
                                "featureType": "landscape",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": -80
                                    },
                                    {
                                        "lightness": 65
                                    },
                                    {
                                        "visibility": "on"
                                    }
                                ]
                            },
                            {
                                "featureType": "poi",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": -80
                                    },
                                    {
                                        "lightness": "50"
                                    },
                                    {
                                        "visibility": "simplified"
                                    }
                                ]
                            },
                            {
                                "featureType": "road",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": "-80"
                                    }
                                ]
                            },
                            {
                                "featureType": "road.highway",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "visibility": "simplified"
                                    }
                                ]
                            },
                            {
                                "featureType": "road.arterial",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "lightness": "30"
                                    }
                                ]
                            },
                            {
                                "featureType": "road.local",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "lightness": "40"
                                    }
                                ]
                            },
                            {
                                "featureType": "transit",
                                "elementType": "all",
                                "stylers": [
                                    {
                                        "saturation": -80
                                    },
                                    {
                                        "visibility": "simplified"
                                    }
                                ]
                            },
                            {
                                "featureType": "water",
                                "elementType": "geometry",
                                "stylers": [
                                    {
                                        "hue": "#6390ff"
                                    },
                                    {
                                        "lightness": -25
                                    },
                                    {
                                        "saturation": -80
                                    }
                                ]
                            },
                            {
                                "featureType": "water",
                                "elementType": "labels",
                                "stylers": [
                                    {
                                        "lightness": -25
                                    },
                                    {
                                        "saturation": -80
                                    }
                                ]
                            }
                        ]
                    });
                }
            }
        }
    }
</script>

<style>
    .gm-style-iw-d div {
        color: black;
    }
</style>