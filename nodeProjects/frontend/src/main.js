import Vue from 'vue'
import App from './App.vue'
import router from './router/index';
import store from './store/store';
import axios from 'axios';

Vue.prototype.$http = axios; //$http is a variable which can be used globally and access by every components

router.beforeEach((to, from, next) => {
  
  if(store.getters.getUserObj != null && !to.meta.requiresAuth){
      next('/dashboard/userDetails');
  }
  else if(store.getters.getUserObj == null && to.meta.requiresAuth){
      next('/');
  }
  else{
    next();  
  }

});

Vue.mixin({
	methods:{
    	setUserMixin(obj){
      		this.$store.dispatch('storeAction_saveCredentails', obj);
    	},
    	getUserMixin(){
      		return this.$store.getters.getUserObj;
    	},
    	logOutUserMixin(){
    		this.$store.dispatch('storeAction_logOut');
    	},
      socket(){
        return this.$store.getters.getSocket;
      }
  	},
});


new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
