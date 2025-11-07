<template>
  <div class="booking-card-footer">
    <div class="grid grid-cols-2 gap-3">
      <div v-if="cardType !== 'subscription'">
        <router-link :to="bookingLink">
          <button class="btn-book-now uppercase rounded-full" type="button">
            Use Package
          </button>
        </router-link>
      </div>
      <div v-else>
        <router-link :to="listingLink">
          <button class="btn-book-now uppercase rounded-full" type="button">
            Use
          </button>
        </router-link>
      </div>
      <button class="btn-book-secondary uppercase rounded-full" name="button" type="button"
              @click="showPackageDetailModal(pack)"
      >
        Details
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ActionButtons",
  computed: {
    bookingLink() {
      return {
        name: "Booking",
        params: {id: this.company_id},
        query: {
          lat: this.formatCoordinate(this.latitude),
          lng: this.formatCoordinate(this.longitude)
        }
      };
    },
    listingLink() {
      return {
        name: "Listing"
      };
    },
  },
  props: {
    cardType: {
      type: String,
      default: "membership"
    },

    latitude: {
      type: String,
      default: null
    },
    longitude: {
      type: String,
      default: null
    },
    pack: {
      type: Object
    },
    company_id: {
      type: Number
    }
  },
  methods: {
    showPackageDetailModal(pack) {
      this.$emit("showPackageDetailModal", pack);
    },
    formatCoordinate(coordinate) {
      return coordinate === "NAN" ? "" : coordinate;
    },
    showDetailModal(pack) {
      this.$emit("showDetailModal", pack);
    }

  }


};
</script>

