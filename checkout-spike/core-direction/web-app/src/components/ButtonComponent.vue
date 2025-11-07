<template>
  <button :class="computedClass" @click="$emit('click')">
    <slot />
  </button>
</template>

<script>
export default {
    name: "ButtonComponent",
    props: {
        type: {
            type: String,
            default: "",
        },
    },
    computed: {
        computedClass() {
            const baseClass = "rounded-full block mx-auto";
            const typeClass = this.type === "cancel" ? "btn-modal-close btn-cancel" : "btn-booking-confirmation";
            return `${baseClass} ${typeClass}`;
        },
    },
};
</script>

