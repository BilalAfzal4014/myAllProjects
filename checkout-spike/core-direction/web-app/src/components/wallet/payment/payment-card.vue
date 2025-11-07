<template>
  <div class="payment-detail-card-outer-box">
    <div class="payment-detail-card-inner-box">
      <div class="payment-detail-card">
        <div class="payment-card-header flex items-center justify-between">
          <p class="card-title">
            {{ card.card_type }}
          </p>
          <div class="card-icon-box flex items-center">
            <span class="first-icon rounded-full" />
            <span class="second-icon rounded-full" />
          </div>
        </div>
        <div class="payment-card-body">
          <p class="card-number">
            **** **** **** {{ card.last_four }}
          </p>
        </div>
        <div class="payment-card-footer">
          <p class="cardholder-name">
            {{ card.card_holder_name }}
          </p>
        </div>
      </div>
      <button class="btn-delete-payment-detail flex items-center" @click="onDelete(card.id)">
        <delete-icon />
        Delete Card
      </button>
    </div>
  </div>
</template>

<script>
import DeleteIcon from "../../../svgs/wallet/delete-icon";

export default {
    name: "PaymentCard",
    components: {DeleteIcon},
    props: {
        card: {
            type: Object,
            required: true
        }
    },
    methods: {
        onDelete() {
            this.$emit("delete", this.card.id);
        },
    }
};
</script>

<style scoped>

</style>