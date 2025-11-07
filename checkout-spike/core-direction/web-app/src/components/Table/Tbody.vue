<template>
  <div ref="tableBody" class="wallet-table-body">
    <slot />
  </div>
</template>

<script>
export default {
  name: "Tbody",
  props: {
    numberOfPages: {
      type: Number,
      default: 1,
    },
  },
  data() {
    return {
      currentPage: 0,
      observer: null,
    };
  },
  mounted() {
    this.$nextTick(() => {
      this.observeLastRow();
    });
  },
  beforeDestroy() {
    if (this.observer) {
      this.observer.disconnect();
    }
  },
  updated() {
    this.$nextTick(() => {
      this.observeLastRow();
    });
  },
  methods: {
    observeLastRow() {
      if (this.observer) {
        this.observer.disconnect();
        this.observer = null;
      }

      if (this.currentPage >= this.numberOfPages) {
        return;
      }

      const options = {
        root: this.$refs.tableBody,
        rootMargin: "10px",
        threshold: 1.0,
      };

      this.observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting && this.currentPage < this.numberOfPages) {
            this.currentPage++;
            this.$emit("onScroll", this.currentPage);
          }
        });
      }, options);

      const rows = this.$refs.tableBody?.querySelectorAll(".table-tr");
      if (rows) {
        const lastRow = rows[rows.length - 1];

        if (lastRow) {
          this.observer.observe(lastRow);
        }
      }
    },
  },
};
</script>