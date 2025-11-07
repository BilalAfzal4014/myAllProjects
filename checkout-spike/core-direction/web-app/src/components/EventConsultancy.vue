<template>
  <div>
    <!-- IndustryPartner -->
    <section id="IndustryPartner">
      <div class="IndustryPartner-outer-box">
        <div class="IndustryPartner-inner-box">
          <div class="content-box">
            <div class="content-inner-box">
              <h1 class="title">
                Event Consultancy
              </h1>
              <h2 class="subtitle">
                Curating Inspiring Events.
              </h2>
              <p class="desc">
                Want to provide a world class fitness and wellness experience that features some of the world's best
                talent in the fitness industry? We have a proven track record of doing just that, we can even provide
                you with a fully white-labelled booking solution for your event.
              </p>
            </div>
          </div>
          <div class="form-box" style="background-image: url('../assets/images/ev1.jpeg');">
            <div class="form-outer-box">
              <div class="form-inner-box">
                <h1 class="title">
                  Want to find out more?
                </h1>
                <p class="desc">
                  Drop us an email on <a href="mailto:info@coredirection.com" target="_blank">info@coredirection.com</a>
                  or click the button below to submit your enquiry.
                </p>
                <a class="btn-register"
                   href="https://forms.zohopublic.com/coredirection/form/EventConsultancyForm/formperma/pVdQwfUibSJvVgNd18Im3pkNdXEPdrEx71QHQ0XeUpU"
                   target="_blank" rel="noopener noreferrer"
                >Let's talk about your event</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- brand -->
    <section id="brand">
      <div class="custom-container">
        <div class="brand-header">
          <h3 class="title">
            Our Partners
          </h3>
          <p class="desc">
            Take a look at the brands that have helped us in our movement to inspire movement.
          </p>
        </div>
        <div class="brand-body">
          <div class="img-box">
            <a href="https://dubaiholding.com/en/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-05.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.dubaifitnesschallenge.com/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-02.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.dubaisc.ae/en/pages/default.aspx" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-03.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.expo2020dubai.com/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-04.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.jumeirah.com/en" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-09.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://dmc.ae/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-06.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.dm.gov.ae/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-07.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.emaar.com/en/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-08.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.hqws.com/" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-01.jpg" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.majidalfuttaim.com/en" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-10.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.meraas.com/en" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-11.png" alt="">
            </a>
          </div>
          <div class="img-box">
            <a href="https://www.nakheel.com/en" target="_blank" class="brand-site">
              <img src="/assets/images/brand/brand-12.png" alt="">
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import {updateMetaInformation} from "../utils";
export default {
    name: "EventConsultancy",
    mounted(){
        updateMetaInformation("Event Consultancy | Core Direction","Core Direction, Coredirection,Event Consultancy, Event Consultancy Dubai, Event Consultant, event management, event manager, Dubai fitness challenge","Collaborating with government entities, developers, global brands and the region's top agencies to curate and deliver the most inspiring wellness events.");
    }
};
</script>

<style scoped>

</style>