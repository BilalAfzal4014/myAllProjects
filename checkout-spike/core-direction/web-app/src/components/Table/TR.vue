<template>
  <div class="wallet-table-row table-tr" :class="{'active' : show}">
    <slot name="rowCollapse" />
    <ul class="wallet-table-content-list">
      <slot />
    </ul>
  </div>
</template>

<script>
export default {
  name: "TR",
  props: {
    show: {
      type: Boolean,
      default: false
    }
  }
};
</script>