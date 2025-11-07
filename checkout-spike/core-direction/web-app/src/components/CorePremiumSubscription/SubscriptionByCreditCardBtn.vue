<template>
  <div class="btn-core-premium-subscribe">
    <button>  Confirm & Pay AED {{price}}</button>
  </div>
</template>

<script>
export default {
  name: "SubscriptionByCreditCardBtn",
  props:{
    price: {
      type: Number,
      default : 36.75
    }
  }
};
</script>

<style lan="scss" scoped>
   .btn-core-premium-subscribe {
        display: flex;
        align-items: center;
        column-gap: 10px;
        color: #FFFFFA;
        text-align: center;
        font-family: "Montserrat", sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: normal;
        padding: 12px 24px;
        background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
        border-radius: 30px;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        -ms-border-radius: 30px;
        -o-border-radius: 30px;
        width: max-content;
    }

</style>