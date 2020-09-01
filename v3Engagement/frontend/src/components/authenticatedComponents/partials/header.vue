<template>
    <header id="header">

        <a class="logo" v-on:click="navigateDashboard">
            <img v-bind:src="$store.getters.getCompanyImageUrl">
        </a>

        <span class="slogan">
                <a class="nav_opener anchor_pointer"><img alt="#" src="../../../assets/images/nav_opener.png"></a>
                <em>{{user.name}}</em>
            </span>
        <ul class="list_none inline_list user_links">
            <li class="sec_hidden"><a class="anchor_pointer"><i class="fas fa-folder-open"></i></a></li>
            <li class="sec_hidden"><a class="anchor_pointer"><i class="far fa-chart-bar"></i></a></li>
            <li class="sec_hidden"><a class="anchor_pointer"><i class="far fa-envelope"></i></a></li>
            <li><a class="anchor_pointer"><i class="far fa-bell"></i></a>
            </li>
        </ul>
        <div class="float_right">
            <input class="header_search sec_hidden" placeholder="Search Anything Here..." type="text">
            <div class="logout_area">
                <a class="drop_opener" href="#"><i aria-hidden="true" class="fa fa-ellipsis-v"></i></a>
                <ul class="list_none profile_dropdown">
                    <li>
                        <router-link :to="'/dashboard/company-edit/'+ user.id">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </router-link>
                    </li>
                    <li><a style="cursor: pointer" v-on:click="logOut"><i class="fas fa-sign-out-alt"></i> Log Out </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                user: []
            }
        },
        mounted() {
            var payload = {
                resource: "user",
                action: "get",
                data: {}
            };
            this.api("post",
                this.constants.getUrl("backendApiUrl"),
                payload,
                this.authHeaders
            ).then((response) => {
                this.user = response.data;
                if (this.user.logo) {
                    this.$store.dispatch('companyImageUrlAction', this.user.logo);
                }

            }).catch((error) => {
            });
        },
        methods: {
            logOut() {
                this.api("post",
                    this.constants.getUrl("logOut"),
                    {},
                    this.authHeaders
                ).then((response) => {
                    this.removeToken();
                    this.$router.go("/login");
                }).catch((error) => {
                    this.removeToken();
                    this.$router.go("/login");
                });

            },
            navigateDashboard() {
                if (this.$store.getters.getUserInfoGetters.is_admin == 0) {
                    this.$router.push("/dashboard/dashboard-stats");
                }
            }
        }
    }
</script>

<style>

</style>