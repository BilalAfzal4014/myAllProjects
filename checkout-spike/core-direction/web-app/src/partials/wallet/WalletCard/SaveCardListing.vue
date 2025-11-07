<template>
  <div class="payment-detail-cards-box items-center mt-5">
    <payment-card v-for="card in cards" :key="card.id" :card="card" @delete="deleteCard" />
  </div>
</template>

<script>

import * as toastr from "toastr";
import PaymentCard from "@/components/wallet/payment/payment-card";

export default {
    name: "SaveCardListing",
    components: {PaymentCard},
    data() {
        return {
            cards: []
        };
    },
    created() {
        this.fetchCards();
    },
    methods: {
        async deleteCard(cardId) {
            try {
                await this.oldApi("delete", `${this.constants.getUrl("deleteCard")}${cardId}`);
                toastr.success("Card has been deleted successfully.");
                this.cards = this.cards.filter(card => card.id !== cardId);
            } catch (error) {
                this.handleError(error);
            }
        },
        async fetchCards() {
            try {
                const response = await this.oldApi("get", this.constants.getUrl("getCardListing"));
                this.cards = response.data;
            } catch (error) {
                this.handleError(error);
            }
        },
        handleError(error) {
            toastr.error(error[0].response.data.errors[0].error);
        }
    }


};
</script>
