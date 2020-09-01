<template>
	<div class="container">
  		<h3>This is register user page</h3>
  		<br/>
  		<span>Total registrations: {{total}}</span>            
  		<table class="table">
    		<thead>
      			<tr>
        			<th>Name</th>
        			<th>unRegister</th>
      			</tr>
    		</thead>
    		<tbody>
      			<tr v-for="user in registerUsers">
        			<td>{{user.name}}</td>
        			<td><button type="button" v-on:click="unRegisterUser(user.id)">unRegister</button></td>
      			</tr>
    		</tbody>
  		</table>
	</div>
</template>

<script>
export default{
	methods:{
		unRegisterUser(id){
			this.$store.dispatch('unRegister',id);
		}
	},
	computed:{
		registerUsers(){
			return this.$store.getters.registerUsersGetters;
		},
		total(){
			return this.$store.getters.totalRegistrations;	
		}
	}
}
</script>