<template>
  <div>
    <p class="package-detail-title">
      Enter Credential for bookings
    </p>
    <detailBox v-for="(user, index) in users" :key="index"
               :serial-number="index + 1"
               :user-details="user"
               @removeUser="updateTotalUsers"
               @updateUserType="updateUserType"
               @validateUserDetails="validateUserDetails"
    />
  </div>
</template>

<script>
import detailBox from "@/partials/booking/single-booking/steps/partials/detail-box";

export default {
    name: "DetailBoxKeeper",
    components: {
        detailBox
    },
    props: {
        users: {
            type: Array,
            required: true,
        },
        updateTotalUsers: {
            type: Function,
            required: true
        },
        validateUserDetails: {
            type: Function,
            required: true
        },
        updateUserType: {
            type: Function,
            required: true
        }
    },
};
</script>

<style>
#cart .package-detail-box .booking-detail-box {
  overflow: hidden;
  position: relative;
  background-color: #FFFFFF;
  padding: 60px 46px 51px;
  border-radius: 21px;
  -webkit-border-radius: 21px;
  -moz-border-radius: 21px;
  -ms-border-radius: 21px;
  -o-border-radius: 21px;
  margin-bottom: 15px;
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
}

@media (max-width: 767px) {
  #cart .package-detail-box .booking-detail-box {
    border-radius: 10px;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    -ms-border-radius: 10px;
    -o-border-radius: 10px;
    padding: 72px 29px 42px;
  }
}

#cart .package-detail-box .booking-detail-tab-counter {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  padding: 17px 22px;
  position: absolute;
  top: 0;
  left: 0;
  width: 91px;
  height: 71px;
  color: #FFFFFF;
  background-color: #690FAD;
  -webkit-clip-path: polygon(100% 0, 0 0, 0 100%);
  clip-path: polygon(100% 0, 0 0, 0 100%);
}

#cart .package-detail-box .ticket-type-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

#cart .package-detail-box .tab-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-right: 35px;
}

@media (max-width: 767px) {
  #cart .package-detail-box .tab-title {
    margin-bottom: 21px;
  }
}

#cart .package-detail-box .form-group {
  font-family: 'Montserrat', sans-serif;
  font-style: normal;
  font-size: 14px;
  line-height: 17px;
  font-weight: 400;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 767px) {
  #cart .package-detail-box .form-group {
    font-size: 12px;
    line-height: 15px;
  }
}

@media (max-width: 767px) {
  #cart .package-detail-box .form-group label::before {
    margin-right: 4px;
  }
}

#cart .package-detail-box .ticket-type-inner-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  width: 100%;
  max-width: 330px;
}

#cart .package-detail-box .form-group input:checked + label:after {
  top: 0px;
}

@media (max-width: 767px) {
  #cart .package-detail-box .form-group input:checked + label:after {
    top: -1px;
  }
}

#cart .package-detail-box .ticket-type-outer-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  /*-webkit-box-align: end;*/
  /*-ms-flex-align: end;*/
  /*align-items: flex-end;*/
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

#cart .package-detail-box .form-field-box {
  margin-top: 31px;
}

#cart .package-detail-box .name-fields {
  width: 100%;
  display: grid;
  grid-template-columns: 1fr 1fr;
  -webkit-column-gap: 30px;
  column-gap: 30px;
  margin-right: auto;
}

@media (min-width: 768px) {
  #cart .package-detail-box .name-fields {
    max-width: 330px;
  }
}

#cart .package-detail-box .form-field-label {
  display: block;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #222222;
}

#cart .package-detail-box .form-field-label .compulsory {
  color: red;
}

#cart .package-detail-box .form-field {
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  padding: 10px 5px;
  border-bottom: 1px solid #000000;
}

#cart .package-detail-box .form-field::-webkit-input-placeholder {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: italic;
  font-weight: 250;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
}

#cart .package-detail-box .form-field:-ms-input-placeholder {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: italic;
  font-weight: 250;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
}

#cart .package-detail-box .form-field::-ms-input-placeholder {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: italic;
  font-weight: 250;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
}

#cart .package-detail-box .form-field::placeholder {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: italic;
  font-weight: 250;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
}

#cart .package-detail-box .email-field-box {
  width: 100%;
}

@media (min-width: 768px) {
  #cart .package-detail-box .email-field-box {
    max-width: 300px;
  }
}

@media (min-width: 768px) and (max-width: 850px) {
  #cart .package-detail-box .email-field-box {
    width: unset;
  }
}

#cart .package-detail-box .btn-delete {
  margin-left: 23px;
}

@media (max-width: 767px) {
  #cart .package-detail-box .btn-delete {
    position: absolute;
    right: 24px;
    top: 26px;
  }
}
</style>