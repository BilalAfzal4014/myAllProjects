<template>
	<div>
		<h1>This is the parent of first and second child</h1>
		<router-link to="/childRoute" style="margin-left:5px;">First Child</router-link>
		<router-link to="/secondChildRoute" style="margin-left:5px;">Second child</router-link>
		<unregister-users></unregister-users>
		<register-user></register-user>
		<router-view/>
		<router-view name="optional" />
		<button v-on:click="logOut">logOut</button>
	</div>
</template>

<script>
import	unregisterusers from './unregisterUsers.vue'
import registerUser from './registerUser.vue'
export default{
	name: 'registerUsers',
	components:{
		'unregister-users': unregisterusers,
		'register-user': registerUser
	},
	methods:{
		logOut(){
			this.$store.dispatch('logOutAction');
			//this.$router.push('/addUsers');
			window.location.reload(); // also this.$router.go(); will do the same
		}	
	}
	
}
</script>