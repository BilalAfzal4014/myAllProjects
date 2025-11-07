<template>
  <div class="checkbox-field-box mb-7">
    <div class="form-group">
      <input id="covid-19" type="checkbox" :checked="is_vaccinated" @change="$emit('getVacciated',$event.target.checked)">
      <label for="covid-19">By ticking this box, I declare that I have received both doses of an approved COVID-19 vaccine and am willing to provide proof of my vaccination on check-in at my booked class, activity or event. I accept that without my proof of vaccination, I may be denied entry to any booking with no reimbursement of booking fees paid.</label>
    </div>
  </div>
</template>

<script>
export default {
    name: "Agreement",
    props:{
        // eslint-disable-next-line vue/prop-name-casing
        is_vaccinated:Boolean,
    }
};
</script>

<style scoped>

</style>