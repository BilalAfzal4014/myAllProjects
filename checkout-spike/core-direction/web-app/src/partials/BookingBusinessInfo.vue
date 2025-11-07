<template>
  <div>
    <div class="business-profile-info-box">
      <div class="business-profile-about-us-box">
        <p class="business-profile-title">
          About Us
        </p>
        <p class="business-profile-about-us-desc" v-html="about_corporate" />
      </div>
    </div>
  </div>
</template>

<script>
import * as toastr from "toastr";

export default {
    name: "BookingBusinessInfo",
    data() {
        return {
            about_corporate: "",
            address: "",
            phone: "",
            companyId: "",
            latitude: "",
            longitude: ""

        };
    },
    methods: {
        goToLocationPage() {
            this.$router.push({
                name: "SingleCompanyLocation",
                params: {id: this.companyId},
                query: {lat: this.latitude, lng: this.longitude}
            });
        }
    },
    created() {
        this.companyId = this.$route.params.id;
        this.latitude = this.$route.query.lat;
        this.longitude = this.$route.query.lng;
        let biography = {
            "id": this.companyId,
            "type": "biography",
            "lat": this.latitude,
            "lng": this.longitude
        };
        this.oldApi("post",
            this.constants.getUrl("biography"),
            biography
        ).then((response) => {
            this.about_corporate = response.data.about_corporate;
        }).catch((error) => {
            toastr.error(error[0].response.data.errors[0].error);
        });
        let basicInfo = {
            "id": this.companyId,
            "type": "basic-info",
            "lat": this.latitude,
            "lng": this.longitude
        };
        this.oldApi("post",
            this.constants.getUrl("getBusinessBasicInfo"),
            basicInfo
        ).then((response) => {
            this.address = response.data.address;

        }).catch((error) => {
            toastr.error(error[0].response.data.errors[0].error);
        });
    }
};
</script>

<style scoped>

</style>