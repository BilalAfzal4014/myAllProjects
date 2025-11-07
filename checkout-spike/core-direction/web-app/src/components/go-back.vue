<template>
  <button class="btn-back flex items-center" @click="$router.go(-1)">
    <div
      class="btn-back-icon-box flex items-center justify-center rounded-full"
    >
      <black-chevron-back-icon />
    </div>
    <span class="btn-back-content"> Go Back </span>
  </button>
</template>

<script>
import BlackChevronBackIcon from "@/svgs/arrows/black-chevron-back-icon";

export default {
    name: "GoBack",
    components: {BlackChevronBackIcon}
};
</script>

