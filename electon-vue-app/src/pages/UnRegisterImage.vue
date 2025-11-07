<template>
  <ImageList
      v-bind:message="'Unregister Images'"
      v-bind:images="unRegisterImages"
      v-bind:remove="removeImageFromUnRegister"
  />
</template>

<script>
import {useImageHook} from '../composition/useImageHook';
import ImageList from '../components/ImageList.vue';

export default {
  components: {
    ImageList
  },
  setup() {

    const {
      unRegisterImages,
      removeImageFromUnRegister
    } = useImageHook();

    return {
      unRegisterImages,
      removeImageFromUnRegister
    };
  }

}

</script>
