<template>
  
<div class="container">
  <br/>
  <form action="">
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" v-model="user.name">
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="pwd" v-model="user.password">
    </div>
    <button type="button" class="btn btn-default" v-on:click="addUser">Add</button>
  </form>
  <br/>
</div>

</template>

<script>
export default {
  name: 'addusers',
  data(){
      return{
        user:{
          name:'',
          password:''
        }
      }
  },
  methods: {
    addUser (event) {
      this.$emit('addUserClicked', this.user);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
