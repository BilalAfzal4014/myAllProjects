<template>
   <div class="main-container">

      <div class="listing-container">
        <ul v-for="(chat, index) in chatListing">
          <li v-on:click="makeActive(chat.id, index)" v-bind:class="applyClass(chat.active)">
            {{chat.name}}
          </li>
        </ul>
      </div>

      <div class="chat-container">
        <div class="message" v-for="message in messageList">
          <span v-bind:class="messageClass(message.sender_id)">
            {{message.sender_id}} -- {{message.message}}
          </span>
        </div>
      </div>

      <div class="msg-container">
        <span v-show="typingList.length > 0">{{typingList}} is/are typing...</span>
        <input type="text" v-model="messageValue" placeholder="Type here" v-on:focus="typing('start')" v-on:blur="typing('stop')"></input>
        <button v-on:click="sendMessage()">send</button>
      </div>        

   </div>
</template>

<script>
export default{
  mounted(){
    this.events();
    this.userObj = this.getUserMixin();
    this.listenForChat();
    this.listenForTyping();
    this.getChatListing();
  },
   beforeDestroy(){
      this.socket().off('chat');
      this.socket().off('typing');
  },
  data(){
    return {
      userObj:{},
      chatListing: [],
      messageList:[],
      currentIndex: 0,
      activeChatId: -1,
      messageValue: '',
      typingList: [],
    };
  },
  methods:{
    getChatListing(){
      this.$http.get('http://localhost:3000/chat/getchatlisting/' + this.userObj.userId, { 
      headers: {
          'Content-type': 'application/json',
          'id': this.userObj.id,
          'userId': this.userObj.userId,
          'Authorization': this.userObj.Authorization
      }
      }).then(response => {
        this.chatListing = response.data.data;
        this.activeChatId = this.chatListing[0].id;
        this.getMessages(this.chatListing[0].id);
      })
      .catch(e => {

      }); 
    },
    applyClass(active){
      if(active == 1)
        return 'active';
      return '';
    },
    makeActive(chatId, index){
      this.activeChatId = chatId;
      this.chatListing[this.currentIndex].active = 0;
      this.chatListing[index].active = 1;
      this.currentIndex = index;
      this.typingList = [];
      this.getMessages(chatId); 
    },
    getMessages(chatId){
      this.$http.get('http://localhost:3000/chat/getmessaging/' + chatId, { 
      headers: {
          'Content-type': 'application/json',
          'id': this.userObj.id,
          'userId': this.userObj.userId,
          'Authorization': this.userObj.Authorization
      }
      }).then(response => {
        this.messageList = response.data.data;
        //$('.chat-container').animate({scrollTop: parseInt($('.chat-container').height())});
      })
      .catch(e => {

      }); 
    },
    messageClass(id){
      if(id == this.userObj.userId)
        return 'my-msg';
      else
        return 'rcv-msg';

    },
    sendMessage(){
      if(this.messageValue.length > 0){
        this.socket().emit('chat', {
          chat_id: this.activeChatId,
          sender_id: this.userObj.userId,
          message: this.messageValue
        });
        this.messageValue = '';  
      }
    },
    listenForChat(){
       var vueScope = this;
       this.socket().on('chat', function(data){
          if(vueScope.activeChatId == data.chat_id ){
            vueScope.messageList.push(data);
            //$('.chat-container').animate({scrollTop: parseInt($('.chat-container').height())});
          }
      });
    },
    events(){
      var vueScope = this;
      $(document).keypress(function(e){
          if(e.which == 13){
            vueScope.sendMessage();
            vueScope.typing('stop');
          }
      });
    },
    typing(type){
      if(type == "start"){
        this.socket().emit('typing', {
          chat_id: this.activeChatId,
          sender_id: this.userObj.userId,
          type: 'start'
        });
      }
      else{
        this.socket().emit('typing', {
          chat_id: this.activeChatId,
          sender_id: this.userObj.userId,
          type: 'stop'
        });
      }
    },
    listenForTyping(){
      var vueScope = this;
       this.socket().on('typing', function(data){
        if(vueScope.activeChatId == data.chat_id){
          if(data.type == 'start' ){
            vueScope.typingList.push(data.typing_id);
          }
          else{
            var index = vueScope.typingList.indexOf(data.typing_id);    
            if (index > -1)       
              vueScope.typingList.splice(index, 1);
          }
        }
      }); 
    }
  }
}
    
</script>

<style scoped>
    .listing-container{
        width: 250px;
        display: inline-block;
        background-color: #f5f5f5;
        height: 86vh;
        border: 1px solid #ddd;
        overflow-y:auto;
      }

      .listing-container ul{
        list-style-type: none;
        padding-left: 0px;
        margin: 0px;
      }

      .listing-container ul li{
        padding: 10px 0px 10px 28px;
        border: 1px solid #ddd;
        cursor:pointer;
      }

      .listing-container ul li:hover{
        background-color: #1111;
      }

      .chat-container{
        width: 80%;
        display: inline-block;
        position: absolute;
        top: 72px;
        background: #f5f5f5;
        height: 74vh;
        overflow-y:auto;
      }

      .msg-container{
        width: 80%;
        display: inline-block;
        position: relative;
        top: -30px;
      }

      .msg-container input{
        width: 94%;
        height:48px
      }

      .msg-container{
          display: inline-block;
          position: relative;
          left: 254px;
          top: -80px;
      }

      .active{
          background-color: #1111;
      }
      
      .notification{
        background-color: red;
      }

      .message{
        /*position: relative;*/
        display: block;
        padding: 15px 15px;
        background: #1111;
        border: 1px solid #ddd;
        overflow:hidden;
      }

      .rcv-msg{
        border: 1px solid darkgreen;
        border-radius: 9px;
        padding: 3px 10px;
      }

      .my-msg{
        /*position: absolute;*/
        border: 1px solid darkgreen;
        border-radius: 9px;
        padding: 3px 10px;
        float: right;
      }
</style>