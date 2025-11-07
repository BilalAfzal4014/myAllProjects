<template>
  <section id="newsletters" class="bg-gradient mt-16">
    <div class="sub-container flex">
      <h4 class="newsletter-title mr-10">
        Be Inspired
      </h4>
      <div class="newsletter-email-filed-box mr-6">
        <div class="group">
          <label for="email">Email</label>
          <input id="email" type="email" class="cool border-b-1 border-black">
          <svg class="" width="21" height="21" viewBox="0 0 21 21" fill="none"
               xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M17.9246 13.5754C16.7811 12.4318 15.4198 11.5853 13.9346 11.0726C15.5252 9.97705 16.5703 8.14365 16.5703 6.07031C16.5703 2.72303 13.8473 0 10.5 0C7.15271 0 4.42969 2.72303 4.42969 6.07031C4.42969 8.14365 5.47477 9.97705 7.06535 11.0726C5.58018 11.5849 4.21887 12.4318 3.07535 13.5754C1.09225 15.5585 0 18.1954 0 21H1.64062C1.64062 16.115 5.61504 12.1406 10.5 12.1406C15.385 12.1406 19.3594 16.115 19.3594 21H21C21 18.1954 19.9078 15.5585 17.9246 13.5754ZM10.5 10.5C8.05752 10.5 6.07031 8.51279 6.07031 6.07031C6.07031 3.62783 8.05752 1.64062 10.5 1.64062C12.9425 1.64062 14.9297 3.62783 14.9297 6.07031C14.9297 8.51279 12.9425 10.5 10.5 10.5Z"
              fill="black"
            />
          </svg>
        </div>
      </div>
      <button class="rounded-full btn-newsletters">
        Subscribe
      </button>
    </div>
  </section>
</template>

<script>
export default {
    name: "SubscribeComponent"
};
</script>

<style scoped>

</style>