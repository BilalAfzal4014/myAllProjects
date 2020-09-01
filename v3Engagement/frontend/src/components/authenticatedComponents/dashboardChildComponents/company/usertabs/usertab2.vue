<template>
    <div id="usertab2" v-bind:class="activetab === 'usertab2' ? 'tab active' : 'tab'">
        <div class="state_cols">
            <div class="state_column">
                <strong class="title"><i class="fas fa-envelope-open"></i> Campaign Email Sent</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.campaign">
                            <tr v-for="value in dataProp.campaign"
                                v-if="value.campaign_type.toLowerCase() == 'email'">
                                <td>{{value.name}}</td>
                                <td>{{value.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="state_column">
                <strong class="title"><i class="fas fa-mobile-alt"></i> Campaign Push Sent</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.campaign">
                            <tr v-for="value in dataProp.campaign"
                                v-if="value.campaign_type.toLowerCase() == 'push'">
                                <td>{{value.name}}</td>
                                <td>{{value.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="state_cols">
            <div class="state_column">
                <strong class="title"><i class="far fa-chart-bar"></i> Campaign Inapp Sent</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.campaign">
                            <tr v-for="value in dataProp.campaign"
                                v-if="value.campaign_type.toLowerCase() == 'inapp'">
                                <td>{{value.name}}</td>
                                <td>{{value.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="state_column">
                <strong class="title"><i class="fas fa-mobile-alt"></i> News Feed Cards Clicked</strong>
                <div class="text">
                    <div class="table_holder default_scroll">
                        <table class="display">
                            <thead>
                            <tr>
                                <th>Link</th>
                                <th>Device Type</th>
                                <th class="auto">Date</th>
                            </tr>
                            </thead>
                            <tbody v-if="dataProp.newsFeed !== undefined">
                            <tr v-for="value in dataProp.newsFeed">
                                <td><a v-bind:href="value.actual_url">{{value.actual_url}}</a></td>
                                <td>{{value.device_type}}</td>
                                <td>{{value.created_at}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "userTabTwo",
        props: ['dataProp', 'activetab'],
        mounted() {
            //alert('tab2');
        }
    }
</script>

<style scoped>

</style>