<template>
  
<div>
  <span>coming from local storage: {{user.lname}} </span><!--{{user.lname}}-->
  <h1>{{msg}}</h1>
  <button v-on:click="backPage">Home</button>
  <listing v-bind:usersArray="userArr"></listing>
  <add-user v-on:addUserClicked="getUserFromChild"></add-user>
</div>

</template>

<script>
import addUser from './addUser';
import listing from './listing';
export default {
  name: 'users',
  components:{
  	'add-user': addUser,
  	'listing': listing
  },
  mounted(){
    //this.user = this.$store.getters.getUser; // can get directly and also by mixin
    this.user =  this.$store.getters.getUser;//this.getUserMixin(); // i will get through mixin for practise
    //console.clear();
    //console.log(this.getUserMixin());

  },
  data(){
  	return{
  		msg: 'This is add user page',
      user: [],
  		userArr: []
  	}
  },
  methods:{
    backPage(){
      this.$router.push("/");
    },
    getUserFromChild(user){
    	var myUser = {
    		name: user.name,
    		password: user.password
    	};
    	this.userArr.push(myUser);
    	//this.userArr.push(Vue.util.extend({}, user));
    },
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
