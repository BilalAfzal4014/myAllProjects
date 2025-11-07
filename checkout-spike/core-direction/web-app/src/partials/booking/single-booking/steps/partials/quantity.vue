<template>
  <div>
    <p class="package-detail-title">
      Select Quantity
    </p>
    <div class="select-quantity-box">
      <p class="__desc">
        How many slots do you want to book for this class
      </p>
      <div class="counter-box">
        <button class="btn-counter" @click="decrementTotal">
          -
        </button>
        <input :value="totalQuantity" class="counter-field" disabled placeholder="1" type="number">
        <button class="btn-counter" @click="incrementTotal">
          +
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "Quantity",
    props: {
        totalQuantity: {
            type: Number,
            required: true
        }
    },
    methods: {
        incrementTotal() {
            this.$emit("updateTotalQuantity", "increment", null);
        },
        decrementTotal() {
            this.$emit("updateTotalQuantity", "decrement", this.totalQuantity - 1);
        }
    }
};
</script>

<style scoped>
#cart .package-detail-box .select-quantity-box {
  padding: 34px 53px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  background-color: #FFFFFF;
  border-radius: 21px;
  -webkit-border-radius: 21px;
  -moz-border-radius: 21px;
  -ms-border-radius: 21px;
  -o-border-radius: 21px;
  -webkit-box-shadow: 0px 22px 40px 0px #0000001A;
  box-shadow: 0px 22px 40px 0px #0000001A;
  margin-bottom: 60px;
}

@media (max-width: 767px) {
  #cart .package-detail-box .select-quantity-box {
    padding: 24px 16px;
    border-radius: 10px;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    -ms-border-radius: 10px;
    -o-border-radius: 10px;
    margin-bottom: 43px;
  }

  #cart .package-detail-box .select-quantity-box .counter-box {
    margin: 20px auto 0;
  }
}

#cart .package-detail-box .select-quantity-box .__desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

#cart .package-detail-box .select-quantity-box .counter-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

#cart .package-detail-box .select-quantity-box .counter-field {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  width: 85px;
  height: 42px;
  border-radius: 8px;
  background-color: #FFFFFF;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
  margin-left: 8px;
  margin-right: 8px;
  -webkit-box-shadow: 0px 2px 4px 0px #00000040;
  box-shadow: 0px 2px 4px 0px #00000040;
}

#cart .package-detail-box .select-quantity-box .btn-counter {
  font-family: 'Montserrat', sans-serif;
  font-size: 32px;
  font-style: normal;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  width: 42px;
  height: 42px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  color: #FFFFFF;
  background-color: #690FAD;
  -webkit-box-shadow: 0px 2px 4px 0px #00000040;
  box-shadow: 0px 2px 4px 0px #00000040;
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
}
</style>