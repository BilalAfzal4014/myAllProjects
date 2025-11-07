<template>
  <li :class="`faq-item ${ faqActive ? 'active' : ''}`" @click="$emit('toggleActive', !faqActive)">
    <button class="btn-faq">
      <span class="content"> {{ faqQuestion }} </span>
      <span class="icon-box">
        <AccordionIcon02 :isActive="faqActive" />
      </span>
    </button>
    <div class="faq-content">
      <p class="desc">
        {{ faqAnswer }}
      </p>
    </div>
  </li>
</template>

<script>
import AccordionIcon02 from "@/svgs/arrows/accordion-icon02.vue";
export default {
  name: "SubscriptionFAQsItem",
  components: {
    AccordionIcon02
  },
  props: {
    faqQuestion: {
      type: String,
      default: "",
    },
    faqAnswer: {
      type: String,
      default: "",
    },
    faqActive :{
      type: Boolean,
      default: false
    }
  },

};
</script>

<style lang="scss" scoped>
	.faq-item{
			padding: 16px 8px;
			.btn-faq{
					display: flex;
					align-items: center;
					justify-content: space-between;
					width: 100%;
					text-align: left;
					font-family: "Montserrat", sans-serif;
					color: #06070E;
					font-size: 14px;
					font-style: normal;
					font-weight: 500;
					line-height: 29px;
					column-gap: 8px;
					@media (min-width: 768px) {
							padding: 8px 16px;
					}
					@media (max-width: 767px) {
							padding: 8px;
					}
			}
			.faq-content{
					display: none;
					@media (min-width: 768px) {
							padding: 8px 16px;
					}
					@media (max-width: 767px) {
							padding: 8px;
					}
					width: 100%;
					.desc{
							color: #06070E;
							font-family: "Montserrat", sans-serif;
							font-size: 14px;
							font-style: normal;
							font-weight: 400;
							line-height: 24px;
					}
			}
			&.active{
					.btn-faq{
						color: #690FAD;
					}
					.faq-content{
							display: block;
					}
			}
	}
</style>