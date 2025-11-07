<template>
  <div class="term-condition-box">
    <div class="form-group">
      <input id="saveCard" type="checkbox" v-model="isCheckedTerms">
      <label for="saveCard"> Save my card for next time</label>
    </div>
    <div class="form-group">
      <input id="term-condition" type="checkbox">
      <label for="term-condition">I agree to Core Direction’s <a href="#">Terms of Services</a> and <a href="#">Privacy Policy</a></label>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubscriptionTermsAndConditions",
  data(){
	return {
		isCheckedTerms: false,
	};
  }
};
</script>

<style lang="scss" scoped>
 .term-condition-box{
	padding: 0px 8px;
		display: flex;
		flex-direction: column;
		row-gap: 16px;
		.form-group {
			line-height: 1;
			padding: 0;
			label{
				color: var(--cd-black, #06070E);
				font-family: "Montserrat", sans-serif;
				font-size: 11px;
				font-style: normal;
				font-weight: 700;
				line-height: normal;
				&::after{
					margin-left: 0;
				}
				&::before{
					width: 14px;
					height: 14px;
					margin-right: 8px;
					background: url("../../assets/images/checkbox-uncheck_black.png");
					background-repeat: no-repeat;
					top: -1px;
					left: 1px;
					padding: 0;
					margin-left: 0;
				}
				a{
						color: #690FAD;
						text-decoration-line: underline;
				}
			}
			input:checked + label:after{
				top: -1px;
				@media (max-width:767px){
					top: -2px;
				}
			}
		}
  }
</style>