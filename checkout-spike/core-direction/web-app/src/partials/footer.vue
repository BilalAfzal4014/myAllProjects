<template>
  <footer id="footer">
    <div class="custom-container footer-top">
      <div class="info">
        <coredirection-logo />
        <ul role="list">
          <li role="listitem" class="mb-3">
            <p class="footer-desc">
              {{ FOOTER_DESCRIPTION }}
            </p>
          </li>
          <ul role="group" class="social-list">
            <li v-for="socialLink in FOOTER_SOCIAL_LINKS" :key="socialLink.platform" role="listitem" class="social-item">
              <a :href="socialLink.url" class="social-link" target="_blank">
                <img :src="socialLink.icon" width="29" height="29" :alt="socialLink.url">
              </a>
            </li>
          </ul>
        </ul>
        <div class="app-store-link-box">
          <a href="https://apps.apple.com/gb/app/core-direction/id1644608047">
            <img src="@/assets/images/btn-store-01-cropped.png" class="img-fluid" alt="Apple Store Button" width="175px" height="74">
          </a>
          <a href="https://play.google.com/store/apps/details?id=com.core_track">
            <img src="@/assets/images/btn-store-02-cropped.png" class="img-fluid" alt="Google Store Button" width="175px" height="74">
          </a>
        </div>
      </div>
      <div class="links">
        <p class="font-bold">
          Company
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/corporate-wellness" target="_blank">
              Corporate Wellness
            </a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/events" target="_blank">
              Event Consultancy
            </a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/wellness-technology" target="_blank">
              Wellness Technology
            </a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coreunityfest.com" target="_blank">
              COREUNITY Festival
            </a>
          </li>
        </ul>
      </div>
      <div class="links">
        <p class="font-bold">
          Explore
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com" target="_blank">
              About us</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/blog" target="_blank">
              Blogs</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://my.coredirection.com/listing" target="_blank">
              Book
              Activity</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/user-guides-home" target="_blank">
              User
              Guides</a>
          </li>
        </ul>
      </div>
      <div class="links">
        <p class="font-bold">
          Legal
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/privacy-policy" target="_blank">
              Privacy Policy</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/terms-and-conditions" target="_blank">
              Terms & Conditions</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/cookie-policy" target="_blank">
              Cookie
              Policy</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/contact-us" target="_blank">
              Contact
              Us</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="custom-container footer-mid">
      <div class="mid-links">
        <p class="font-bold">
          my.coredirection
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://my.coredirection.com/signup" target="_blank">
              Create Account</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://qr.coredirection.com/u/i2Z6czMN" target="_blank">
              Download the App</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/user-guides-home" target="_blank">
              Platform User Guides</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/challenges" target="_blank">
              Upcoming
              Challenges</a>
          </li>
          <li role="listitem" class="list-item">
            <a
              href="https://forms.coredirection.com/coredirection/form/BecomeaPartner/formperma/vx24_DxIld48OawAP2R1lcZxNoNFGKpcz7LP6PghWSQ" target="_blank"
            >
              Become a Partner</a>
          </li>
        </ul>
      </div>
      <div class="mid-links">
        <p class="font-bold">
          Corporate Wellness
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/corporate-wellness" target="_blank">
              Corporate Wellness Home</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/corporatewellness/events" target="_blank">
              Browse Wellness Events</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://meeting.coredirection.com/#/customer/dwayne" target="_blank">
              Book a Meeting</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/corporatewellness/pricing" target="_blank">
              Platform Pricing</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/blog-posts/corporate-wellness-blogs" target="_blank">
              Corporate Wellness Blogs</a>
          </li>
        </ul>
      </div>
      <div class="mid-links">
        <p class="font-bold">
          Event Consultancy
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/events" target="_blank">
              Events
              Home</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/events#Dubai-Fitness-Challenge" target="_blank">
              Dubai Fitness Challenge</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/events#COREUNITY-Festival" target="_blank">
              COREUNITY Festival</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/events#AlUla-Wellness-Festival" target="_blank">
              AlUla Wellness Festival</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/events#EXPO-2020-Dubai" target="_blank">
              EXPO 2020 Dubai</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/event-consultancy/contact" target="_blank">
              Contact Events Team</a>
          </li>
        </ul>
      </div>
      <div class="mid-links">
        <p class="font-bold">
          Wellness Technology
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/wellness-technology" target="_blank">
              Wellness Tech Home</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/blog-posts/technology-blogs" target="_blank">
              Wellness Tech Blogs</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/wellness-technology" target="_blank">
              Whitelabel Booking Technology</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/technology/contact" target="_blank">
              Technology Enquiry Form</a>
          </li>
        </ul>
      </div>
      <div class="mid-links">
        <p class="font-bold">
          Core Direction
        </p>
        <ul role="list">
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com#mission" target="_blank">
              Core
              Mission</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/team" target="_blank">
              Core Team</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://explore.coredirection.com/" target="_blank">
              Link
              Tree</a>
          </li>
          <li role="listitem" class="list-item">
            <a href="https://coredirection.com/contact-us" target="_blank">
              Contact
              Us</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="custom-container footer-bottom">
      <p class="copyright">
        {{ COPYRIGHT_TEXT }}
      </p>
    </div>
  </footer>
