<template>
  <div class="field-box">
    <slot name="label" />
    <div class="flex">
      <input
        :id="inputId"
        :class="classNames"
        :placeholder="placeholder"
        :disabled="disabled"
        :type="type"
        :value="value"
        @input="onInput"
        @keyup.enter="nextStep"
      >
      <slot name="icons" />
    </div>
    <p class="error-message">
      {{ badWordError }}
    </p>
    <slot name="errors" />
  </div>
</template>

<script>
import BadWordsFilter from "bad-words";

export default {
    name: "CustomInput",
    props: {
        inputId: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: "text",
        },
        placeholder: {
            type: String,
            default: "",
        },
        value: {
            type: String,
            default: "",
        },
        classNames: {
            type: String,
            default: "input-field",
        },
        disabled: {
            type: Boolean,
            default: false
        }

    },
    data() {
        return {
            inputValue: this.value,
            badWordError: "",
        };
    },
    computed: {
        filter() {
            return new BadWordsFilter();
        },
    },
    methods: {
        onInput(event) {
            this.inputValue = event.target.value;
            this.checkBadWords();
        },
        checkBadWords() {
            if (this.filter.isProfane(this.inputValue)) {
                this.badWordError = "The entered text contains inappropriate words.";
            } else {
                this.badWordError = "";
            }
            this.$emit("badWordErrorChange", this.badWordError);
            this.$emit("input", this.inputValue);
        },
        nextStep(e) {
            this.$emit("nextStep", e);
        }
    },
    watch: {
        value(newVal) {
            this.inputValue = newVal;
        },
    },
};
</script>

<style scoped>
.error-message {
  color: red;
  font-size: 12px;
  margin-top: 5px;
  margin-bottom: 5px;
}
</style>
