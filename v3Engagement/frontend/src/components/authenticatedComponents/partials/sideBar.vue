<template>
    <aside id="sidebar" class="">
        <ul class="first_level list_none">
            <li v-if="is_admin == 0" class="add_groups">
                <a class="inner_drop_opener" style="color: #fff">
                    <i v-bind:class="appGroupLogo!=''?'':'fas fa-layer-group'">
                        <img v-if="appGroupLogo" v-bind:src="appGroupLogo" height="20px" width="20px"/>
                        <img v-else src="../../../assets/images/logo.png" height="20px" width="20px"/>
                    </i>
                    {{appGroupName}}
                </a>
                <ul class="left_inner_menu list_none groups_menu">
                    <li><a class="add_app_btn" v-on:click="appGroup" style="cursor: pointer"><i
                            class="fas fa-plus-circle"></i> New App Group</a></li>
                    <div class="scroll_div inner_group_ul">
                        <li v-for="row in appgrouplisting">
                            <div v-if="row.is_default==1">
                                <a class="active" style="cursor: pointer" v-bind:id="row.id"
                                   v-on:click="updateAppGroupStatus(row.id,row.is_default)">
                                    <img v-if="row.logo" v-bind:src="row.logo" height="20px" width="20px">
                                    <img v-else src="../../../assets/images/logo.png" height="20px" width="20px"/>
                                    <em class="row_name">{{row.name }}</em>
                                </a>
                            </div>
                            <div v-else>
                                <i class="fas fa-edit" v-on:click="editAppGroup(row)"></i>
                                <a style="cursor: pointer" v-bind:id="row.id"
                                   v-on:click="updateAppGroupStatus(row.id,row.is_default)">
                                    <img v-if="row.logo" v-bind:src="row.logo" height="20px" width="20px">
                                    <img v-else src="../../../assets/images/logo.png" height="20px" width="20px"/>
                                    <em class="row_name">{{row.name}}</em>
                                </a>
                            </div>
                        </li>
                    </div>
                </ul>
            </li>
            <li v-if="is_admin == 0" class="anchor_pointer"
                v-bind:class="$route.path == '/dashboard/lookup-listing' || $route.path == '/dashboard/app-listing' || $route.path == '/dashboard/import-listing' || $route.path == '/dashboard/attribute-listing' || $route.path === '/dashboard/attribute/import-attribute-data' ? 'active': ''">
                <a class="inner_drop_opener" href="#"><i aria-hidden="true" class="fa fa-cog"></i> Settings</a>
                <ul class="left_inner_menu list_none" style="display: none;">
                    <li v-bind:class="$route.path == '/dashboard/app-listing' ? 'active': ''">
                        <router-link to="/dashboard/app-listing"><i class="fas fa-list-ul"></i> App List</router-link>
                    </li>
                    <li v-bind:class="$route.path == '/dashboard/import-listing' || $route.path === '/dashboard/attribute/import-attribute-data' ? 'active': ''">
                        <router-link to="/dashboard/import-listing"><i aria-hidden="true" class="fa fa-download"></i>
                            Import List
                        </router-link>
                    </li>
                    <li v-bind:class="$route.path == '/dashboard/attribute-listing' ? 'active': ''">
                        <router-link to="/dashboard/attribute-listing"><i class="fas fa-list-ul"></i> Attribute List
                        </router-link>
                    </li>
                </ul>
            </li>
            <li v-if="is_admin == 0"
                v-bind:class="$route.path == '/dashboard/location-Listing' ? 'active': ''">
                <router-link to="/dashboard/location-Listing"><i class="fa fa-map-marker"></i> Location</router-link>
            </li>
            <li v-if="is_admin == 0" class="anchor_pointer"
                v-bind:class="$route.path == '/dashboard/campaign-listing' || $route.path == '/dashboard/campaign-create' || $route.path.includes('/dashboard/campaign-stats/') || $route.path == '/dashboard/campaign/capping-settings' ? 'active': ''">
                <a class="inner_drop_opener" href="#"><i class="fas fa-bullhorn"></i> Campaign</a>
                <ul class="left_inner_menu list_none" style="display: none;">
                    <li v-bind:class="$route.path == '/dashboard/campaign-listing' ? 'active': ''">
                        <router-link to="/dashboard/campaign-listing"><i class="fas fa-list-ul"></i> List</router-link>
                    </li>
                    <li v-bind:class="$route.path == '/dashboard/campaign/capping-settings' ? 'active': ''">
                        <router-link to="/dashboard/campaign/capping-settings"><i class="fas fa-signal"></i> Frequency
                            Capping
                        </router-link>
                    </li>
                </ul>
            </li>
            <li v-if="is_admin == 0"
                v-bind:class="$route.path == '/dashboard/segment-listing' || $route.path === '/dashboard/segment-create' ? 'active': ''">
                <router-link to="/dashboard/segment-listing"><i class="fas fa-users"></i> Segments</router-link>
            </li>
            <li v-if="is_admin == 0"
                v-bind:class="$route.path == '/dashboard/newsFeed-Listing' || $route.path === '/dashboard/newsfeed-create' ? 'active': ''">
                <router-link to="/dashboard/newsFeed-Listing"><i class="fas fa-newspaper"></i> Newsfeed</router-link>
            </li>
            <li v-if="is_admin == 0"
                v-bind:class="$route.path == '/dashboard/gallery-listing' ? 'active': ''">
                <router-link to="/dashboard/gallery-listing"><i class="fas fa-images"></i> Gallery</router-link>
            </li>
            <li v-if="is_admin == 0"
                v-bind:class="$route.path === '/dashboard/company-userListing' || $route.path.includes('/dashboard/user-stats/') ? 'active': ''">
                <router-link to="/dashboard/company-userListing"><i class="fas fa-user-circle"></i> User</router-link>
            </li>
            <!--admin routes starts-->

            <li v-if="is_admin == 1"
                v-bind:class="$route.path == '/dashboard/company-listing' ? 'active': ''">
                <router-link to="/dashboard/company-listing"><i class="fas fa-users"></i> Company</router-link>
            </li>
            <li v-if="is_admin == 1"
                v-bind:class="$route.path == '/dashboard/cache' ? 'active': ''">
                <router-link to="/dashboard/cache"><i class="fas fa-book"></i> Cache Viewer</router-link>
            </li>
            <li v-if="is_admin == 1"
                v-bind:class="$route.path == '/dashboard/language-listing' ? 'active': ''">
                <router-link to="/dashboard/language-listing"><i class="fas fa-list-ul"></i>Language</router-link>
            </li>
            <li v-if="is_admin == 1"
                v-bind:class="$route.path == '/dashboard/package-listing' ? 'active': ''">
                <router-link to="/dashboard/package-listing"><i class="fa fa-envelope-open" aria-hidden="true"></i>Package
                </router-link>
            </li>
            <li v-if="is_admin == 1" class="anchor_pointer"
                v-bind:class="$route.path == '/dashboard/campaign-queue-listing' ? 'active': ''">
                <a class="inner_drop_opener" href="#"><i aria-hidden="true" class="fa fa-cog"></i> Settings</a>
                <ul class="left_inner_menu list_none" style="display: none;">
                    <li v-bind:class="$route.path == '/dashboard/campaign-queue-listing' ? 'active': ''">
                        <router-link to="/dashboard/campaign-queue-listing"><i class="fas fa-search-location"></i>
                            Campaign Queue
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</template>
<script>
    export default {
        name: "sidebar",
        props: ['listingApp'],
        data() {
            return {
                appGroupName: '',
                appGroupLogo: '',
                appgrouplisting: [],
                is_admin: this.$store.getters.getUserInfoGetters.is_admin
            }
        },
        mounted() {
            this.getAppGroupListing();
        },
        methods: {
            toggleUl() {
                $('.second_level').slideToggle();
            },
            getAppGroupListing() {
                var payload = {
                    resource: "groups",
                    action: "get",
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    this.appgrouplisting = [];
                    if (response.data.data.length > 0) {
                        for (var val = 0; val < response.data.data.length; val++) {
                            //   console.log('data',response.data.data);
                            this.appgrouplisting.push({
                                'name': response.data.data[val]['name'],
                                'id': response.data.data[val]['id'],
                                'is_default': response.data.data[val]['is_default'],
                                'logo': response.data.data[val]['logo'],
                                'description': response.data.data[val]['description'],
                            });
                            if (response.data.data[val]['is_default'] == 1) {
                                this.appGroupName = response.data.data[val]['name'],
                                    this.appGroupLogo = response.data.data[val]['logo']
                            }
                        }
                    } else {
                        this.appgrouplisting.push({
                            'name': '',
                        });
                    }
                }).catch((error) => {
                })
            },
            editAppGroup(row) {
                //  console.log('edit in side bar', row);
                this.$emit('editAppGroup', row, function (res) {
                    //         console.log('call back Response', res);
                });
            },
            updateAppGroupStatus: function (id, status) {
                var payload = {
                    resource: "groups/current",
                    action: "update",
                    id: id,
                    data: {
                        'status': status
                    }
                };
                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    this.authHeaders
                ).then((response) => {
                    this.getAppGroupListing();
                    this.bus.$emit('appGroupChanged');
                }).catch((error) => {
                })
            },
            appGroup() {
                this.$emit('updateAppGroup');
                $("#add_app_group").modal("show");
            }
        },
        watch: {
            listingApp(currVal, OldVal) {
                this.appgrouplisting = [];
                for (var val = 0; val < currVal.length; val++) {
                    this.appgrouplisting.push({
                        'name': currVal[val]['name'],
                        'id': currVal[val]['id'],
                        'logo': currVal[val]['logo'],
                        'description': currVal[val]['description'],
                        'is_default': currVal[val]['is_default']
                    });
                }
            }
        }
    }

</script>

<style scoped>

</style>