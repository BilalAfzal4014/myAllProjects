<template>
  <section id="banner" class="flex items-center">
    <img 
      class="banner-img" 
      src="https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=1920&quality=100" 
      srcset="https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=480&quality=100 w480, 
        https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=768&quality=100 w768, 
        https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=992&quality=100 w992, 
        https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=1024&quality=100 w1024, 
        https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=1440&quality=100 w1440, 
        https://coredirection-pull-zone.b-cdn.net/prod-media/images/web/jan-challenge-banner-v2.png?optimiser=image&format=webp&width=1920&quality=100 w1920" 
      sizes="100vw"
      alt="Hero Banner"
    >
    <div class="custom-container mx-auto">
      <div class="hero-content-box">
        <h3 class="hero-title">
          Win an iPhone 15
        </h3>
        <p class="hero-desc">
          Join Core Premium & score 150+ points in the January Challenge to enter the prize draw
        </p>
        <div class="hero-btn-box flex items-center flex-wrap">
          <button class="hero-btn rounded-full" @click="$router.push('/corepremium')">
            Go Premium
          </button>
          <!-- <div class="other-option flex items-center">
            <p class="option-text">
              or
            </p>
            <button class="btn-signin" @click="showSignInModal">
              Sign in
            </button>
          </div> -->
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import emitter from "tiny-emitter/instance";

export default {
  name: "BannerComponent",
  methods: {
    showSignInModal: function () {
      document.body.classList.remove("overflow-y-hidden");
      emitter.emit("sign_in_modal", "show sign in modal");
    },
  }
};
</script>

<style scoped>
#banner {
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  min-height: 60vh;
  height: 500px;
  position: relative;
}

#banner .banner-img{
  position: absolute;
  width: calc(100vw + 2px);
  object-fit: cover;
  object-position: center;
  z-index: -1;
  height: 100%;
  right: -2px;
}

@media (max-width: 991px) {
  #banner {
    height: 320px;
  }
}

@media (max-width: 767px) {
  #banner {
    max-height: 320px;
    height: 100%;
    min-height: auto;
  }
  #banner .banner-img{
    object-position: 36% center;
  }
}

#banner .custom-container {
  position: relative;
  min-height: 60vh;
  height: 320px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding-top: 30px;
  padding-bottom: 64px;
}

@media screen and (min-width: 768px) {
  #banner .custom-container {
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
}

@media screen and (max-width: 767px) {
  #banner .custom-container .hero-img {
    max-width: 300px !important;
    margin-bottom: 35px;
    padding-left: 1rem;
    padding-right: 1rem;
    margin-left: auto;
  }
}

@media screen and (max-width: 991px) {
  #banner .custom-container {
    padding-left: 20px;
    padding-right: 20px;
  }

  #banner .custom-container .hero-content-box {
    margin-right: auto;
    margin-left: 0;
  }
}

#banner .custom-container .hero-tagline {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  margin-bottom: 10px;
  color: #525252;
  letter-spacing: 0.25em;
  text-align: left;
}

@media screen and (max-width: 991px) {
  #banner .custom-container .hero-tagline {
    font-size: 22px;
  }
}

#banner .custom-container .hero-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 54px;
  font-style: italic;
  font-weight: 700;
  line-height: 1.5;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 13px;
  color: #690FAD;
}
#banner .custom-container .hero-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 20px;
  max-width: 500px;
  font-weight: 600;
  line-height: 1.5;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 23px;
  color: #000;
}

@media (min-width: 768px) and (max-width: 991px) {
  #banner .custom-container .hero-title {
    font-size: 38px;
    margin-bottom: 33px;
  }
}

@media screen and (max-width: 767px) {
  #banner .custom-container .hero-title {
    font-size: 24px;
    margin-bottom: 0;
  }
  #banner .custom-container .hero-desc {
    font-size: 13px;
    font-weight: 400;
    margin-bottom: 15px;
  }
}

#banner .custom-container .hero-btn {
  display: block;
  width: 240px;
  height: 50px;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 2.5;
  color: #FFFFFF;
  -webkit-box-shadow: 0px 5px 10px rgba(105, 15, 173, 0.45);
  box-shadow: 0px 5px 10px rgba(105, 15, 173, 0.45);
  background-color: #690FAD;
  cursor: pointer;
  text-transform: capitalize;
  margin: 0;
}

@media screen and (max-width: 991px) {
  #banner .custom-container .hero-btn {
    width: 180px;
    height: 44px;
    font-size: 14px;
    font-weight: 600;
    line-height: 3;
    text-align: center;
    margin-right: auto;
  }
}

#banner .custom-container .hero-img {
  max-width: 600px;
}

#banner .custom-container .hero-btn-box,
#banner .custom-container .other-option {
  -webkit-column-gap: 15px;
  column-gap: 15px;
  row-gap: 13px;
}
@media (max-width:991px){
  #banner .custom-container .hero-btn{
    margin-left: 0;
  }
  #banner .custom-container .hero-btn-box{
    flex-direction: column;
    align-items: baseline;
  }
  #banner .custom-container .other-option{
    padding-left: 8px;
  }
}

#banner .custom-container .option-text {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 400;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
}

#banner .custom-container .btn-signin {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
  text-decoration: underline;
  cursor: pointer;
}
@media (max-width: 767px) {
  #banner .custom-container{
    padding-bottom: 0;
  }
  #banner .custom-container .hero-content-box {
    max-width: 250px;
  }
}
</style>