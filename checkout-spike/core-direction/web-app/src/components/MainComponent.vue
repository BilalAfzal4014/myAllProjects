
<template>
  <div class="wrapper">
    <!-- header -->
    <header id="header">
      <div class="container">
        <a href="/" class="brand">
          <img src="/assets/images/logo.svg" alt="" class="brand-img">
        </a>
        <nav class="main-navbar">
          <ul class="navbar-navs" role="list">
            <li class="nav-items">
              <router-link href="/about-use" class="nav-link">
                About Us
              </router-link>
            </li>
            <li class="nav-items">
              <router-link href="/contact-us" class="nav-link">
                Contact Us
              </router-link>
            </li>
            <li class="nav-items">
              <button class="nav-link btn-more">
                ?
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </header>
    
    <!-- main -->
    <main id="main">
      <!-- our services -->
      <section id="our-services">
        <div class="container">
          <div class="content-box">
            <div class="content-inner-box option2">
              <button class="accordion title2" type="button">
                Marketplace
              </button>
              <div class="panel">
                <div class="panel-inner-box">
                  <p class="accordion-desc">
                    A coalition of fitness, health &amp; wellness businesses and activity providers working together to collectively inspire movement, health &amp; happiness by providing the region's largest wellness focused marketplace and booking engine.
                  </p>
                  <a class="btn-more" href="http://my.coredirection.com" target="_blank" rel="noopener noreferrer">Explore More <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.5303 6.53033C21.8232 6.23744 21.8232 5.76257 21.5303 5.46967L16.7574 0.696701C16.4645 0.403807 15.9896 0.403807 15.6967 0.696701C15.4038 0.989594 15.4038 1.46447 15.6967 1.75736L19.9393 6L15.6967 10.2426C15.4038 10.5355 15.4038 11.0104 15.6967 11.3033C15.9896 11.5962 16.4645 11.5962 16.7574 11.3033L21.5303 6.53033ZM-6.55671e-08 6.75L21 6.75L21 5.25L6.55671e-08 5.25L-6.55671e-08 6.75Z" fill="#690FAD" />
                  </svg>
                  </a>
                </div>
              </div>
            </div>
            <div class="content-inner-box option1">
              <button class="accordion title1" onclick="myFunction()">
                Corporate Wellness
              </button>
              <div class="panel">
                <div class="panel-inner-box">
                  <p class="accordion-desc">
                    Providing a holistic onsite and online corporate wellness solution offering fitness, health and wellness inspiration to every employee regardless of age, gender, activity level, interest or location.
                  </p>
                  <a class="btn-more" href="http://my.coredirection.com/corporate-wellness" target="_blank" rel="noopener noreferrer">Explore More <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.5303 6.53033C21.8232 6.23744 21.8232 5.76257 21.5303 5.46967L16.7574 0.696701C16.4645 0.403807 15.9896 0.403807 15.6967 0.696701C15.4038 0.989594 15.4038 1.46447 15.6967 1.75736L19.9393 6L15.6967 10.2426C15.4038 10.5355 15.4038 11.0104 15.6967 11.3033C15.9896 11.5962 16.4645 11.5962 16.7574 11.3033L21.5303 6.53033ZM-6.55671e-08 6.75L21 6.75L21 5.25L6.55671e-08 5.25L-6.55671e-08 6.75Z" fill="#690FAD" />
                  </svg>
                  </a>
                </div>
              </div>
            </div>
            
            
            <div class="content-inner-box option3">
              <button class="accordion title3" onclick="myFunction()">
                Event Consultancy
              </button>
              <div class="panel">
                <div class="panel-inner-box">
                  <p class="accordion-desc">
                    Collaborating with government entities, developers, global brands and the region's top agencies to curate and deliver the most inspiring wellness festivals, events and team building products the world over.
                  </p>
                  <a class="btn-more" href="http://my.coredirection.com/event-consultancy" target="_blank" rel="noopener noreferrer">Explore More <svg width="22" height="12" viewBox="0 0 22 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.5303 6.53033C21.8232 6.23744 21.8232 5.76257 21.5303 5.46967L16.7574 0.696701C16.4645 0.403807 15.9896 0.403807 15.6967 0.696701C15.4038 0.989594 15.4038 1.46447 15.6967 1.75736L19.9393 6L15.6967 10.2426C15.4038 10.5355 15.4038 11.0104 15.6967 11.3033C15.9896 11.5962 16.4645 11.5962 16.7574 11.3033L21.5303 6.53033ZM-6.55671e-08 6.75L21 6.75L21 5.25L6.55671e-08 5.25L-6.55671e-08 6.75Z" fill="#690FAD" />
                  </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- services-detail -->
        <div id="services-detail">
          <div class="container">
            <div class="services-detail-outer-box">
              <div class="services-detail-inner-box">
                <div class="our-services-box">
                  <div class="title-box">
                    <p class="title">
                      Corporate Wellness
                    </p>
                    <a href="http://my.coredirection.com/corporate-wellness" class="btn-more">Explore More
                      <svg width="13" height="10" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.4009 5.40089C12.6223 5.17949 12.6223 4.82052 12.4009 4.59911L8.79287 0.991082C8.57146 0.769676 8.21249 0.769676 7.99108 0.991082C7.76968 1.21249 7.76968 1.57146 7.99108 1.79287L11.1982 5L7.99108 8.20714C7.76967 8.42854 7.76967 8.78751 7.99108 9.00892C8.21249 9.23033 8.57146 9.23033 8.79286 9.00892L12.4009 5.40089ZM-4.95641e-08 5.56695L12 5.56695L12 4.43305L4.95641e-08 4.43305L-4.95641e-08 5.56695Z" fill="#F2F5EA" />
                      </svg>
                    </a>
                  </div>
                  <p class="desc">
                    Providing a holistic onsite and online corporate wellness solution offering fitness, health and wellness inspiration to every employee regardless of age, gender, activity level, interest or location.
                  </p>
                  <img class="section-img" src="/assets/images/CorporateWellnessImage.png" alt="">
                </div>
                
                <div class="our-services-box">
                  <div class="title-box">
                    <p class="title">
                      Marketplace
                    </p>
                    <a href="http://my.coredirection.com" class="btn-more">Explore More
                      <svg width="13" height="10" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.4009 5.40089C12.6223 5.17949 12.6223 4.82052 12.4009 4.59911L8.79287 0.991082C8.57146 0.769676 8.21249 0.769676 7.99108 0.991082C7.76968 1.21249 7.76968 1.57146 7.99108 1.79287L11.1982 5L7.99108 8.20714C7.76967 8.42854 7.76967 8.78751 7.99108 9.00892C8.21249 9.23033 8.57146 9.23033 8.79286 9.00892L12.4009 5.40089ZM-4.95641e-08 5.56695L12 5.56695L12 4.43305L4.95641e-08 4.43305L-4.95641e-08 5.56695Z" fill="#F2F5EA" />
                      </svg>
                    </a>
                  </div>
                  <p class="desc">
                    A coalition of fitness, health & wellness businesses and activity providers working together to collectively inspire movement, health & happiness by providing the region's largest wellness focused marketplace and booking engine.
                  </p>
                  <img class="section-img" src="/assets/images/Marketplace_Image.png" alt="">
                </div>
                
                <div class="our-services-box">
                  <div class="title-box">
                    <p class="title">
                      Event Consultancy
                    </p>
                    <a href="http://my.coredirection.com/event-consultancy" class="btn-more">Explore More
                      <svg width="13" height="10" viewBox="0 0 13 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.4009 5.40089C12.6223 5.17949 12.6223 4.82052 12.4009 4.59911L8.79287 0.991082C8.57146 0.769676 8.21249 0.769676 7.99108 0.991082C7.76968 1.21249 7.76968 1.57146 7.99108 1.79287L11.1982 5L7.99108 8.20714C7.76967 8.42854 7.76967 8.78751 7.99108 9.00892C8.21249 9.23033 8.57146 9.23033 8.79286 9.00892L12.4009 5.40089ZM-4.95641e-08 5.56695L12 5.56695L12 4.43305L4.95641e-08 4.43305L-4.95641e-08 5.56695Z" fill="#F2F5EA" />
                      </svg>
                    </a>
                  </div>
                  <p class="desc">
                    Collaborating with government entities, developers, global brands and the region's top agencies to curate and deliver the most inspiring wellness festivals, events and team building products the world over.
                  </p>
                  <img class="section-img" src="/assets/images/EventsConsultanty_image.png" alt="">
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
export default {
    name: "MainComponent"
};
</script>

