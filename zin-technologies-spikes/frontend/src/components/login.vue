
<template>
<div class="content" style="overflow-x: hidden;">

  <div class="row">

    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="margin-left:23%; margin-top:10%;">

      <h3>Login here</h3>

      <form id="myForm">

      <div class="form-group">
        <label for="email">Email:</label> 
        <input class="form-control" type="text" id="email" name="email" v-model="logObj.email">
        <span class="help-block error" v-if="emptyCheck(logObj.email)">Email Required</span>
        <span class="help-block error" v-if="inValidCheck(logObj.email)">Invalid Email</span>
      </div>

      <div class="form-group">
        <label for="password">Password:</label> 
        <input class="form-control" type="password" id="password" name="password" v-model="logObj.password">
        <span class="help-block error" v-if="emptyCheck(logObj.password)">Password Required</span>
      </div>

      <a  class="btn btn-link pull-left" >Forgot Password?</a>

      <button type="button" class="btn btn-lg btn-primary btn-block" v-on:click="loginUser">Login</button>

      <h2></h2>

    <div class="form-group">
      <div class="col-md-12 control">
        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
          Don't have an account! 
        <a >   <!-- we can also write here full url i.e http://localhost/formsub/urlSignUp  and urlSignUp  when project goes on i will check this--> 
          Sign Up Here
        </a>
        </div>
      </div>
    </div>    


    </form>

  </div>

  </div>

  </div>
</template>


<script>
  export default{
      mounted(){
        this.events();
      },
      data(){
        return{
            clickPressed: false,
            logObj:{
                email: "",
                password:"",
            },
        }
      },
      methods:{
        loginUser(){
          this.clickPressed = true;
          if(!this.validation()){
              this.matchCredentials();
          }
        },
        matchCredentials(){
            this.$http.post('http://localhost:3000/login', this.logObj).then(response => {
              if(response.data.status){
                this.setUserMixin(response.data.data);
                this.$router.go("/dashboard/userDetails");
              }
              else{
                toastr.error(response.data.message);
              }
            })
            .catch(e => {

            });
        },
        validation(){
          if(this.emptyCheck(this.logObj.email) || this.inValidCheck(this.logObj.email) || this.emptyCheck(this.logObj.password))
              return true;

            return false;
                    
        },
        emptyCheck(value){
           if(value == "" && this.clickPressed){
              return true;
          } 
          return false; 
        },
        inValidCheck(email){
            var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/igm;
            if(email != ""){
                if (!re.test(email) && this.clickPressed) {
                  return true;      
                }
            }
            return false;
        },
        events(){
          var vueScope = this;
          $(document).keypress(function(e){
              if(e.which == 13)
              vueScope.loginUser();
          });
        }
      }
    
  }
</script>

<style>

</style>