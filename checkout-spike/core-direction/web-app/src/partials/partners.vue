<template>
  <div>
    <section id="our-partners">
      <section-heading-component name="Our Partners" title="See All" url="#" />
      <div class="section-body">
        <div class="custom-container">
          <div class="content-container">
            <!-- Slider main container -->
            <div class="swiper-container our-partners-swiper-container">
              <!-- Additional required wrapper -->
              <div class="swiper-wrapper">
                <!-- Slides -->
                <partner-image-component path="/assets/images/our-partners/our-partners-01.png" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-02.jpeg" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-03.jpeg" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-04.jpeg" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-05.jpeg" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-06.jpeg" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-07.png" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-08.png" title="partner" />
                <partner-image-component path="/assets/images/our-partners/our-partners-09.jpeg" title="partner" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <subscribe-component />
  </div>
  <!-- our-partners -->
</template>

<script>
import subscribeComponent from "./subscribe";
import sectionHeadingComponent from "./section-heading";
import partnerImageComponent from "./partner-image";
export default {
    components:{
        subscribeComponent,
        sectionHeadingComponent,
        partnerImageComponent
    },
    name: "PartnersComponent"
};
</script>

<style scoped>

</style>