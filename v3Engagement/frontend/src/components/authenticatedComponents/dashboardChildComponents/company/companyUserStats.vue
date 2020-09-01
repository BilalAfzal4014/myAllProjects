<template>
    <div class="page_content_holder">
        <div class="padding_content">
            <div class="list_holder full_scroll">
                <div class="white_boxed_content lists_page">
                    <div class="user_state_cols user_page">
                        <div class="state_col">
                            <div class="img_holder">
                                <div style="height: 128px;" v-if="mainObj.userObj.image_url">
                                    <img v-bind:src="mainObj.userObj.image_url"/>
                                </div>
                                <div v-else>
                                    <img alt="#"
                                         src="../../../../assets/images/profile_placeholder.png">
                                </div>

                            </div>
                            <div class="text user_info_div">
                                <h2>{{mainObj.userObj.userName}}</h2>
                                <ul class="list_none fluid">
                                    <li>
                                        <span>User Id:</span>
                                        <span>{{mainObj.userObj.userId}}</span>
                                    </li>
                                    <li>
                                        <span>Row Id:</span>
                                        <span>{{lastDevice.row_id}}</span>
                                    </li>
                                    <li>
                                        <span>Email:</span>
                                        <span>{{mainObj.userObj.email}}</span>
                                    </li>
                                    <li>
                                        <span>Language:</span>
                                        <span>{{lastDevice.lang}}</span>
                                    </li>
                                </ul>
                                <div style="width: 200px;">
                                    <div class="on_off_div" style="float: none;margin-bottom: 4px;">
                                        <strong>App Notification</strong>
                                        <div class="an-onoffswitchContainer" style="float: right">
                                            <input type="checkbox" class="an-switch an-onoffswitch-checkbox"
                                                   name="check1" id="an-onoffswitch1"
                                                   v-model="mainObj.userObj.enable_notification"
                                                   v-on:change="changeNotification('enable_notification')">
                                            <label class="an-onoffswitch-label" for="an-onoffswitch1">
                                                <span class="an-onoffswitch-inner"></span>
                                                <span class="an-onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="on_off_div" style="float: none">
                                        <strong>Email Notification</strong>
                                        <div class="an-onoffswitchContainer" style="float: right">
                                            <input type="checkbox" class="an-switch an-onoffswitch-checkbox"
                                                   name="check1" id="an-onoffswitch0"
                                                   v-model="mainObj.userObj.email_notification"
                                                   v-on:change="changeNotification('email_notification')">
                                            <label class="an-onoffswitch-label" for="an-onoffswitch0">
                                                <span class="an-onoffswitch-inner"></span>
                                                <span class="an-onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="state_col">
                            <div class="text">
                                <h2>&nbsp;</h2>
                                <ul class="list_none text-left">
                                    <li>
                                        <span>Country:</span>
                                        <span>{{mainObj.userObj.country}}</span>
                                    </li>
                                    <li>
                                        <span>App ID:</span>
                                        <span>{{lastDevice.app_id}}</span>
                                    </li>
                                    <li>
                                        <span>App Name:</span>
                                        <span>{{lastDevice.app_name}}</span>
                                    </li>
                                    <li>
                                        <span>Device Type:</span>
                                        <span>{{lastDevice.device_type}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="state_col">
                            <div class="text align_right">
                                <h2>&nbsp;</h2>
                                <ul class="list_none">
                                    <li>
                                        <span>Last Login:</span>
                                        <span>{{mainObj.userObj.lastLogin}}</span>
                                    </li>
                                    <li>
                                        <span>Most Recent Location</span><br>
                                        <div id="myMap" style="height: 115px; width: 100%;"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="new_padding_div">
                        <ul class="tabs stats_tabs list_none inline_list">
                            <li v-bind:class="activetab == 'usertab1' ? 'active' : ''">
                                <a class="anchor_pointer" v-on:click="activetab = 'usertab1'">Overview</a></li>
                            <li v-bind:class="activetab == 'usertab2' ? 'active' : ''">
                                <a class="anchor_pointer" v-on:click="activetab = 'usertab2'">Engagement</a>
                            </li>
                            <li v-bind:class="activetab == 'usertab3' ? 'active' : ''">
                                <a class="anchor_pointer" v-on:click="activetab = 'usertab3'">Custom
                                    Attribute</a>
                            </li>
                            <li v-bind:class="activetab == 'usertab4' ? 'active' : ''">
                                <a class="anchor_pointer" v-on:click="activetab = 'usertab4'">Device Types</a>
                            </li>

                        </ul>
                        <div class="stats_tab_content" id="tab-content">
                            <div class="tab">
                                <keep-alive>
                                    <component v-bind:dataProp="mainObj[activetab]" v-bind:is="activetab"></component>
                                </keep-alive>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import usertab1 from '../../../authenticatedComponents/dashboardChildComponents/company/usertabs/usertab1';
    import usertab2 from '../../../authenticatedComponents/dashboardChildComponents/company/usertabs/usertab2';
    import usertab3 from '../../../authenticatedComponents/dashboardChildComponents/company/usertabs/usertab3';
    import usertab4 from '../../../authenticatedComponents/dashboardChildComponents/company/usertabs/usertab4';

    export default {
        name: "userstats",
        components: {
            usertab1,
            usertab2,
            usertab3,
            usertab4
        },
        data() {
            return {
                activetab: 'usertab1',
                userAttribute: [],
                userObj: {
                    'email': '',
                    'userId': '',
                    'deviceType': '',
                    'userName': '',
                    'country': '',
                    'appId': '',
                    'lastLogin': '',
                },
                mainObj: {
                    userObj: {},
                    usertab1: {},
                    usertab2: {},
                    usertab3: {},
                    usertab4: {}
                },
                lastDevice: {
                    row_id: '',
                    app_id: '',
                    app_name: ''
                }
            }
        },
        mounted() {
            $("#footer").css({display: "none"});
            let id = this.$route.params.id;
            this.getUserlist(id);
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
            this.bus.$off("targetUsers");
        },
        methods: {
            changeNotification(notification) {

                const payLoad = {
                    resource: "user/stats/notification",
                    action: "post",
                    data: {
                        userId: this.$route.params.id,
                        key: notification,
                        value: this.mainObj.userObj[notification] ? 1 : 0,
                    },
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders,
                ).then((response) => {
                    this.mainObj.usertab3.user[payLoad.data.key] = payLoad.data.value;
                }).catch((error) => {

                });

            },
            getUserlist(id) {
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    {
                        resource: "user/stats",
                        action: "get",
                        id: id
                    },
                    this.authHeaders
                ).then((response) => {
                    console.log('response 200',response);
                    if (response.data.meta.code == 405) {
                        this.$router.push('/notfound');
                    }
                    this.mainObj = response.data.data;
                    this.lastDevice = response.data.data.usertab4.attributeList.length > 0 ? response.data.data.usertab4.attributeList[0] : this.lastDevice;

                    if (this.mainObj.userObj.latitude != null && this.mainObj.userObj.longitude != null) {
                        let mapOptions = {
                            center: new google.maps.LatLng(this.mainObj.userObj.latitude, this.mainObj.userObj.longitude),
                            streetViewControl: false,
                            mapTypeControl: false,
                            zoom: 8
                        };
                        let map = new google.maps.Map(document.getElementById('myMap'), mapOptions);
                    } else {
                        var geocoder = new google.maps.Geocoder();
                        var address = this.mainObj.userObj.country;
                        geocoder.geocode({'address': address}, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                let mapOptions = {
                                    center: new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng()),
                                    streetViewControl: false,
                                    mapTypeControl: false,
                                    zoom: 8
                                };
                                let map = new google.maps.Map(document.getElementById('myMap'), mapOptions);
                            }
                        });
                    }


                }).catch((error) => {
                    console.log('error',error);
                })
            },
        }
    }
</script>

<style scoped>

</style>