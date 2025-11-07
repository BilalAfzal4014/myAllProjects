<template>
  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none" :class="isActive ? 'active' : ''">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.6818 2.13282L8.76818 8.21985C8.34393 8.59338 7.65607 8.59338 7.23182 8.21985L0.318191 2.13282C-0.106064 1.75929 -0.106064 1.15368 0.318191 0.780148C0.742446 0.406617 1.4303 0.406617 1.85455 0.780148L8 6.19084L14.1454 0.780148C14.5697 0.406617 15.2576 0.406617 15.6818 0.780148C16.1061 1.15368 16.1061 1.75929 15.6818 2.13282Z" :fill="isActive ? '#690FAD' : '#06070E'" />
  </svg>
</template>

<script>
export default {
  name: "AccordionIcon02",
  props: {
    isActive: {
      type: Boolean,
      default: false
    }
  }
};

</script>

<style lang="scss" scoped>
  .active{
    fill: #690FAD;
    transform: rotate(180deg);
    -webkit-transform: rotate(180deg);
    -moz-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    -o-transform: rotate(180deg);
    
  }
</style>
