<template>
  <button :class="`${btnType} ${btnStyle} ${size} ${isDisabled ? 'disabled' : ''}`" :disabled="isDisabled">
    <slot />
  </button>
</template> 

<script>
export default {
  name: "Button",
  props: {
    btnType: {
      type: String,
      default: "",
    },
    btnStyle: {
      type: String,
      default: "",
    },
    size: {
      type: String,
      default: "",
    },
    isDisabled: {
      type: Boolean,
      default: false
    }
  },
};
</script>

<style lang="scss" scoped>
  @import "@/assets/scss/mixins/depth";

  button {
    background-color: transparent;
    font-family: 'Montserrat', sans-serif;
    padding: .75rem 1.25rem;
    border-radius: .25rem;
    font-weight: 700;
    font-size: 1rem;
    line-height: 1.3;
    @media (max-width: 768px){
      padding: 0.8rem 1rem;
      font-size: .9rem;

    }
    &.round {
      border-radius: 100%;
    }
    &.round-edge {
      border-radius: 2.125rem;
    }
    &.square {
     border-radius: .25rem;
    }
    &.sm {
      font-size: .875rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    &.md {
      width: 9.375rem;
      height: 2.75rem;
      padding: 0;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    &.lg {
      width: 12.5rem;
      height: 3.125rem;
      font-size: 1.125rem;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    &.xl {
      width: 18.75rem;
      height: 3.75rem;
      padding: 0;
      display: flex;
      font-size: 1.25rem;
      align-items: center;
      justify-content: center;
    }
    &.primary {
      background-color: $primary;
      color: $secondary-white;

      &:hover {
        background-color: $forth-primary;
      }
      &:active, &.active{
        background-color: $second-primary;
        color: $primary;
      }
    }
    &.secondary {
      background-color: $accent-white;
      color: $primary;

      &:hover {
        background-color: $secondary-white;
        border: 1px solid $primary;
      }
    }
    &.gradient {
      background: $btn-gradient;
      color: $secondary-white;

      &:hover {
        background: $btn-gradient-hover;
      }

      &:active, &.active{
        opacity: 0.65;
        background: $btn-gradient-active;
      }
    }
    &.default {
      background-color: $white;
      color: $cd-black;
      @include depth-sm;
    }
    &.disabled, &.disabled:hover{
      background: $accent-white;
      color: $primary-dark;
      cursor: not-allowed;
    }
  }

</style>
