<template>
  <div id="session-cancel-modal" class="custom-modal">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header" />
          <div class="modal-body">
            <div class="form-container">
              <div class="session-cancel-icon">
                <ApprovedTickIconVue />
              </div>
              <p class="session-cancel-title">
                Membership Canceled
              </p>
              <p class="session-cancel-desc">
                Your Core Premium membership has been cancelled as of {{ formatDate(cancellationDate) }}.
                But don't worry, you can continue enjoying premium benefits until your current subscription expires on
                {{ formatDate(expirationDate) }}.
              </p>
            </div>
            <div class="session-cancel-btn-box">
              <button class="session-cancel-btn btn-pill btn-modal-close"
                      @click="closeCancelSubscriptionSuccessfullyModal"
              >
                Go Back
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApprovedTickIconVue from "@/svgs/ApprovedTickIcon";

export default {
  name: "SuccessfullyCancelSubscriptionModal",
  components: {ApprovedTickIconVue},
  methods: {
    closeCancelSubscriptionSuccessfullyModal() {
      this.$emit("closeCancelSubscriptionSuccessfullyModal");
    },
    formatDate(date) {
      const day = date.getDate().toString().padStart(2, "0");
      const month = (date.getMonth() + 1).toString().padStart(2, "0");
      const year = date.getFullYear();
      return `${day}-${month}-${year}`;
    }
  },
  data() {
    return {
      cancellationDate: new Date(),
      expirationDate: null,

    };
  },
  created() {
    this.expirationDate = new Date(this.cancellationDate);
    this.expirationDate.setDate(this.cancellationDate.getDate() + 30);
  }

};
</script>

<style scoped>
#session-cancel-modal .modal-outer-box {
  max-width: 450px;
  background: #FFFFFF;
  -webkit-box-shadow: 0px 3px 17px rgba(0, 0, 0, 0.37);
  box-shadow: 0px 3px 17px rgba(0, 0, 0, 0.37);
  border-radius: 21px;
}

@media screen and (max-width: 767px) {
  #session-cancel-modal .modal-outer-box {
    max-width: 394px;
  }
}

#session-cancel-modal .modal-outer-box .modal-header {
  min-height: 68px;
  padding: 22px 24px 16px;
}

#session-cancel-modal .modal-outer-box .modal-header .btn-modal-close {
  margin-left: auto;
}

#session-cancel-modal .modal-outer-box .modal-body {
  padding-left: 20px;
  padding-right: 20px;
}

#session-cancel-modal .modal-outer-box .form-container {
  width: 100%;
  max-width: 345px;
  margin-left: auto;
  margin-right: auto;
}

#session-cancel-modal .modal-outer-box .session-cancel-icon {
  margin-left: auto;
  margin-right: auto;
}

#session-cancel-modal .modal-outer-box .session-cancel-title {
  color: #000000;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  line-height: 21.94px;
  margin-top: 21px;
  margin-bottom: 18px;
  font-weight: 600;
  text-transform: uppercase;
  text-align: center;
}

#session-cancel-modal .modal-outer-box .session-cancel-desc {
  color: #000000;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  margin-bottom: 18px;
  text-align: center;
}

#session-cancel-modal .modal-outer-box .session-cancel-btn-box {
  text-align: center;
  margin-top: 55px;
}

#session-cancel-modal .modal-outer-box .session-cancel-btn-box .session-cancel-btn {
  width: 205px;
  height: 45px;
  -webkit-box-shadow: 0px 4px 4px 0px #00000040;
  box-shadow: 0px 4px 4px 0px #00000040;
  color: #FFFFFF;
  background-color: #690FAD;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 16.41px;
  margin-bottom: 50px;
}
</style>