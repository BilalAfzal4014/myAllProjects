<template>
  <div>
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'app',
  created(){
    this.makeSocketConnection();
  },
  mounted(){
    
  },
  data () {
    return {
      userObj:{},
    }
  },
  methods:{
    makeSocketConnection(){
        this.userObj = this.getUserMixin();
        
        var vueScope = this;
        this.socket().on('connect', function(){ 
          vueScope.socket().emit('login', vueScope.userObj);
        });
    }
  }
}
</script>

<style lang="scss">


</style>
