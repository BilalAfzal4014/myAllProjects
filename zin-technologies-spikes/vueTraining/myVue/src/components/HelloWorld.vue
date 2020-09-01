<template>
  
<div>
<h1>{{msg}}</h1>
<button v-if="disappearBtn()" v-on:click="goNext">Add User</button>
</div>

</template>

<script>
export default {
  name: 'HelloWorld',
  mounted(){
    this.setUserMixin(this.user);
  },
  data () {
    return {
      msg: 'This is home page',
      user: {  
          fname: 'Amna', 
          lname: 'Bilal'
        }
    }
  },
  methods:{
    goNext(){
      this.$router.push('/addUsers');
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
