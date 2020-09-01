<template>
	<span style='margin: auto; display: block; text-align: center;'>Path doesn't exist</span>
</template>

<script>
import {bus} from '../main'
	export default{
		created(){
			//this.$emit('hideShowAppAll', false);
			bus.$emit('hideShowAppAll', false);
		},
		destroyed(){
			//this.$emit('hideShowAppAll', true);
			bus.$emit('hideShowAppAll', true);
		}
	}
</script>
