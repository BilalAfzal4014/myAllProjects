<template>
  <div>
    <div class="wrapper">
      <div id="booking-confirmation-modal" class="custom-modal m-auto">
        <div class="modal-center">
          <div class="modal-outer-box">
            <div class="modal-inner-box">
              <div class="modal-header">
                <div class="btn-modal-close ml-auto" @click="closeBookingModal">
                  <close-icon />
                </div>
              </div>
              <div class="modal-body px-5">
                <div class="form-container mx-auto">
                  <h3 class="modal-title text-center">
                    Select Booking Type
                  </h3>
                  <p class="booking-confirmation-modal-desc text-center">
                    Book the selected activity for yourself or book multiple slots for your friends and family to join
                    the session with/without you
                  </p>
                  <div v-for="(label, index) in radioLabels" :key="index"
                       @click="setBookingType(index)"
                  >
                    <radio-button :id="'radio' + (index + 1)" :label="label" name="radio" />
                  </div>
                  <button-component type="confirm" @click="confirmBookingType">
                    Confirm
                  </button-component>
                  <button-component type="cancel" @click="closeBookingModal">
                    Cancel
                  </button-component>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CloseIcon from "@/svgs/close-icon";
import RadioButton from "@/components/RadioButton";
import ButtonComponent from "@/components/ButtonComponent";

export default {
    name: "BookingModal",
    components: {ButtonComponent, RadioButton, CloseIcon},
    props: {
        bookClass: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            bookingType: "",
            radioLabels: [
                "This booking is for only me",
                "This booking is for me and/or others",
            ],
        };
    },
    methods: {
        setBookingType(index) {
            this.bookingType = index === 0 ? "single" : "multiple";
        },
        closeBookingModal() {
            this.$emit("clicked", "close");
        },
        confirmBookingType() {
            const body = document.querySelector("body");
            body.classList.remove("overflow-y-hidden");

            if (!this.bookingType) {
                toastr.options.timeOut = 100;
                toastr.error("Please select booking type");
                return false;
            }

            const bookingTypePath = this.bookingType === "single" ? "book-activity" : "book-multiple-activity";
            const {classId, schedule_detail_id, facilityLatitude, facilityLongitude, facilityId} = this.bookClass;

            this.$router.push(
                `/${bookingTypePath}/${classId}/${schedule_detail_id}?lat=${facilityLatitude}&lng=${facilityLongitude}&facility=${facilityId}`
            );
        }


    },
};
</script>

<style scoped>
#booking-confirmation-modal .modal-outer-box,
#multiple-booking-modal .modal-outer-box,
#booking-confirmed-modal .modal-outer-box {
  max-width: calc(650px + 2rem);
  background: #FFFFFF;
  padding-bottom: 40px;
}

#booking-confirmation-modal .form-container,
#multiple-booking-modal .form-container,
#booking-confirmed-modal .form-container {
  width: 100%;
  max-width: 450px;
}

#booking-confirmation-modal .form-container .modal-title,
#multiple-booking-modal .form-container .modal-title,
#booking-confirmed-modal .form-container .modal-title {
  font-size: 36px;
  line-height: 42.19px;
  margin-top: 8px;
  margin-bottom: 20px;
}

@media screen and (max-width: 991px) {
  #booking-confirmation-modal .form-container .modal-title,
  #multiple-booking-modal .form-container .modal-title,
  #booking-confirmed-modal .form-container .modal-title {
    font-size: 24px;
  }
}

#booking-confirmation-modal .modal-subtitle,
#multiple-booking-modal .modal-subtitle,
#booking-confirmed-modal .modal-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 21.94px;
  margin-top: 39px;
  margin-bottom: 13px;
}

@media screen and (max-width: 991px) {
  #booking-confirmation-modal .modal-subtitle,
  #multiple-booking-modal .modal-subtitle,
  #booking-confirmed-modal .modal-subtitle {
    font-size: 14px;
  }
}

#booking-confirmation-modal .booking-confirmation-modal-desc,
#multiple-booking-modal .booking-confirmation-modal-desc,
#booking-confirmed-modal .booking-confirmation-modal-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 14.63px;
  margin-bottom: 41px;
}

@media screen and (max-width: 991px) {
  #booking-confirmation-modal .booking-confirmation-modal-desc,
  #multiple-booking-modal .booking-confirmation-modal-desc,
  #booking-confirmed-modal .booking-confirmation-modal-desc {
    font-size: 10px;
  }
}


#booking-confirmation-modal .btn-booking-confirmation,
#multiple-booking-modal .btn-booking-confirmation,
#booking-confirmed-modal .btn-booking-confirmation {
  font-family: 'Montserrat', sans-serif;
  width: 100%;
  max-width: 303px;
  font-size: 14px;
  font-weight: 700;
  line-height: 17.07px;
  height: 50px;
  color: #FFFFFF;
  background-color: #690FAD;
  margin-top: 67px;
  margin-bottom: 25px;
}

#booking-confirmation-modal .btn-cancel,
#multiple-booking-modal .btn-cancel,
#booking-confirmed-modal .btn-cancel {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 16.41px;
  color: #690FAD;
  margin-bottom: 48px;
}

@media screen and (max-width: 991px) {
  #booking-confirmation-modal .btn-cancel,
  #multiple-booking-modal .btn-cancel,
  #booking-confirmed-modal .btn-cancel {
    margin-bottom: 30px;
  }
}

#booking-confirmation-modal .booking-confirmed-success-icon,
#multiple-booking-modal .booking-confirmed-success-icon,
#booking-confirmed-modal .booking-confirmed-success-icon {
  margin: 46px 0;
}

@media screen and (max-width: 991px) {
  #booking-confirmation-modal .booking-confirmed-success-icon svg,
  #booking-confirmation-modal .booking-confirmed-success-icon img,
  #multiple-booking-modal .booking-confirmed-success-icon svg,
  #multiple-booking-modal .booking-confirmed-success-icon img,
  #booking-confirmed-modal .booking-confirmed-success-icon svg,
  #booking-confirmed-modal .booking-confirmed-success-icon img {
    max-width: 80px;
  }
}
</style>
