<template>
  <div class="delivery-type-filter-box">
    <div class="custom-container">
      <div class="flex items-center justify-between">
        <div class="title-box">
          <h4 class="activity-listing-title capitalize">
            Delivery Type
          </h4>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="custom-container">
        <div class="content-container">
          <!-- Slider main container -->
          <div class="swiper-container mass-events-swiper-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-free-mode">
            <!-- Additional required wrapper -->
            <div id="swiper-wrapper-2faeb10d6292da7b" class="swiper-wrapper" aria-live="polite">
              <!-- Slides -->
              <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 2">
                <button @click="checkClassDeliveryTypeIsPhysical">
                  <div class="btn-filter">
                    <div class="btn-filter-icon-box">
                      <img src="/assets/images/Physical.png">
                    </div>
                    <p class="btn-filter-content-box">
                      On Location
                    </p>
                  </div>
                </button>
              </div>
              
              <!-- Slides -->
              <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 2">
                <button type="button" @click="checkClassDeliveryTypeIsOnline">
                  <div class="btn-filter">
                    <div class="btn-filter-icon-box">
                      <img src="/assets/images/online.png">
                    </div>
                    <p class="btn-filter-content-box">
                      Online
                    </p>
                  </div>
                </button>
              </div>
            </div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "DeliveryTypeFilter",
    data(){
        return{
            isOnline:false,
            isPhysical:false
        };
    },
    methods:{
        checkClassDeliveryTypeIsOnline(){
            this.isOnline = !this.isOnline;
            this.$emit("online",this.isOnline);
        },
        checkClassDeliveryTypeIsPhysical(){
            this.isPhysical = !this.isPhysical;
            this.$emit("physical",this.isPhysical);
        }
    }
};
</script>

<style scoped>

.delivery-type-filter-box{
  width: 100%;
}
.delivery-type-filter-box .swiper-wrapper{
  flex-wrap: wrap !important;
}
.delivery-type-filter-box .section-body .swiper-container {
  margin-right: 0;
  width: 100%;
}
.delivery-type-filter-box .btn-filter{
  margin-bottom: 15px;
}
</style>