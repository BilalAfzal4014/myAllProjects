<template>
  <input :type="inputType" :name="name" :placeholder="placeholder" :disabled="disabled" @input="$emit(`input`, $event.target.value)">
</template>

<script>
export default {
  name: "Input",
  $_veeValidate: {
    value () {
      return this.$el.value;
    },
    name () {
      return this.name;
    }
  },
  props: {
    name: {
      type: String,
      default: ""
    },
    value: {
      type: String,
      default: ""
    },
    placeholder: {
      type: String,
      default: ""
    },
    inputType: {
      type: String,
      default: "text"
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  mounted () {
    this.$el.value = this.value;
  },
};
</script>

<style lang="scss" scoped>
  input {
    border: 0.1rem solid $accent-white;
    background-color: $secondary-white;
    border-radius: .8rem;
    padding: 1.5rem 2.2rem;
    font-size: 1.4rem !important;
    font-weight: 300;
    width: 100%;
    &:focus {
      border: 0.1rem solid $primary;
    }
    &:disabled {
      background-color: rgba($accent-white,.5);
    }
  }

</style>
