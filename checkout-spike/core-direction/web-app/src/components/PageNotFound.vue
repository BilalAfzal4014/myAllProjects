<template>
  <div>
    <section id="page-not-found">
      <div class="custome-container flex items-center justify-center">
        <div class="page-not-found-content-box">
          <h4 class="page-not-found-title text-center">
            404
          </h4>
          <p class="page-not-found-subtitle text-center">
            Sorry, page not found
          </p>
          <p class="page-not-found-desc text-center">
            The page you requested could not be found
          </p>
          <router-link to="/" class="btn-page-not-found rounded-full">
            Home
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
    name: "PageNotFound",
};
</script>

<style scoped>
/*======================================
		01. 404
======================================*/
#page-not-found {
  padding-top: 50px;
  padding-bottom: 50px;
}
#page-not-found .custome-container {
  min-height: 100vh;
  height: 100%;
}
#page-not-found .page-not-found-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 276px;
  font-weight: 700;
  line-height: 323px;
  color: #690FAD;
  margin: 0;
}
@media screen and (max-width: 991px) {
  #page-not-found .page-not-found-title {
    font-size: 144px;
    line-height: 169px;
  }
}
#page-not-found .page-not-found-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 48px;
  font-weight: 500;
  line-height: 0px;
  color: #690FAD;
  margin: 0;
}
@media screen and (max-width: 991px) {
  #page-not-found .page-not-found-subtitle {
    font-size: 24px;
  }
}
#page-not-found .page-not-found-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 400;
  line-height: 28px;
  margin-top: 55px;
  margin-bottom: 66px;
}
@media screen and (max-width: 991px) {
  #page-not-found .page-not-found-desc {
    font-size: 14px;
    line-height: 16px;
    margin-top: 42px;
    margin-bottom: 42px;
  }
}
#page-not-found .btn-page-not-found {
  display: block;
  width: 134px;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 21px;
  letter-spacing: 0em;
  color: #FFFFFF;
  background-color: #690FAD;
  padding: 9px 42px 10px;
  margin: 0 auto 40px;
}
</style>