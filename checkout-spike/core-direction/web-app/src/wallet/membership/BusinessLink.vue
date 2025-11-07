<template>
  <router-link
    :to="{
      name: 'Booking',
      params: { id },
      query:{
        lat: latitude == 'NAN' ? '' : latitude ,
        lng: longitude == 'NAN' ? '' : longitude }
    }"
  >
    <h3 class="business-name text-left">
      {{ title }}
    </h3>
  </router-link>
</template>

<script>
export default {
  name: "BusinessLink",
  props: {
    id: {
      type: [String, Number]
    },
    latitude: {
      type: String
    },
    longitude: {
      type: String
    },
    title: {
      type: String
    }


  }
};
</script>
