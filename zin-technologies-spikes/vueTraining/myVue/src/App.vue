<template>
  <div id="app">
    <div v-if="show">
      <h1 v-mydirective>{{msg}}</h1>
      <h1 v-mydirective:red.a.b="'brown'">{{msg}}</h1>
      <hr>
      <router-link to="/"> Home</router-link>
      <router-link to="/parameter/1" style="margin-left:5px;">Parameter</router-link>
      <router-link to="/addUsers" style="margin-left:5px;"> Add User</router-link>
      <router-link to="/registerUsers" style="margin-left:5px;">Register User</router-link>
      <router-view :key="$route.fullPath" /> <!-- the route will change w.r.t full url, we have done this bcz the problem arrives when there is paramter in routing the latest component do not load w.r.t change in parameter bcz it only look at routing which will still the same. after adding this key it will also consider the router full path --> 
    </div>
    <!-- by emitting custom event -->
    <!-- <router-view name="notFound" v-on:hideShowAppAll="hideShowIt" />  -->

    <!-- by emitting through bus, bus is also used to send and rcv data between siblings without using parent -->
    <router-view name="notFound" />
  </div>
</template>

<script>
import {bus} from './main'
export default {
  name: 'App',
  created(){
    //  will listen on creation of component
    bus.$on('hideShowAppAll', (data) => {
      this.show = data;
    });
  },
  data(){
    return{
      msg: 'This is header and it will stay here',
      show: true
    }
  },
  methods:{
    // hideShowIt(val){
    //   this.show = val;
    // }
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
