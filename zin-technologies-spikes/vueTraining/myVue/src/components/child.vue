<template>
	<div class="container">
  		<h3 v-localDirective:red.a.b="'brown'">This is first Child route of register route(parent)</h3>
	</div>
</template>

<script>
	export default{
		directives:{
			localDirective: {
				bind(el,binding,vnode){
					$(el).css({color: binding.arg})
				}
			}
		}
	}
</script>
