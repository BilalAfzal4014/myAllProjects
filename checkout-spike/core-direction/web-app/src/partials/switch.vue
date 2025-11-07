<template>
  <label class="switch ml-2">
    <input type="checkbox">
    <span class="slider round" />
  </label>
</template>

<script>
export default {
    name: "SwitchComponent"
};
</script>

<style scoped>
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 20px;
  /* Rounded sliders */
}
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}
.switch .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #F1F1F1;
  -webkit-transition: .4s;
  transition: .4s;
}
.switch .slider::before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  top: 0px;
  left: 0px;
  bottom: 0px;
  background-color: #FFFFFF;
  -webkit-transition: .3s;
  transition: .3s;
  -moz-transition: .3s;
  -ms-transition: .3s;
  -o-transition: .3s;
  -webkit-box-shadow: 0px 0px 4px 0px #00000040;
  box-shadow: 0px 0px 4px 0px #00000040;
}
.switch input:checked + .slider {
  background-color: #690fad;
}
.switch input:focus + .slider {
  -webkit-box-shadow: 0 0 1px #690fad;
  box-shadow: 0 0 1px #690fad;
}
.switch input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  transform: translateX(20px);
  -moz-transform: translateX(20px);
  -o-transform: translateX(20px);
}
.switch .slider.round {
  border-radius: 34px;
}
.switch .slider.round::before {
  border-radius: 50%;
}
</style>