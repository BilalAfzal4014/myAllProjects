<template>
	<h1>This is a parameter component having id : {{id}}</h1>
</template>

<script>
	export default{
		mounted(){
			if(this.$route.params.id)
				this.id = this.$route.params.id;
			else
				this.id = '';
		},
		data(){
			return{
				id: ''
			}
		}
	}
</script>