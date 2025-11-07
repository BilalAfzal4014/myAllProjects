<template>
  <ul class="feature-list">
    <li v-for="(benefit,index) in benefitsList" :key="index" class="feature-item">
      <span class="feature-icon">
        <CircledRightArrow />
      </span>
      <span class="feature-content">
        {{ benefit }}
      </span>
    </li>
  </ul>
</template>

<script>
import CircledRightArrow from "@/svgs/CircledRightArrow.vue";
export default {
  name: "SubscriptionBenefitsList",
  components: {
    CircledRightArrow,
  },
  data(){
    return {
      benefitsList: [
        "Join Monthly Challenges, Get Active & Win Prizes",
        "Track Steps, Heart Rate or Log Activity to earn points",
        "150 Monthly Core Points = Prize Draw Entry",
        "Monthly Prizes worth +AED3,000",
        "Access to Discount Partner Deals & Offers",
        "Free and Discounted Access to Events"
      ],
    };
  }
};
</script>

<style lang="scss" scoped>
	.feature-list{
		padding: 16px 32px;
		margin-bottom: 16px;
		display: flex;
		flex-direction: column;
		row-gap: 18px;
		@media (max-width:767px) {
			row-gap: 12px;
			padding: 0;
			margin-bottom: 32px;
		}
    .feature-item{
      display: flex;
      align-items: center;
      column-gap: 20px;
      color: #06070E;
      font-family: "Montserrat", sans-serif;
      font-size: 16px;
      font-style: normal;
      font-weight: 400;
      line-height: 135%;
      @media (max-width:767px) {
        font-size: 12px;
        column-gap: 12px;
      }
      .feature-icon{
        width: 40px;
        height: 40px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        @media (max-width:767px) {
          width: 32px;
          height: 32px;
        }
		  }
    }
	}
</style>