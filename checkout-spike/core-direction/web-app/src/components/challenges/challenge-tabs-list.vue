<template>
  <ul class="challenges-tab-list flex items-center">
    <li
      v-for="(tab, index) in tabs"
      :key="index"
      class="challenges-tab-item"
    >
      <button
        :class="{ active: isActiveTab(tab.value) }"
        class="challenges-tab-link"
        @click="$emit('updateShowInfo', tab.value)"
      >
        {{ tab.label }}
      </button>
    </li>
  </ul>
</template>

<script>
export default {
    name: "ChallengeTabsList",
    props: {
        showInfo: {
            type: Boolean,
            required: true,
        },
        tabs: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    methods: {
        isActiveTab(tabValue) {
            return tabValue === this.showInfo;
        },
    },
};
</script>

