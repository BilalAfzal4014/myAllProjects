<template>
  <section id="core-premium-partners">
    <div class="custom-container">
      <div class="core-premium-title-box">
        <p class="core-premium-title">
          A network of Health & Happiness focused location partners
        </p>
        <p class="core-premium-desc">
          Exclusive Deals & Offers on gym, health club, boutique studio, wellness sanctuary, recovery center, spa,
          festival and event access as well as offers with F&B, Retail, Leisure and lifestyle partners
        </p>
      </div>
      <div class="partner-list section-body">
        <SubscriptionPartnersSlider />
      </div>
    </div>
  </section>
</template>

<script>
import SubscriptionPartnersSlider from "./SubscriptionPartnersSlider";

export default {
  name: "SubscriptionPartners",
  components: {
    SubscriptionPartnersSlider
  }
};
</script>

<style lang="scss" scoped>
#core-premium-partners {
  position: relative;
  padding-top: 64px;
  padding-bottom: 64px;
  margin-bottom: 32px;
  height: 420px;
  @media (max-width: 767px) {
    padding-top: 32px;
    padding-bottom: 32px;
  }
  //&::before{
  //	position: absolute;
  //	content: "";
  //	left: -343px;
  //	top: -461px;
  //	width: 686px;
  //	height: 686px;
  //	background: rgba(105, 15, 173, 0.30);
  //	filter: blur(500px);
  //	z-index: -1;
  //}
  //&::after{
  //	position: absolute;
  //	content: "";
  //	bottom: -12px;
  //	right: -151px;
  //	width: 381px;
  //	height: 381px;
  //	background: rgba(105, 15, 173, 0.30);
  //	filter: blur(500px);
  //	z-index: -1;
  //}
  .core-premium-title-box {
    width: 100%;
    max-width: 852px;
    padding-left: 16px;
    padding-right: 16px;
    margin-left: auto;
    margin-right: auto;

    .core-premium-title {
      text-align: center;
      font-family: "Montserrat", sans-serif;
      font-size: 32px;
      font-style: normal;
      font-weight: 600;
      line-height: normal;
      background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 24px;
      @media (max-width: 767px) {
        font-size: 24px;
      }
    }

    .core-premium-desc {
      color: #3A4963;
      text-align: center;
      font-family: "Montserrat", sans-serif;
      font-size: 18px;
      font-style: normal;
      font-weight: 400;
      line-height: 29px;
      @media (max-width: 767px) {
        font-size: 14px;
      }
    }
  }

  .partner-list {
    margin-top: 32px;
  }
}
</style>