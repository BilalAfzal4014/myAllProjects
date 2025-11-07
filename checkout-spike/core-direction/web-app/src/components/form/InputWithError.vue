<template>
  <div class="input-with-error">
    <Input v-validate="validate" :inputType="inputType" :name="inputName" :placeholder="placeholder" />
    <Error>{{ errors.first(inputName) }}</Error>
  </div>
</template>

<script>
import { Input, Error } from "@/components/form";
export default {
  name: "InputWithError",
  props: {
    validate: {
      type: String,
      default: "",
    },
    inputType: {
      type: String,
      default: "text",
    },
    inputName: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "",
    },
  },
  components: {
    Input,
    Error
  }
};
</script>