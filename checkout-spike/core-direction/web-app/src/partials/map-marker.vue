<template>
  <div class="d-none" />
</template>

<script>
export default {
    name: "MapMarker",
    props: {
        lat:{type:Number,required:true},
        lng: {type:Number,required:true},
    },
    data: ()=> ({
        marker:null,
    }),
    mounted(){
        this.$parent.getMap(map=>{
            this.marker = new window.google.maps.Marker({
          
                position:{lat:this.lat,lng:this.lng},
                map:map
            });
        });
    },
    beforeUnmount: function(){
        this.marker.setMap(null);
        window.google.maps.event.clearInstanceListeners(this.marker);
    },
    render(){
        return null;
    }
};
</script>

<style scoped>

</style>