<style scoped>
/*=======================================================
                reset default setting
=======================================================*/
*,
*::before,
*::after {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  /* Remove all animations and transitions for people that prefer not to see them */
}
/* Remove default margin */
body,
h1,
h2,
h3,
h4,
p,
figure,
blockquote,
dl,
dd {
  margin: 0;
}
/* Remove list styles on ul, ol elements with a list role, which suggests default styling will be removed */
ul[role="list"],
ol[role="list"] {
  list-style: none;
  margin: 0;
  padding: 0;
}
/* Set core root defaults */
html {
  scroll-behavior: smooth;
}
/* Set core body defaults */
body {
  font-family: 'Open Sans', sans-serif;
  min-height: 100vh;
  text-rendering: optimizeSpeed;
}
/* A elements that don't have a class get default styles */
a:not([class]) {
  text-decoration-skip-ink: auto;
}
/* Make images easier to work with */
img,
picture {
  max-width: 100%;
  display: block;
}
/* Inherit fonts for inputs and buttons */
input,
button,
textarea,
select {
  font: inherit;
}
a {
  text-decoration: none;
}
/*=======================================================
                        general
=======================================================*/
.container {
  width: 100%;
  max-width: 1280px;
  padding-left: 15px;
  padding-right: 15px;
  margin-left: auto;
  margin-right: auto;
}
/*=======================================================
                        header
=======================================================*/
#header {
  position: absolute;
  left: 0;
  right: 0;
  top: 48px;
}
@media (max-width: 767px) {
  #header {
    top: 0;
    height: 72px;
  }
}
#header .container {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}
#header .brand {
  z-index: 99;
}
@media (max-width: 767px) {
  #header .brand {
    position: unset;
    background: #690FAD;
    padding: 17px 15px;
    margin-left: -15px;
    margin-right: -15px;
    height: 72px;
  }
}
#header .navbar-navs {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
#header .nav-items {
  margin-left: 10px;
  margin-right: 10px;
}
#header .nav-link {
  position: relative;
  z-index: 9;
  display: block;
  color: #000;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  padding: 5px 10px;
}
@media (max-width: 767px) {
  #header .nav-link {
    display: none;
  }
}
#header .btn-more {
  position: relative;
  z-index: 99;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  min-width: 58px;
  min-height: 58px;
  max-width: 58px;
  max-height: 58px;
  color: #fff;
  border: 1px solid #F2F5EA;
  border-radius: 30px;
  background-color: #690FAD;
  cursor: pointer;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-style: normal;
  font-weight: 700;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: center;
}
@media (max-width: 767px) {
  #header .btn-more {
    display: block !important;
    min-width: 44px !important;
    min-height: 44px !important;
    max-width: 44px !important;
    max-height: 44px !important;
    font-size: 16px !important;
  }
}
/*=======================================================
                        our-services
=======================================================*/
#our-services {
  overflow: hidden;
  position: relative;
  width: 100%;
  min-height: 100vh;
  height: 100%;
  -webkit-transition: all .5s ease-in-out;
  transition: all .5s ease-in-out;
}
#our-services::before {
  position: absolute;
  content: "";
  left: 0;
  top: 0;
  background-image: url("../assets/images/icon-01.png");
  background-repeat: no-repeat;
  background-size: contain;
  background-position: top left;
  z-index: 1;
  width: 23%;
  height: 273px;
  z-index: 99;
}
#our-services::after {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  background-image: url("../assets/images/icon-02.png");
  background-repeat: repeat no-repeat;
  background-size: contain;
  background-position: top left;
  z-index: 1;
  height: 213px;
}
@media (min-width: 768px) {
  #our-services {
    background: -webkit-gradient(linear, left top, right top, color-stop(30%, #690FAD), color-stop(30%, #fff));
    background: linear-gradient(to right, #690FAD 30%, #fff 30%);
  }
  #our-services::before {
    width: 30%;
  }
}
@media (min-width: 992px) {
  #our-services {
    background: -webkit-gradient(linear, left top, right top, color-stop(23%, #690FAD), color-stop(23%, #fff));
    background: linear-gradient(to right, #690FAD 23%, #fff 23%);
  }
  #our-services::before {
    width: 23%;
  }
  #our-services::after {
    width: 23%;
  }
}
@media (min-width: 1440px) {
  #our-services {
    background: -webkit-gradient(linear, left top, right top, color-stop(30%, #690FAD), color-stop(30%, #fff));
    background: linear-gradient(to right, #690FAD 30%, #fff 30%);
  }
  #our-services::before {
    width: 30%;
  }
  #our-services::after {
    width: 30%;
  }
}
#our-services .container {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: end;
  -ms-flex-pack: end;
  justify-content: end;
  min-height: 100vh;
  height: 100%;
}
@media (max-width: 767px) {
  #our-services .container {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}
#our-services .content-box {
  margin-top: 100px;
  margin-bottom: 100px;
  padding-left: 15px;
  padding-right: 15px;
  width: 100%;
}
@media (min-width: 768px) {
  #our-services .content-box {
    max-width: 65%;
    margin-left: auto;
    margin-right: 5%;
  }
}
#our-services .accordion {
  padding: 0;
  color: #fff;
  background: transparent;
  cursor: pointer;
  width: 100%;
  border: none;
  outline: none;
  -webkit-transition: 0.4s;
  transition: 0.4s;
  font-family: 'Montserrat', sans-serif;
  font-size: 38px;
  line-height: 53px;
  font-style: normal;
  font-weight: 900;
  letter-spacing: 0.02em;
  text-align: left;
  margin-top: 15px;
  margin-bottom: 15px;
  text-shadow: -2px -2px #690fad, -2px 2px #690fad, 2px 2px #690fad, 2px -2px #690fad;
}
@media (min-width: 768px) {
  #our-services .accordion {
    font-size: 48px;
    line-height: 63px;
  }
}
@media (min-width: 992px) {
  #our-services .accordion {
    font-size: 58px;
    line-height: 73px;
  }
}
@media (min-width: 1200px) {
  #our-services .accordion {
    font-size: 68px;
    line-height: 83px;
  }
}
#our-services .content-inner-box.active .accordion,
#our-services .content-inner-box:hover .accordion {
  color: #000;
  text-shadow: none;
}
#our-services .panel {
  display: none;
  background-color: white;
  overflow: hidden;
  margin-top: 10px;
  padding: 0 3px;
}
#our-services .content-inner-box.active .panel {
  display: block;
}
#our-services .panel-inner-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}
@media (max-width: 767px) {
  #our-services .panel-inner-box {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
  }
}
#our-services .accordion-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-right: auto;
}
@media (min-width: 768px) {
  #our-services .accordion-desc {
    width: 77%;
  }
}
#our-services .btn-more {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  padding: 5px;
}
#our-services .btn-more svg {
  margin-left: 8px;
}
@media (max-width: 767px) {
  #our-services .btn-more {
    padding: 0;
    margin-top: 15px;
  }
}
#our-services #services-detail {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  width: 0%;
  min-height: 100vh;
  z-index: 9;
  background-color: #690FAD;
  -webkit-transition: all .5s ease-in-out;
  transition: all .5s ease-in-out;
}
#our-services #services-detail::after {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  background-image: url("../assets/images/icon-02.png");
  background-repeat: repeat no-repeat;
  background-size: contain;
  background-position: top left;
  z-index: 1;
  width: 100%;
  height: 213px;
}
#our-services #services-detail.active {
  width: 100%;
}
#our-services .services-detail-outer-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 100%;
  margin-top: 211px;
}
@media (max-width: 767px) {
  #our-services .services-detail-outer-box {
    margin-top: 111px;
  }
}
#our-services .services-detail-inner-box {
  width: 100%;
  max-width: 906px;
  min-height: 416px;
  margin-left: auto;
  margin-right: auto;
}
#our-services .our-services-box {
  cursor: pointer;
  opacity: .6;
  position: relative;
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  grid-column-gap: 10px;
  grid-row-gap: 15px;
  max-width: 906px;
  margin-bottom: 40px;
  padding-left: clamp(24px, 5%, 65px);
  z-index: 999;
}
#our-services .our-services-box::before {
  position: absolute;
  content: "";
  left: 0;
  width: 4px;
  height: 65px;
  background-color: #CAA8F5;
  border-radius: 30px;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}
