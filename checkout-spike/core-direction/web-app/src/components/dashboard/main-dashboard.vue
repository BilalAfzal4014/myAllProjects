<template>
  <div>
    <Menu />
    <router-view />
    <footer-component />
  </div>
</template>

<script>
import FooterComponent from "../../partials/footer";
import Menu from "./menu";

export default {
    components:{
        FooterComponent,
        Menu
    }
};
</script>