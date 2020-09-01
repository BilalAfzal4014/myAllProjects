import Vue from 'vue'
import App2 from './App'
import router from './router/index'
import store from './store/store'






export const bus = new Vue();


//global directive

Vue.directive('mydirective', {
  bind:function(el,binding,vnode){
    //alert(binding.arg);
    //alert(binding.expression);
    //alert(binding.modifiers.a);
    //alert(binding.modifiers.b);
    if(binding.value){
        $(el).css({color: binding.value});
    }
    else{
       $(el).css({color: 'gray'});
    }
  }
  // When the bound element is inserted into the DOM...
  // bind: called only once, when the directive is first bound to the element. This is where you can do one-time setup work.
  // inserted: called when the bound element has been inserted into its parent node (this only guarantees parent node presence, not necessarily in-document).
  // update: called after the containing component’s VNode has updated, but possibly before its children have updated. The directive’s value may or may not have changed, but you can skip unnecessary updates by comparing the binding’s current and old values (see below on hook arguments).
  // componentUpdated: called after the containing component’s VNode and the VNodes of its children have updated.
  // unbind: called only once, when the directive is unbound from the element.
  // We’ll explore the arguments passed into these hooks (i.e. el, binding, vnode, and oldVnode) in the next section.

  // var s = JSON.stringify
  //   el.innerHTML =
  //     'name: '       + s(binding.name) + '<br>' +
  //     'value: '      + s(binding.value) + '<br>' +
  //     'expression: ' + s(binding.expression) + '<br>' +
  //     'argument: '   + s(binding.arg) + '<br>' +
  //     'modifiers: '  + s(binding.modifiers) + '<br>' +
  //     'vnode keys: ' + Object.keys(vnode).join(', ')

})







Vue.config.productionTip = false
router.beforeEach((to, from, next) => {
  
  if(store.getters.getUserName && !to.meta.requiresAuth){
      next('/registerUsers');
  }
  else if(!store.getters.getUserName  && to.meta.requiresAuth){
      // next(false); //or
      next('/');
  }
  else{
    next();  
  }

});

// vue mixin methods cannot be called in router.beforeEach

Vue.mixin({
  
  methods:{
    disappearBtn(){
      return false;
    },
    setUserMixin(obj){
      this.$store.dispatch('setUserAction', obj);
    },
    getUserMixin(){
      return this.$store.getters.getUser;
    }

  },

});



// global Filter
/*
  Vue.filter('capitalize', function (value) {
  if (!value) return ''
  value = value.toString()
  return value.charAt(0).toUpperCase() + value.slice(1)
})
*/



// global component
/*
Vue.component('button-counter', {
  data: function () {
    return {
      count: 0
    }
  },
  template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
})
*/


/* eslint-disable no-new */
new Vue({
  el: '#app1',
  router,
  store,
  render: h => h(App2)
})