#our-services .our-services-box .title-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  width: 100%;
  max-width: 663px;
  grid-area: 1 / 1 / 2 / 6;
}
@media (min-width: 768px) {
  #our-services .our-services-box .title-box {
    grid-area: 1 / 1 / 2 / 4;
  }
}
#our-services .our-services-box .title {
  font-family: 'Montserrat', sans-serif;
  font-size: 25px;
  font-style: normal;
  font-weight: 600;
  line-height: 30px;
  letter-spacing: 0.02em;
  text-align: left;
  color: #F2F5EA;
}
@media (max-width: 767px) {
  #our-services .our-services-box .title {
    font-size: 19px;
    line-height: 24px;
  }
}
#our-services .our-services-box .btn-more {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  color: #F2F5EA;
  text-decoration: underline;
  margin: 0;
}
@media (max-width: 767px) {
  #our-services .our-services-box .btn-more {
    font-size: 10px;
    line-height: 12px;
  }
}
#our-services .our-services-box .btn-more svg {
  margin-left: 5px;
}
#our-services .our-services-box .desc {
  grid-area: 2 / 1 / 3 / 5;
  font-family: 'Montserrat', sans-serif;
  font-size: 13px;
  font-style: normal;
  font-weight: 500;
  line-height: 16px;
  letter-spacing: 0em;
  text-align: left;
  color: #F2F5EA;
  width: 100%;
  max-width: 663px;
}
@media (min-width: 768px) {
  #our-services .our-services-box .desc {
    grid-area: 2 / 1 / 3 / 4;
  }
}
@media (max-width: 767px) {
  #our-services .our-services-box .desc {
    font-size: 10px;
    line-height: 12px;
  }
}
#our-services .our-services-box .section-img {
  visibility: hidden;
  grid-area: 2 / 5 / 3 / 6;
}
@media (min-width: 768px) {
  #our-services .our-services-box .section-img {
    grid-area: 1 / 4 / 3 / 6;
    position: absolute;
    top: 50%;
    right: 0;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
  }
}
#our-services .our-services-box:nth-child(3) {
  margin-bottom: 0;
}
#our-services .our-services-box:nth-child(3) .section-img {
  mix-blend-mode: luminosity;
}
#our-services .our-services-box.active {
  opacity: 1;
}
#our-services .our-services-box.active::before {
  height: 128px;
  background-color: #F2F5EA;
}
#our-services .our-services-box.active .section-img {
  visibility: visible;
}
</style>
