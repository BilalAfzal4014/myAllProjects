<template>
  <div>
    {{ image }}
  </div>
</template>

<script>
import {reactive , onMounted} from 'vue';

export default {
  setup() {
    const image = reactive({});

    onMounted(() => {
      window.electron.receive('show-image', (data) => {
        Object.assign(image, data);
      });
    });

    return {
      image
    }
  }
}
</script>
