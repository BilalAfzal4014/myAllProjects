<template>
  <div v-if="paginationCount.length > 1" class="pagination-box">
    <ul class="pagination-list flex items-center">
      <li class="pagination-item">
        <button
          v-if="currentPage > '1'"
          class="btn-pagination-prev rounded-full"
          @click="setCurrentPage(currentPage-1)"
        >
          <PreviousArrow />
        </button>
      </li>
      <li
        v-for="(paginatedNumber, index) in paginationCount"
        :key="`${index}-pagination-index${paginatedNumber}`"
        class="pagination-item"
        @click="setCurrentPage(Number(paginatedNumber))"
      >
        <button
          :class="`btn-pagination rounded-full ${
            Number(currentPage) === Number(paginatedNumber) ? 'active' : ''
          }`"
        >
          {{ paginatedNumber }}
        </button>
      </li>
      <li class="pagination-item">
        <button
          v-if="currentPage < paginationCount[paginationCount.length - 1]"
          class="btn-pagination-next rounded-full"
          @click="setCurrentPage(currentPage+1)"
        >
          <NextArrow />
        </button>
      </li>
    </ul>
  </div>
</template>

<script>
import PreviousArrow from "@/svgs/pagination/PreviousArrow";
import NextArrow from "@/svgs/pagination/NextArrow";
export default {
    name: "Pagination",
    components: {NextArrow, PreviousArrow },
    props: {
        paginationCount: {
            type: Array,
            default: null
        },
        currentPage: {
            type: Number,
            default: 1
        }
    },
    methods: {
        setCurrentPage(currentPageValue) {
            this.$emit("setCurrentPageValue", currentPageValue);
        }
    }
};
</script>

<style scoped>
.pagination-box {
  margin-top: 35px;
  overflow-x: auto;
  margin-left: auto;
  margin-right: auto;
  max-width: 350px;
}
.pagination-item {
  padding: 6px;
}
.pagination-list{
  margin: auto;
  width: fit-content;
}
.btn-pagination {
  color: #000000;
  background-color: #ffffff;
  width: 30px;
  height: 30px;
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 700;
  line-height: 14px;
  letter-spacing: 0;
  text-align: center;
}
.btn-pagination.active {
  color: #690fad;
  border: 1px solid #690fad;
}
.btn-pagination-prev,
.btn-pagination-next {
  background-color: #690fad;
  width: 40px;
  height: 40px;
}
.btn-pagination-prev svg,
.btn-pagination-next svg {
  margin: auto;
}
.no-groups {
  text-align: center;
  padding: 50px;
  font-family: "Montserrat", sans-serif;
  font-size: 24px;
  font-weight: 500;
}
</style>