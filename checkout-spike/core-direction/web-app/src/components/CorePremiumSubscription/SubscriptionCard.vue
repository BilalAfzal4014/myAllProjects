<template>
  <section id="subscription">
    <div class="subscription-box">
      <div class="content-box">
        <p class="title">
          Subscribe to Core Premium Now
        </p>
        <p class="desc">
          Core Direction is a wellness focused technology solution that allows users to create a daily routine with healthy living, physical activity, mental wellbeing and community in mind. Be a part of this inspiring community to thrive at life.
        </p>
      </div>
      <a class="btn-subscribe" href="#core-premium-subscription">
        Subscribe Now
      </a>
    </div>
  </section>
</template>

<script>
export default {
  name: "SubscriptionCard", 
};
</script>

<style lang="scss" scoped>
	#subscription{
		background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
		box-shadow: 0px 4px 6px 0px rgba(16, 24, 40, 0.03), 0px 12px 16px 0px rgba(16, 24, 40, 0.08);
		padding: 32px;
		margin-bottom: 115px;
		.subscription-box{
			display: flex;
			align-items: center;
			column-gap: 64px;
			row-gap: 32px;
			width: 100%;
			max-width: 928px;
			margin: 0 auto;
			@media (max-width:767px) {
				flex-wrap: wrap;
			}
			.title{
				color: #FFFFFA;
				font-family: "Montserrat", sans-serif;
				font-size: 32px;
				font-style: normal;
				font-weight: 600;
				line-height: normal;
				margin-bottom: 16px;
				@media (max-width:767px) {
					font-size: 24px;
				}
			}
			.desc{
				color: #FFFFFA;
				font-family: "Montserrat", sans-serif;
				font-size: 18px;
				font-style: normal;
				font-weight: 400;
				line-height: 29px;
				@media (max-width:767px) {
					font-size: 14px;
					line-height: 20px;
				}
			}
			.btn-subscribe{
				padding: 12px 24px;
				border-radius: 24px;
				background: #FFFFFA;
				color: #690FAD;
				text-align: center;
				font-family: "Montserrat", sans-serif;
				font-size: 14px;
				font-style: normal;
				font-weight: 600;
				line-height: normal;
				display: block;
				min-width: max-content;
			}
		}
	}

</style>