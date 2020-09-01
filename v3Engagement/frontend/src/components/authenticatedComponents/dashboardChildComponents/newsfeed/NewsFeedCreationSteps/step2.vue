<template>
    <div class="tab" id="delivery_tab">
        <div class="inner_tab_content add">
            <div class="table_header_holder news_feed">
                <div class="col">
                    <div class="holder">
                        <select class="add" v-if="step2Prop != undefined" v-model="step2.segment">
                            <option v-bind:value="-1">Please select Segment</option>
                            <option v-for="segment in step2Prop.segments" v-bind:value="segment.id">{{segment.name}}
                            </option>
                        </select>
                        <p class="error">{{errors.segment.message}}</p>
                    </div>
                </div>
                <div class="col">
                    <div class="holder">
                        <select class="add" v-if="step2Prop != undefined" v-model="step2.location">
                            <option v-bind:value="-1">Please select Location</option>
                            <option v-for="location in step2Prop.locations" v-bind:value="location.id">{{location.name}}
                            </option>
                        </select>
                        <p class="error">{{errors.location.message}}</p>
                    </div>
                </div>
            </div>
            <div class="inner_tab_text active" id="schedule">
                <div class="inner_tab_widget">
                    <div class="heading_holder">
                        <span>Time Zone: <em>UTC</em></span>
                        <h2>Newsfeed Duration</h2>
                    </div>
                    <div class="txt_holder">
                        <div class="row">
                            <strong>Start Time (Required)</strong>
                            <input type="date" class="datepicker hasDatepicker"
                                   placeholder="mm/dd/yyyy" id="dp1541541165038" v-model="step2.dateTime.start.date">
                            <i>at</i>
                            <select v-model="step2.dateTime.start.hours">
                                <option v-for="n in 24" v-bind:value="n-1">{{n - 1}}</option>
                            </select>
                            <select v-model="step2.dateTime.start.mints">
                                <option v-for="n in 60" v-bind:value="n-1">{{n - 1}}</option>
                            </select>
                            <p class="error">{{errors.startDate.message}}</p>
                        </div>
                        <div class="row">
                            <strong>End Time (Optional)</strong>
                            <input type="date" class="datepicker hasDatepicker"
                                   placeholder="mm/dd/yyyy" id="dp1541541165039" v-model="step2.dateTime.end.date"
                                   v-bind:min="step2.dateTime.start.date">
                            <i>at</i>
                            <select v-model="step2.dateTime.end.hours">
                                <option v-for="n in 24" v-bind:value="n-1">{{n - 1}}</option>
                            </select>
                            <select v-model="step2.dateTime.end.mints">
                                <option v-for="n in 60" v-bind:value="n-1">{{n - 1}}</option>
                            </select>
                        </div>
                        <div class="sec_hidden">
                            <div class="row">
                                <input checked="" type="checkbox" id="allow_users">
                                <label for="allow_users"><i class="far fa-square"></i> Show NFC to users
                                    in their local time zone</label>
                            </div>
                            <div class="row">
                                <p><b>Summary:</b> Send Campaign immediately after trigger criteria are met, beginning
                                    Today at 5:45PM.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props: ['step2Prop', 'newsFeedId', 'hitApi', 'getNewsFeedStep'],
        data() {
            return {
                locked: true,
                step2: {
                    step: 'delivery',
                    segment: -1,
                    location: -1,
                    dateTime: {
                        start: {
                            date: "",
                            hours: 0,
                            mints: 0
                        },
                        end: {
                            date: "",
                            hours: 0,
                            mints: 0
                        }
                    },
                },
                errors: {
                    location: {
                        message: '',
                    },
                    segment: {
                        message: '',
                    },
                    startDate: {
                        message: '',
                    },
                    endDate: {
                        message: '',
                    }
                }
            };
        },
        mounted() {
            this.populateStartDateUtc();
        },
        methods: {
            populateStartDateUtc() {
                var isoDate = new Date().toISOString();
                this.step2.dateTime.start.date = isoDate.split(":")[0].split("T")[0];
                this.step2.dateTime.start.hours = parseInt(isoDate.split(":")[0].split("T")[1]);
                this.step2.dateTime.start.mints = parseInt(isoDate.split(":")[1]);
            },
            submitStep() {
                this.locked = false;
                if (this.validate()) {

                    const payLoad = {
                        resource: "newsfeeds",
                        action: "update",
                        id: this.newsFeedId,
                        data: JSON.parse(JSON.stringify(this.step2))
                    };

                    this.api("post",
                        this.constants.getUrl("backendApiUrl"),
                        payLoad,
                        this.authHeaders,
                        true,
                    ).then((response) => {
                        if (response.data.meta.code === 200) {
                            this.$emit("goNext");
                        }
                    }).catch((error) => {

                    });
                }
            },
            validate() {
                let success = true;
                let startDate = this.step2.dateTime.start.date != '' ? this.step2.dateTime.start.date + '-' + ('0' + this.step2.dateTime.start.hours).slice(-2) + '-' + ('0' + this.step2.dateTime.start.mints).slice(-2) : '';
                let endDate = this.step2.dateTime.end.date != '' ? this.step2.dateTime.end.date + '-' + ('0' + this.step2.dateTime.end.hours).slice(-2) + '-' + ('0' + this.step2.dateTime.end.mints).slice(-2) : '';


                /*if (this.step2.segment == -1) {
                    success = false;
                    this.errors.segment.message = 'Segment is required';

                } else {
                    this.errors.segment.message = '';
                }*/

                /*if (this.step2.location == -1) {
                    success = false;
                    this.errors.location.message = 'Location is required';

                } else {
                    this.errors.startDate.message = '';
                }*/

                if (startDate == '') {
                    success = false;
                    this.errors.startDate.message = 'Start Date is Required';

                } else {
                    this.errors.startDate.message = '';
                }

                if (endDate != '' && startDate != '') {
                    if (!(endDate > startDate)) {
                        success = false;
                        this.errors.startDate.message = 'Start Date should not be greater than End Date';
                    } else {
                        this.errors.startDate.message = '';
                    }
                }
                return success;
            }
        },
        watch: {
            step2Prop: {
                handler(currVal, oldVal) {
                    //this.step2.segment = currVal.segments[0] != undefined ? currVal.segments[0].id : '';
                    //this.step2.location = currVal.locations[0] != undefined ? currVal.locations[0].id : '';
                },
                deep: true
            },
            hitApi: {
                handler(currVal, oldVal) {
                    if (currVal.step == 2) {
                        this.submitStep();
                    }
                },
                deep: true
            },
            getNewsFeedStep: {
                handler(currVal, oldVal) {
                    let val = JSON.stringify(currVal);
                    if (val != '{}') {
                        this.step2 = JSON.parse(val);
                    }
                },
                deep: true
            },
            step2: {
                handler(currVal, oldVal) {
                    if (!this.locked) {
                        this.validate();
                    }
                },
                deep: true
            },
        }
    }
</script>

<style>

</style>