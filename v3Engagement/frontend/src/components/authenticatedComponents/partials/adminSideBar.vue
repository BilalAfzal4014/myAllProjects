<template>
    <aside id="sidebar" class="">
        <ul class="first_level list_none">
            <li v-bind:class="$route.path == '/dashboard/company-listing' ? 'active': ''">
                <router-link to="/dashboard/company-listing"><i class="fas fa-users"></i> Company</router-link>
            </li>
            <li v-bind:class="$route.path == '/dashboard/cache' ? 'active': ''">
                <router-link to="/dashboard/cache"><i class="fas fa-book"></i> Cache Viewer</router-link>
            </li>
            <li v-bind:class="$route.path == '/dashboard/language-listing' ? 'active': ''">
                <router-link to="/dashboard/language-listing"><i class="fas fa-list-ul"></i>Language</router-link>
            </li>
            <li v-bind:class="$route.path == '/dashboard/package-listing' ? 'active': ''">
                <router-link to="/dashboard/package-listing"><i class="fa fa-envelope-open" aria-hidden="true"></i>Package</router-link>
            </li>
            <li class="anchor_pointer"
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
                appgrouplisting: []
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
                            this.appgrouplisting.push({
                                'name': response.data.data[val]['name'],
                                'id': response.data.data[val]['id'],
                                'is_default': response.data.data[val]['is_default']
                            });
                            if (response.data.data[val]['is_default'] == 1) {
                                this.appGroupName = response.data.data[val]['name']
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
                }).catch((error) => {
                })
            },
        },
        watch: {
            listingApp(currVal, OldVal) {
                this.appgrouplisting = [];
                for (var val = 0; val < currVal.length; val++) {
                    this.appgrouplisting.push({
                        'name': currVal[val]['name'],
                        'id': currVal[val]['id'],
                        'is_default': currVal[val]['is_default']
                    });
                }
            }
        }
    }

</script>

<style>

</style>