<template>
  <div class="inputGroup">
    <input :id="id" :name="name" type="radio">
    <label :for="id">{{ label }}</label>
  </div>
</template>

<script>
export default {
    name: "RadioButton",
    props: {
        id: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
    }
};
</script>

<style scoped>
#booking-confirmation-modal .inputGroup,
#multiple-booking-modal .inputGroup,
#booking-confirmed-modal .inputGroup {
  display: block;
  margin: 11px 0;
  position: relative;
  border-radius: 15px;
  -webkit-box-shadow: 0px 2px 4px 0px #0000001A;
  box-shadow: 0px 2px 4px 0px #0000001A;
}

#booking-confirmation-modal .inputGroup label,
#multiple-booking-modal .inputGroup label,
#booking-confirmed-modal .inputGroup label {
  padding: 52px 80px 45px 38px;
  width: 100%;
  display: block;
  text-align: left;
  color: #000000;
  cursor: pointer;
  position: relative;
  z-index: 2;
  -webkit-transition: color 200ms ease-in;
  transition: color 200ms ease-in;
  overflow: hidden;
  border-radius: 15px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 19.5px;
  border: 1px solid transparent;
  margin: 0;
}

@media screen and (max-width: 991px) {
  #booking-confirmation-modal .inputGroup label,
  #multiple-booking-modal .inputGroup label,
  #booking-confirmed-modal .inputGroup label {
    font-size: 12px;
    line-height: 14.63px;
  }
}

#booking-confirmation-modal .inputGroup label:after,
#multiple-booking-modal .inputGroup label:after,
#booking-confirmed-modal .inputGroup label:after {
  width: 24px;
  height: 24px;
  content: '';
  border: 6px solid #CAA8F5;
  background-color: #FFFFFF;
  border-radius: 50%;
  z-index: 2;
  position: absolute;
  right: 38px;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  cursor: pointer;
  -webkit-transition: all 200ms ease-in;
  transition: all 200ms ease-in;
}

#booking-confirmation-modal .inputGroup input:checked ~ label,
#multiple-booking-modal .inputGroup input:checked ~ label,
#booking-confirmed-modal .inputGroup input:checked ~ label {
  border: 1px solid #690FAD;
}

#booking-confirmation-modal .inputGroup input:checked ~ label:before,
#multiple-booking-modal .inputGroup input:checked ~ label:before,
#booking-confirmed-modal .inputGroup input:checked ~ label:before {
  -webkit-transform: translate(-50%, -50%) scale3d(56, 56, 1);
  transform: translate(-50%, -50%) scale3d(56, 56, 1);
  opacity: 1;
}

#booking-confirmation-modal .inputGroup input:checked ~ label:after,
#multiple-booking-modal .inputGroup input:checked ~ label:after,
#booking-confirmed-modal .inputGroup input:checked ~ label:after {
  border: 6px solid #690FAD;
  background-color: #CAA8F5;
}

#booking-confirmation-modal .inputGroup input,
#multiple-booking-modal .inputGroup input,
#booking-confirmed-modal .inputGroup input {
  width: 24px;
  height: 24px;
  -webkit-box-ordinal-group: 2;
  -ms-flex-order: 1;
  order: 1;
  z-index: 2;
  position: absolute;
  right: 38px;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
  cursor: pointer;
  visibility: hidden;
}
</style>