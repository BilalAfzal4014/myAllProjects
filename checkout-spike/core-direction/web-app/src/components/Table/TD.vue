<template>
  <li class="wallet-table-content-item">
    <span class="table-small-screen-heading">{{ title }}</span>
    <span class="table-data">
      <slot />
    </span>
  </li>
</template>

<script>
export default {
  name: "TD",
  props: {
    title: {
      type: String,
      default: "",
    }
  }
};
</script>