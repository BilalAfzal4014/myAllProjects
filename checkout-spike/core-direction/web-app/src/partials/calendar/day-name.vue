<template>
  <th>{{ dayname }}</th>
</template>

<script>
export default {
    props: {
        dayname: {
            type: String,
            default: "",
        },
    },
    name: "DayName"
};
</script>

<style scoped>

</style>