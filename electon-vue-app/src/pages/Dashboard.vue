<template>
  <p>
    <strong>Current route path:</strong> {{ $route.fullPath }}
  </p>
  <nav>
    <RouterLink to="/dashboard/register">Register Images</RouterLink>
    <br/>
    <RouterLink to="/dashboard/unregister">Un-Register Images</RouterLink>
  </nav>
  <div>
    <RouterView></RouterView>
  </div>
</template>

<script setup>
console.log('👋 This message is being logged by "Dashboard.vue", included via Vite');
</script>
