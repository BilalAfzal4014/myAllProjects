<template>
  <ImageList
      v-bind:message="'Register Images'"
      v-bind:images="registerImages"
      v-bind:remove="removeImageFromRegister"
      v-bind:display="display"
  />
</template>

<script>
//import {ipcRenderer} from 'electron';
//const { remote, ipcRenderer } = require('electron')
import {toRaw} from 'vue';
import {useImageHook} from '../composition/useImageHook';
import ImageList from '../components/ImageList.vue';

export default {
  components: {
    ImageList
  },
  setup() {

    const {
      registerImages,
      removeImageFromRegister
    } = useImageHook();

    const display = (image) => {
      window.electron.send('send-image', toRaw(image));
    }

    return {
      registerImages,
      removeImageFromRegister,
      display
    };
  }

}

</script>
