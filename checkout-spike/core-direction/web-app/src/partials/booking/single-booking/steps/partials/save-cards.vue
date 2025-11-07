<template>
  <div class="my-card-list">
    <div v-for="saveCard in savedCreditCards" :key="saveCard.id"
         :class="selectedCreditCard && selectedCreditCard.id === saveCard.id ? 'active': ''"
         class="my-card-box"
         @click="chooseSaveCard(saveCard)"
    >
      <div class="payment-detail-card">
        <div class="payment-card-header flex items-center justify-between">
          <p class="card-title">
            {{ saveCard.card_type }}
          </p>
          <img alt="" src="/assets/images/payment-icons/card-01.png">
        </div>
        <div class="payment-card-body">
          <p class="card-number">
            **** **** **** {{ saveCard.last_four }}
          </p>
        </div>
        <div class="payment-card-footer">
          <p class="cardholder-name">
            {{ saveCard.card_holder_name }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import toastr from "toastr";

export default {
    props: {
        makePaymentWithSaveCardInfoGetter: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            savedCreditCards: [],
            selectedCreditCard: null
        };
    },
    mounted() {
        this.fetchPreReqData();
    },
    methods: {
        fetchPreReqData() {
            this.fetchSaveCreditCardsOfUser();
        },
        fetchSaveCreditCardsOfUser() {
            this.oldApi("get",
                this.constants.getUrl("getAllCardsOfAUser")
            ).then((response) => {
                this.savedCreditCards = response.data;
            }).catch((error) => {
                if (error[0]?.response?.data) {
                    for (let errorElement of error[0].response.data.errors) {
                        toastr.error(errorElement.error);
                    }
                }
            });
        },
        chooseSaveCard(card) {
            this.selectedCreditCard = card;
        }
    },
    watch: {
        makePaymentWithSaveCardInfoGetter(newVal, oldVal) {
            if (this.selectedCreditCard === null) {
                toastr.error("Please choose credit card for payment");
                this.$emit("sendPaymentResponse", {validation: "failed"});
            } else {
                this.$emit("sendPaymentResponse", {
                    validation: "success",
                    exisiting_card_id: this.selectedCreditCard.id
                });
            }
        }
    }
};
</script>

<style>
</style>