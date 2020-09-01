<template>
    <div class="padding_content" style="padding-right: 0px;">
        <div class="main_content">
            <pinMap></pinMap>
            <div class="multi-timelines">
                <newsFeedStatsWidget></newsFeedStatsWidget>
                <userStatsWidget></userStatsWidget>
            </div>
            <div class="multi-timelines">
                <campaignStats></campaignStats>
                <conversionStats></conversionStats>
            </div>
        </div>
        <recentData></recentData>
    </div>
</template>

<script>
    import pinMap from '../../../dashboardWidgets/map';
    import recentData from '../../../dashboardWidgets/recentData';
    import campaignStats from '../../../dashboardWidgets/campaignStatsWidget';
    import conversionStats from '../../../dashboardWidgets/conversionStatsWidget';
    import newsFeedStatsWidget from '../../../dashboardWidgets/newsFeedStatsWidget';
    import userStatsWidget from '../../../dashboardWidgets/userStatsWidget';

    export default {
        name: "dashboardStats",
        components: {
            pinMap,
            recentData,
            campaignStats,
            conversionStats,
            newsFeedStatsWidget,
            userStatsWidget
        },
        data() {
            return {
            }
        },
        mounted() {
            $("#footer").css({display: "none"});
        },
        beforeDestroy() {
            $("#footer").css({display: "block"});
        },
    }
</script>

<style>

</style>