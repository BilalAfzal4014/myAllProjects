<template>
  <div class="input-field-search-box mr-4">
    <input type="text" :placeholder="`${placeholderText}`" 
      :class="[
        'location',
        { 'rounded-full': isRounded },
      ]"
      @blur="handleBlur"
      :value="address"
    />
  </div>
</template>

<script>

export default {
    name: "SearchLocation",
    props: {
        address: {
            type: String,
            default: "",
        },
        showIcon: {
            type: Boolean,
            default: true,
        },
        isRounded: {
            type: Boolean,
            default: true,
        },
        placeholderText: {
            type: String,
            default: "Search Location..."
        }
    },
    methods: {
        setPlace() {
            this.$emit("changed", "");
        },
        handleBlur() {
            return this.$refs.gmapAutocomplete.$el.value = "";
        }
    },
};  
</script>

<style scoped>
.input-field-search-box input {
  width: 100%;
}
</style>