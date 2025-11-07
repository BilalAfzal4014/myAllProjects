<template>
  <div>
    <!-- IndustryPartner -->
    <section id="IndustryPartner">
      <div class="IndustryPartner-outer-box">
        <div class="IndustryPartner-inner-box">
          <div class="content-box">
            <div class="content-inner-box">
              <h1 class="title">
                Partner with us
              </h1>
              <h2 class="subtitle">
                Help us to inspire movement
              </h2>
              <p class="desc">
                Apply to list your business on the Core Direction marketplace and join our movement to inspire movement.
                Once your business profile is live you will be able to list class &amp; membership options and manage
                bookings and payments through our user friendly backend system.
              </p>
            </div>
          </div>
          <div class="form-box" style="background-image: url('../assets/images/bg-001.jpeg');">
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
                   href="https://forms.zohopublic.com/coredirection/form/IndustryPartnerForm/formperma/0mZrgNdJvJDDZCpx-9EJ5n7DD3ICWljvHeyPs83YZvk"
                   target="_blank" rel="noopener noreferrer"
                >Apply to be a partner</a>
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
    name: "OurPartner",
    mounted() {
        updateMetaInformation("Partner With Us | Core Direction", "Core Direction, Coredirection, fitness booking system, booking management system, glofox, zingfit, mindbody, Dubai", "Apply to list your business on the Core Direction marketplace and join our movement to inspire movement.");
    }
};
</script>

<style scoped>

</style>