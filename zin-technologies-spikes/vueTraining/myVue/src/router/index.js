import Vue from 'vue'
import vueRouter from 'vue-router'
import routes from './route';


Vue.use(vueRouter)
export default new vueRouter({
  routes,
  //mode:'history',  // to eliminate #
  linkActiveClass: "active", // active class for non-exact links.
  //linkExactActiveClass: "active" // active class for exact links.
})
