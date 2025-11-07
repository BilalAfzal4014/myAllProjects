<template>
  <div class="checkbox-field-box mb-7">
    <div class="form-group">
      <input id="promotionalEmail" type="checkbox" :checked="promotionalEmailEnable" @change="$emit('promotionalEmail', $event.target.checked)">
      <label for="promotionalEmail">I agree to the <a href="./terms-conditions" style="text-decoration: underline;">Terms &amp; Conditions</a> and acknowledge I have read the <a href="./privacy-policy" style="text-decoration: underline;">Privacy Policy</a>.</label>
    </div>
  </div>
</template>

<script>
export default {
    name: "AgreeTermsConditionComponent",
    props:{
        promotionalEmailEnable:{
            type:Boolean,
            required:true
        }

    }
  
};
</script>

<style scoped>

</style>