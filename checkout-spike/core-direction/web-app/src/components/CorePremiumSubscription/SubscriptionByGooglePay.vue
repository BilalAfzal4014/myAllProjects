<template>
  <div class="subs-by-google-pay">
    <p class="cart-payment-title">
      Enter your G-pay Info
    </p>
    <div class="credit-box">
      <div class="field-box">
        <label for="" class="input-label">Enter your Email</label>
        <input type="email" class="input-field">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubscriptionByGooglePay"
};
</script>

<style lang="scss" scoped>
	.subs-by-google-pay {
		.cart-payment-title {
			font-family: 'Montserrat', sans-serif;
			font-size: 18px;
			font-style: normal;
			font-weight: 700;
			line-height: normal;
			text-transform: capitalize;
			letter-spacing: 0em;
			text-align: left;
			margin-bottom: 16px;
			@media screen and (min-width: 768px) {
				padding: 8px;
			}
		}
		.credit-box{
			@media (min-width:768px) {
					padding: 0 8px;
					margin-top: 24px;
			}
			@media (max-width:767px) {
				margin-top: 20px;
			}
			.field-box {
				position: relative;
				cursor: pointer;
				margin-bottom: 16px;
				@media (max-width:767px) {
					margin-bottom: 12px;
				}
				.input-label {
					position: absolute;
					top: 12px;
					left: 18px;
					color: #06070E;
					font-family: "Montserrat", sans-serif;
					font-size: 14px;
					font-style: normal;
					font-weight: 400;
					line-height: normal;
				}
				.input-field {
					display: block;
					width: 100%;
					padding: 12px 18px 13px;
					color: #06070E;
					font-family: "Montserrat", sans-serif;
					font-size: 14px;
					font-style: normal;
					font-weight: 400;
					line-height: normal;
					border:1px solid rgba(#06070E, .4499);
					border-radius: 8px;
					-webkit-border-radius: 8px;
					-moz-border-radius: 8px;
					-ms-border-radius: 8px;
					-o-border-radius: 8px;
				}
				.icon-list{
					position: absolute;
					top: 8px;
					right: 16px;
					display: flex;
					align-items: center;
					justify-content: flex-end;
					column-gap: 4px;
					@media (max-width:767px) {
						display: none;
					}
					.icon-img{
						width: 36px;
						height: 24px;
					}
				}
				&.focus{
					.input-label{
						top: -8px;
						padding: 0 6px;
						background-color: #fff;
						color: #690FAD;
					}
					.input-field{
						border-color: #690FAD;
					}
				}
				&.filled{
					.input-label{
						display: none;
					}
					.input-field{
						border-color: rgba(#690fad, .45);
					}
				}
				&:hover{
					.input-label{
						color: #690fad;
					}
					.input-field{
						border-color: #000;
					}
				}
			}
			.field-inner-box{
				display: grid;
				grid-template-columns: 1fr 1fr;
				column-gap: 24px;
				@media (max-width:767px) {
					column-gap: 8px;
				}
				.field-box{
					margin-bottom: 0;
				}
			}
		}
	}
</style>
