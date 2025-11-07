<template>
  <div>
    <Signin />
    <ForbiddenPage />
  </div>
</template>

<script>
import Emitter from "tiny-emitter/instance";
import ForbiddenPage from "@/components/RestrictedPages/ForbiddenPage";
import Signin from "@/partials/modal/signin";

export default {
  name: "NoAccessPage",
  components: {
    Signin,
    ForbiddenPage
  },
  created() {
    const token = localStorage.getItem("token");
    if (!token) {
      Emitter.emit("sign_in_modal", "show sign in modal");
    }
  }

};
</script>

<style>

</style>