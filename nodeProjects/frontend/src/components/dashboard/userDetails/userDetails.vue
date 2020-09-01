<template>
	<div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Your Details
            </div>
    
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6 col-lg-6" style="margin-left:23%; margin-top:2%;">

                        <form id="myForm">

                            <div class="form-group">
                                <label for="email">Email:</label> 
                                <input class="form-control"  id="email" name="email" v-model="userDetails.email" readonly>
                            </div>

                            <div class="form-group" >   
                                <label for="contactNo">Contact Number:</label> 
                                <input class="form-control" type="contactNo" id="contactNo" name="contactNumber" v-model="userDetails.contactNo">
                                <span class="help-block error" v-if="emptyCheck(userDetails.contactNo)">Contact number is required</span>
                            </div>

                            <span class="help-block" id="update"></span>

    					   <button type="button" class="btn btn-lg btn-primary btn-block" v-on:click="updateDetails">Update</button>

                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</template>

<script>
    export default{
        created(){
            this.socket().emit('updation');
        },
        mounted(){
            this.userObj = this.getUserMixin();
            this.getUserDetails();
            this.socket().on('updation', function(data){
                alert(data);
            });
        },
         beforeDestroy(){
            this.socket().off('updation');
            //this.socket().removeListener('updation');            
        },
        data(){
            return{
                loading:false,
                clickPressed: false,
                userObj:{},
                userDetails:{}    
            }
        },
        methods:{
            getUserDetails(){
                var obj = {
                    userId: this.userObj.userId
                };
                this.$http.post('http://localhost:3000/user', obj, { 
                headers: {
                    'Content-type': 'application/json',
                    'id': this.userObj.id,
                    'userId': this.userObj.userId,
                    'Authorization': this.userObj.Authorization
                }
                }).then(response => {
                    this.userDetails = response.data.data;
                })
                .catch(e => {

                });
            },
            updateDetails(){
                this.clickPressed = true;
                if(!this.validation()){
                    var obj = {
                        userId: this.userObj.userId,
                        contactNo: this.userDetails.contactNo
                    }
                    this.$http.post('http://localhost:3000/user/update', obj, { 
                        headers: {
                            'Content-type': 'application/json',
                            'id': this.userObj.id,
                            'userId': this.userObj.userId,
                            'Authorization': this.userObj.Authorization
                        }
                        }).then(response => {
                            this.clickPressed = false;
                            toastr.success(response.data.message);
                        })
                        .catch(e => {

                        });
                    }
            },
            validation(){
                if(this.emptyCheck(this.userDetails.contactNo)){
                    return true;
                }
                return false;
            },
            emptyCheck(value){
                if(value == "" && this.clickPressed){
                    return true;       
                }
                return false;
            }
        }
    }
</script>