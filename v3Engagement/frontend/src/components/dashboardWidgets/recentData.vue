<template>
    <div class="right_sidebar">
        <img id="user_default_image" style="display: none" src="../../assets/images/user.jpg">
        <img id="app_default_image" style="display: none" src="../../assets/images/app.svg">
        <div class="wid_holder">
            <div class="right_widget">
                <div class="widget_header">
                    <router-link to="/dashboard/company-userListing">View all</router-link>
                    <strong class="title">Recent Users</strong>
                </div>
                <ul class="list_none users">
                    <li v-for="(user, index) in recentUsers">
                        <router-link style="float: none" v-bind:to="'/dashboard/user-stats/' + user.row_id">
                            <img style="border-radius: 23px;" v-bind:src="user.image_url + '?'" alt="#"
                                 v-on:error="errorImgHandler(index, 'user')">
                            <div class="txt">
                                <strong>{{user.username}}</strong>
                                <p>{{user.device_type}} User</p>
                            </div>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
        <div class="wid_holder">
            <div class="right_widget">
                <div class="widget_header">
                    <router-link to="/dashboard/app-listing">View all</router-link>
                    <strong class="title">Popular Apps</strong>
                </div>
                <ul class="list_none users apps_list">
                    <li v-for="(app, index) in popularApps">
                        <!--<router-link style="float: none" v-bind:to="'/dashboard/user-stats/' + app.id">-->
                        <img v-bind:src="app.logo == null ? '': app.logo" alt="#"
                             v-on:error="errorImgHandler(index, 'app')">
                        <div class="txt">
                            <strong>{{app.name}}</strong>
                            <p>{{app.app_id}}</p>
                        </div>
                        <!--</router-link>-->
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                popularApps: [],
                recentUsers: []
            };
        },
        mounted() {
            this.getPopularApps();
            this.getRecentUsers();
        },
        methods: {
            getPopularApps() {
                const payLoad = {
                    resource: 'dashboard/recent-apps',
                    action: 'get',
                    data: {}
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.popularApps = response.data.data;
                }).catch((error) => {

                })
            },
            getRecentUsers() {
                const payLoad = {
                    resource: "dashboard/campaign-user/recent-users",
                    action: "get",

                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payLoad,
                    this.authHeaders
                ).then((response) => {
                    this.recentUsers
                        = response.data.data;
                }).catch((error) => {

                })
            },
            errorImgHandler(index, listingType) {

                if (listingType == 'user') {
                    this.recentUsers[index].image_url = $("#user_default_image").attr("src");
                } else {
                    this.popularApps[index].logo = $("#app_default_image").attr("src");
                }
            }
        }
    }
</script>

<style>

</style>