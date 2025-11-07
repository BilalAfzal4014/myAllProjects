<template>
  <div class="input-field-category-box mr-2">
    <select
      id="category"
      class=" rounded-full"
      name="category"
      @change="changeCategory($event)"
    >
      <option value="">
        Select Category
      </option>
      <option
        v-for="category in categories"
        :key="category.id"
        :value="category.id"
      >
        {{ category.title }}
      </option>
    </select>
    <down-chevron-icon />
  </div>
</template>

<script>
import * as toastr from "toastr";
import DownChevronIcon from "@/svgs/arrows/down-chevron-icon";

export default {
    name: "SelectCategory",
    components: {DownChevronIcon},
    data() {
        return {
            categories: [],
        };
    },
    methods: {
        changeCategory(event) {
            this.$emit("changed", event.target.value);
        },
        async loadCategories() {
            try {
                const response = await this.oldApi("get", this.constants.getUrl("getCategories"));
                this.categories = response.data;
            } catch (error) {
                toastr.error(error[0].response.data.errors[0].error);
            }
        }
    },
    mounted() {
        this.loadCategories();
    },
};
</script>

<style scoped></style>
