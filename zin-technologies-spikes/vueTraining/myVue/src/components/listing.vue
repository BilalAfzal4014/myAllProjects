<template>
  
<div class="container">
  <br/>            
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Password</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="user in upperCase(usersArray)">
        <td>{{user.name | appendL}}</td>
        <td>{{user.password}}</td>
      </tr>
    </tbody>
  </table>
</div>

</template>

<script>
export default {
  name: 'userListing',
  props: ['usersArray'],
  methods:{
    upperCase: function(values){
      if(values.length > 0){
        //alert("hello");
        //console.log(values[0].name); // full array a rahi hai
        //for(var i = 0 ; i < values.length; i++){
          //  values[i].name = values[i].name.toUpperCase();
        //}
        //return values;
        return values.filter(function (value) {
          return value.name = value.name.toUpperCase(); // can also return true and false, array ka object pass ho raha hai 
        });  
      
      }
    }
  },
  filters:{
    appendL: function(value){
      return value + 'L';
    }
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
