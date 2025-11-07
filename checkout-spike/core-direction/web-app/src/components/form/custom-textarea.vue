<template>
  <div className="bio-box">
    <slot name="label" />
    <textarea
      :id="inputId"
      :class="[classNames, { 'no-resize': !resizable }, { 'no-overflow': !overflowY }]"
      :maxlength="maxlength < 0 ? null : maxlength"
      :placeholder="placeholder"
      :value="value"
      @input="onInput"
      @keyup.enter="nextStep"
    />
    <div class="textarea-underline" />
    <p className="error-message">
      {{ badWordError }}
    </p>
    <slot name="errors" />
  </div>
</template>

<script>
import BadWordsFilter from "bad-words";

export default {
    name: "CustomTextarea",
    props: {
        inputId: {
            type: String,
            required: true,
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
        maxlength: {
            type: Number,
            default: -1,
        },
        resizable: {
            type: Boolean,
            default: true,
        },
        overflowY: {
            type: Boolean,
            default: true,
        },
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
        nextStep() {
            this.$emit("nextStep");
        },
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

.no-resize {
  resize: none;
}

textarea {
  width: 100%;
}

.textarea-underline {
  border-bottom: 1px solid black;
}
</style>
