<template>
  <div>
    <navbar />
    <main id="main">
      <section id="calendar">
        <div class="custom-container">
          <div class="section-header flex items-center justify-between">
            <div class="title-box">
              <h4 class="section-title capitalize">
                Calendar
              </h4>
            </div>
          </div>
          <VueCalendar :events="events" />
        </div>
      </section>
      <section id="bookings">
        <UpComingBookingByDay v-if="isUpcoming" />
      </section>
    </main>
    <footer-component />
  </div>
</template>

<script>
import Navbar from "@/partials/navbar";
import FooterComponent from "@/partials/footer";
import VueCalendar from "@/components/VueCalendar";
import UpComingBookingByDay from "@/partials/booking/UpComingBookingByDay";
import * as toastr from "toastr";
export default {
    name: "SeeAllBooking",
    components: {UpComingBookingByDay,  VueCalendar, FooterComponent, Navbar},
    data(){
        return {
            events:[],
            isHistory:false,
            isUpcoming:false,
        };
    },
    created(){
        let type = this.$route.query.type;
        if(type == "history"){
            this.isHistory = true;
      
        } else {
            this.isUpcoming = true;
      
      
        }
    }
  
};
</script>

<style scoped>

</style>