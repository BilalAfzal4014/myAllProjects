<template>
	<nav class="navbar navbar-default">
  
  	<div class="container-fluid">
    
    <div class="navbar-header">
      
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      
      <a class="navbar-brand">Dashboard</a>
    
    </div>
    
    <div class="collapse navbar-collapse" id="myNavbar">
      
      <ul class="nav navbar-nav">
        
        <li ><router-link to="/dashboard/userDetails">Home</router-link></li>
        <li ><router-link to="/dashboard/userListing">See Users</router-link></li>
        <li ><router-link to="/dashboard/chat">Chat</router-link></li>
        
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        
        <li><a style="cursor:pointer" v-on:click="logOutUser"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
      
      </ul>
    
    </div>
  
  </div>

 </nav>
</template>

<script>
  export default{
     mounted(){
            this.userObj = this.getUserMixin();
        },
        data(){
            return{
                userObj:{},
            }
        },
        methods:{
            logOutUser(){
                this.$http.get('http://localhost:3000/logout', { 
                headers: {
                    'Content-type': 'application/json',
                    'id': this.userObj.id,
                    'userId': this.userObj.userId,
                    'Authorization': this.userObj.Authorization
                }
                }).then(response => {
                    if(response.data.status){
                      this.logOutUserMixin();
                      this.$router.go("/");
                    }
                })
                .catch(e => {

                });
            }
        }
}
</script>