<template>
  <section id="core-premium-faqs">
    <div class="custom-container">
      <div class="core-premium-title-box">
        <p class="core-premium-title">
          Frequently Asked Questions
        </p>
        <p class="core-premium-desc">
          A guide to everything you need to know for registration, participation and cancellation even though we know
          you’ll never want to go.
        </p>
      </div>
      <ul class="faq-list">
        <SubscriptionFAQsItem v-for="(faq,index) in faqsList" :key="index" :faqActive="faq.isActive"
                              :faqAnswer="faq.answer" :faqQuestion="faq.question"
                              @toggleActive="checkActiveFaq($event,faq)"
        />
      </ul>
    </div>
  </section>
</template>

<script>
import SubscriptionFAQsItem from "./SubscriptionFAQsItem.vue";

export default {
  name: "SubscriptionFAQs",
  components: {
    SubscriptionFAQsItem,
  },
  data() {
    return {
      faqsList: [
        {
          isActive: false,
          question: "What are the pricing options for the subscription plan?",
          answer: "A single recurring monthly fee of AED 36.75 gives you everything the Core Direction wellness focussed technology solution has to offer."
        },
        {
          isActive: false,
          question: "What are the key features included in the subscription plan?",
          answer: "Access to Deals & Offer linked to all listed marketplace partners include discounts of a minimum of 15% off, buy one, get one free offers and many free passes. Access to exclusive challenges and Core Premium only monthly raffles and prize draws. Monthly Health & Happiness daily routine trackers and supporting push notifications to help create the best version of you."
        },
        {
          isActive: false,
          question: "What do non Core Premium platform users get on Core Direction?",
          answer: "Create activities, find friends, connect wearables, track steps & active heart rate. Build sleep, hydration and breathwork streaks. Access a marketplace of partner locations with full price activity booking. Compete on an annual Challenge Leaderboard."
        },
        {
          isActive: false,
          question: "How can I cancel or modify my subscription plan?",
          answer: `To cancel or make changes to your subscription, follow these simple steps:
            1. Navigate to the "Wallet" screen on our platform.
            2. Click on the "Packages & Membership" option.
            3. Look for the "Core Premium" tile and select it.


            Here, you will find the options to cancel or modify your subscription according to your preferences. You can cancel your subscription at any time.`
        },
        {
          isActive: false,
          question: "Are there any additional benefits or perks associated with the subscription plan?",
          answer: "Core Premium subscribers are provided with early bird ticket access to special events and exclusive VIP access to retreats, festivals & events."
        },
        {
          isActive: false,
          question: "How did I get Core Premium for FREE?",
          answer: "Core Direction is a global leading corporate wellness technology platform. Ifd you have an active Core Premium account and have not upgraded and paid yourself, this is likely due to the fact that your company is a Core Direction corporate wellness partner and as an employee/community member of this organisation you can now avail Core Premium benefits "
        },
        {
          isActive: false,
          question: "What is the duration of the subscription plan and is it automatically renewed?",
          answer: "Core Premium subscription is a monthly auto renewing fee that will continue to run until you cancel. If you have availed Core Premium features through your company, your subscription will expire if you leave your company or your company cancels their Core Direction corporate wellness agreement."
        }
      ]
    };
  },
  methods: {
    checkActiveFaq(event, faq) {
      return faq.isActive = event;
    }
  }

};
</script>

<style lang="scss" scoped>
#core-premium-faqs {
  position: relative;
  padding-top: 64px;
  padding-bottom: 64px;
  margin-bottom: 32px;
  @media (max-width: 767px) {
    padding-top: 32px;
    padding-bottom: 32px;
  }
  //&::before{
  //	position: absolute;
  //	content: "";
  //	left: -343px;
  //	top: -104px;
  //	width: 686px;
  //	height: 686px;
  //	background: rgba(105, 15, 173, 0.30);
  //	filter: blur(500px);
  //	z-index: -1;
  //}
  //&::after{
  //	position: absolute;
  //	content: "";
  //	bottom: -179px;
  //	right: -456px;
  //	width: 686px;
  //	height: 686px;
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

  .faq-list {
    border-radius: 12px;
    background: rgba(255, 255, 250, 0.10);
    box-shadow: 0px 4px 6px 0px rgba(16, 24, 40, 0.03), 0px 12px 16px 0px rgba(16, 24, 40, 0.08);
    display: flex;
    flex-direction: column;
    row-gap: 4px;
    @media (min-width: 768px) {
      margin-top: 64px;
      padding: 16px;
    }
    @media (max-width: 767px) {
      margin-top: 32px;
      padding: 12px 8px;
    }
  }
}
</style>
