<script>
export default {
  props: {
    message: {
      type: String,
      required: true
    },
    images: {
      type: Array,
      required: true
    },
    remove: {
      type: Function,
      required: true,
    },
    display: {
      type: Function,
      required: false,
    }
  },

}

</script>

<template>
  <h3>{{message}}</h3>
  <ul>
    <li v-for="image in images" v-bind:key="image.id">
      {{image.name}}
      <button v-on:click="remove(image)">Remove</button>
      <button v-if="!!display" v-on:click="display(image)">Display</button>
    </li>
  </ul>
</template>
