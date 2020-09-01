<template>
	<div class="container">
		<h3>This is register page</h3>
  		<br/>            
  		<table class="table">
    		<thead>
      			<tr>
        			<th>Name</th>
        			<th>Register</th>
      			</tr>
    		</thead>
    		<tbody>
      			<tr v-for="user in users">
        			<td>{{user.name}}</td>
        			<td><button type="button" v-on:click="registerUser(user.id)">Register</button></td>
      			</tr>
    		</tbody>
  		</table>
	</div>
</template>

<script>
export default{
	/*data(){ // can also do it like this
		return{
			users: []
		}
	},
	mounted(){
		this.users = this.$store.getters.usersGetter;
	},*/
	methods:{
		registerUser(id){
			this.$store.dispatch('register',id);
		}
	},
	computed:{
		users(){
			return this.$store.getters.usersGetter;
		}
	}
}
</script>