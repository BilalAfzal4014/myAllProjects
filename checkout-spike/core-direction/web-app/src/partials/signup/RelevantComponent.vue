<template>
  <div class="checkbox-field-box mb-7">
    <div class="form-group">
      <input id="relevantPromotionalEmail" type="checkbox" :checked="corporateEmailEnable" name="relevantPromotionalEmail" @change="$emit('corporateEmail', $event.target.checked)">
      <label for="relevantPromotionalEmail">I want to receive relevant promotional email from CoreDirection partners.</label>
    </div>
  </div>
</template>

<script>
export default {
    name: "RelevantComponent",
    props:{
        corporateEmailEnable:{
            type:Boolean,
            corporateEmailEnable:false
        }
    }
};
</script>

<style scoped>

</style>