</template>

<script>
import {COPYRIGHT_TEXT, FOOTER_DESCRIPTION, FOOTER_LINKS, FOOTER_SOCIAL_LINKS} from "@/constants/footer";
import CoredirectionLogo from "@/svgs/coredirection-logo";

export default {
  name: "FooterComponent",
  components: {CoredirectionLogo},
  data() {
    return {
      ourService: process.env.VUE_APP_OUR_SERVICE,
      FOOTER_DESCRIPTION,
      FOOTER_SOCIAL_LINKS,
      FOOTER_LINKS,
      COPYRIGHT_TEXT,
      isFooterDropdownHide: false
    };
  }
};
</script>

<style scoped>
#footer {
  padding-top: 70px;
  background-color: #fff;
  position: relative;
  overflow: hidden;
}
#footer::after{
  content: "";
  position: absolute;
  width: 320px;
  height: 320px;
  right: 0;
  bottom: -22px;
  background-image: url("../assets/images/coreicon_colored.svg");
  background-position: bottom right;
  background-repeat: no-repeat;
  z-index: -1;
}

/* Remove list styles on ul, ol elements with a list role, which suggests default styling will be removed */
ul[role="list"],
ol[role="list"],
ul[role="group"],
ol[role="group"] {
  list-style: none;
  margin: 0;
  padding: 0;
}

#footer img {
  display: inline-block;
}

#footer .footer-top {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: flex-start;
  -ms-flex-align: flex-start;
  align-items: flex-start;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 20px;
  column-gap: 20px;
  row-gap: 50px;
  padding-bottom: 30px;
}

#footer a.social-link {
  display: block;
}
#footer .footer-mid {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: flex-start;
  -ms-flex-align: flex-start;
  align-items: flex-start;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 20px;
  column-gap: 20px;
  row-gap: 50px;
  border-top: 1px solid #eee;
  margin: 0 auto;
}

@media screen and (min-width: 992px) {
  #footer .footer-top {
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
  #footer .footer-mid {
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
}
@media screen and (max-width: 767px) {
  #footer::after{
    width: 220px;
    height: 220px;
  }
  #footer .custom-container .mid-links{
    margin-top: 0 !important;
  }
  #footer .custom-container .mid-links:first-child{
    margin-top: 30px !important;
  }
  #footer .footer-mid {
    row-gap: 0;
  }
  #footer .mid-links p {
    font-size: 16px !important;
  }
  #footer .mid-links li {
    font-size: 14px !important;
  }
}

@media screen and (max-width: 500px) {
  #footer .footer-top {
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
}

#footer .custom-container .info {
  width: 100%;
  max-width: 373px;
  margin-right: 60px;
  margin-top: -5px;
}

#footer .custom-container .footer-desc {
  font-size: 14px !important;
}
@media screen and (max-width: 500px) {
  #footer .custom-container .info {
    margin-right: 0;
  }
}

#foote .custom-container .info img {
  width: 100%;
  max-width: 300px;
  margin-bottom: 10px;
}

#footer .custom-container .info p {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 24px;
  margin-top: 11px;
  color: #000;
  text-align: justify;
}

@media screen and (max-width: 991px) {
  #footer .custom-container .info p {
    line-height: 22px;
  }
}

#footer .custom-container .app-store-link-box {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  column-gap: 10px;
  row-gap: 10px;
  margin-top: 10px;
}
#footer .custom-container .links {
  width: 100%;
  max-width: 200px;
  margin-right: auto;
  color: #000;
}

#footer .custom-container .mid-links {
  width: 100%;
  max-width: 200px;
  color: #000;
  margin: 30px 0;
}

#footer .mid-links p {
  font-family: 'Montserrat', sans-serif;
  color: #444;
  font-weight: 600;
  font-size: 14px;
  margin-bottom: 1rem;
}

#footer .mid-links li {
  font-family: 'Montserrat', sans-serif;
  color: #777;
  font-weight: 400;
  font-size: 12px;
  line-height: 2;
}

#footer .mid-links a {
  color: #777;
}

#footer .mid-links a:hover {
  color: #690fad;
}

@media screen and (max-width: 991px) {
  #footer .custom-container .links {
    margin-right: 0;
  }
}

#footer .links p {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 700;
  line-height: 28px;
  margin-bottom: 34px;
  color: #000;
}

#footer .custom-container .links button,
#footer .custom-container .links a {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 40px;
  color: #000;
}

#footer .custom-container .links button:hover,
#footer .custom-container .links a:hover {
  color: #690fad;
	  font-weight: 500;
}

#footer .custom-container .links:nth-last-child(1) {
  margin-right: 0;
}

#footer .social-list {
  margin-top: 15px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
}

#footer .footer-bottom {
  border-top: 1px solid #222;
}

#footer .copyright {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 14px;
  letter-spacing: 0em;
  text-align: center;
  padding-top: 30px;
  padding-bottom: 30px;
  color: #000;
  margin-bottom: 0;
}
</style>
