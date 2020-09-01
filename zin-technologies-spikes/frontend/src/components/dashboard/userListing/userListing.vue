<template>
	<div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                All Users
            </div>
    
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-12 col-lg-12">
                        <div class="table-responsive">
                                                
                            <table id="mytable" class="table table-bordred table-striped table-hover">
                                <thead>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    
                                </thead> 
                                <tbody>
                                    <tr v-for="user in userListing" v-if="userObj.userId != user.id">  
                                        <td>{{user.email}}</td>
                                        <td>{{user.contactNo}}</td>
                                        <td><input type="checkbox" v-bind:value="user.id" v-model="chatUsers"></td>
                                                            
                                    </tr>       
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="10"></td>
                                    </tr>
                                </tfoot>                     
                            </table>
                        </div>  
                        <button type="button" v-on:click="selectForChatRoom()">Make Room</button>                  
                    </div>                
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        created(){
            this.socket().emit('listing');
        },
        mounted(){
            this.userObj = this.getUserMixin();
            this.getUserListing();
            
            this.socket().on('listing', function(data){
                alert(data);
            });
        },
        beforeDestroy(){
            this.socket().off('listing');
            //this.socket().removeListener('listing');
        },
        data(){
            return{
                userObj:{},
                userListing:[],
                chatUsers: [],
                loading: true,
            }
        },
        methods:{
            getUserListing(){
                this.$http.get('http://localhost:3000/user', { 
                headers: {
                    'Content-type': 'application/json',
                    'id': this.userObj.id,
                    'userId': this.userObj.userId,
                    'Authorization': this.userObj.Authorization
                }
                }).then(response => {
                    this.userListing = response.data.data;
                })
                .catch(e => {

                });
            },
            selectForChatRoom(){
                if(this.chatUsers.length > 0){
                    this.chatUsers.push(this.userObj.userId);
                    var obj = {
                        userIdsForChatRoom: this.chatUsers,
                    }; 
                    this.$http.post('http://localhost:3000/chat/makechatroom', obj, { 
                    headers: {
                        'Content-type': 'application/json',
                        'id': this.userObj.id,
                        'userId': this.userObj.userId,
                        'Authorization': this.userObj.Authorization
                    }
                    }).then(response => {
                        this.chatUsers = [];
                        if(response.data.status)
                            toastr.success(response.data.message);
                        else
                            toastr.error(response.data.message);
                    })
                    .catch(e => {
                        this.chatUsers = [];
                    });    
                }
                else{
                    toastr.error("Please select user(s)");
                }
            }
        }
    }
</script>