<template>
  <div class="form-group">
    <input
      :id="inputId"
      v-model="localIsChecked"
      :name="inputName"
      :style="inputStyle"
      :value="inputValue"
      type="checkbox"
      @change="handleChange"
    >
    <label :for="inputId">
      <slot />
    </label>
  </div>
</template>

<script>
export default {
  name: "CustomCheckbox",
  props: {
    inputId: {
      type: String,
      required: true,
    },
    inputName: {
      type: String,
      required: true,
    },
    inputValue: {
      type: String,
      required: true,
    },
    inputStyle: {
      type: String,
      default: "",
    },
    isChecked: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      localIsChecked: this.isChecked,
    };
  },
  watch: {
    isChecked(newVal) {
      this.localIsChecked = newVal;
    },
    localIsChecked(newVal) {
      if (newVal !== this.isChecked) {
        this.$emit("update:isChecked", newVal);
      }
    }
  },
  methods: {
    handleChange(event) {
      this.$emit("checked-value", event);
    },
  }

};
</script>
