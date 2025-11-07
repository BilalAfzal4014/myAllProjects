<template>
  <td>{{ day }}</td>
</template>

<script>

export default {
    props:{
        day: {
            type: Number,
            default: 0,
        },
    },
    name: "Date"
};
</script>

<style scoped>

</style>