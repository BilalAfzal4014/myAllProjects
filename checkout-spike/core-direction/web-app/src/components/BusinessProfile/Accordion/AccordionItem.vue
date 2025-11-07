<template>
  <button
    :aria-controls="controlsId"
    :aria-expanded="isExpanded"
    class="btn-accordion"
    role="button"
    @click="toggleAccordion"
  >
    <span>{{ itemName }}</span>
    <img
      :class="{ 'arrow-icon': true, 'active': isExpanded }"
      alt="arrow icon"
      src="@/assets/images/carrot-arrow-icon-top.svg"
    >
  </button>
</template>

<script>
export default {
  name: "AccordionItem",
  props: {
    itemName: {
      type: String,
      default: "",
    },
    isActive: {
      type: Boolean,
      required: true
    },
    controlsId: {
      type: String,
      default: ""
    },
  },
  data() {
    return {
      isExpanded: true
    };
  },
  watch: {
    isActive(newVal) {
      this.isExpanded = newVal;
    }
  },
  methods: {
    toggleAccordion() {
      this.isExpanded = !this.isExpanded;
      this.$emit("isActive", this.isExpanded);
    }
  }
};
</script>


<style lang="scss" scoped>
.btn-accordion {
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  padding: 28px;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  border-radius: 16px;
  background: #FFFFFA;
  -webkit-box-shadow: 0px 8px 24px 0px rgba(28, 4, 47, 0.08);
  box-shadow: 0px 8px 24px 0px rgba(28, 4, 47, 0.08);
  cursor: pointer;
  color: #690FAD;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  text-transform: capitalize;
  margin-top: 8px;
  @media screen and (max-width: 480px){
    padding: 24px;
    font-size: 17px;
  }
  .arrow-icon {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: #690FAD;
    border-radius: 50%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    padding: 4px;
    transform: rotate(180deg);
    -webkit-transform: rotate(180deg);
    -moz-transform: rotate(180deg);
    -ms-transform: rotate(180deg);
    -o-transform: rotate(180deg);

    &.active {
      transform: rotate(0deg);
      -webkit-transform: (0deg);
      -moz-transform: rotate(0deg);
      -ms-transform: rotate(0deg);
      -o-transform: rotate(0deg);
    }
  }
}
</style>