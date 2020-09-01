<template>
	<div>
		<topMenu></topMenu>
		<router-view/>
	</div>
</template>

<script>
	import topMenu from '../partials/menu';
	export default{
		components:{
			topMenu,
		}
	}
</script>