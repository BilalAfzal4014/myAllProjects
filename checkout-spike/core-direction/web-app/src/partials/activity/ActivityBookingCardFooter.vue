<template>
  <div class="booking-card-footer">
    <button
      :class="buttonClass"
      :disabled="isButtonDisabled"
      name="button"
      type="button"
      @click="handleClick"
    >
      {{ buttonText }}
    </button>
  </div>
</template>

<script>

export default {
  name: "ActivityBookingCardFooter",
  props: {
    data: {
      type: Object,
      required: true
    },
  },
  computed: {
    isActivityBooked() {
      return this.data.is_already_booked || this.data.isBooked;
    },
    buttonClass() {
      if (this.data.booked_slots === this.data.slots) {
        return "btn-diabled-activity-full uppercase rounded-full";
      } else if (this.isActivityBooked) {
        return "booked-activity uppercase rounded-full";
      } else {
        return "btn-book-now uppercase rounded-full";
      }
    },
    buttonText() {
      if (this.data.booked_slots === this.data.slots) {
        return "Activity Full";
      } else if (this.isActivityBooked) {
        return "Booked";
      } else {
        return "Book Now";
      }
    },
    isButtonDisabled() {
      return this.data.booked_slots === this.data.slots || this.isActivityBooked;
    },
  },
  methods: {
    bookActivity(data) {
      this.$emit("bookActivity", data);
    },
    handleClick() {
      if (!(this.data.booked_slots === this.data.slots || this.isActivityBooked)) {
        this.bookActivity(this.data);
      }
    },
  }

};
</script>

<style scoped>

.booking-card-footer .btn-diabled-activity-full {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  width: 100%;
  max-width: 262.47px;
  min-height: 44.82px;
  text-align: center;
  background-color: #CAA8F5;
  color: #FFFFFF;
  cursor: not-allowed;
}

.booked-activity {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  width: 100%;
  max-width: 262.47px;
  min-height: 44.82px;
  text-align: center;
  background-color: black;
  color: #FFFFFF;
  cursor: not-allowed;
}

</style>