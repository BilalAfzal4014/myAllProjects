<template>
  <div class="business-profile-map-box">
    <gmap-map
      :center="{lat:latitude, lng:longitude}"
      :zoom="18"
      :options="mapOptions"
      style="width: 100%; height:440px;"
    >
      <gmap-marker
        :position="{lat:latitude, lng:longitude}"
        :icon="{ url: require('../../public/assets/images/marker-icon.png')}"
        :option="MarkerOptions"
      />
    </gmap-map>
  </div>
</template>

<script>
import { Map } from "vue2-google-maps";
export default {
    name: "BusinessProfileMap",
    component: {"gmap-map": Map},
    props: {
        latitude: {
            type: Number,
            default: 0,
        },
        longitude: {
            type: Number,
            default: 0,
        },
    },
    data(){
        return {
            center:{lat:this.latitude, lng:this.longitude},
            mapOptions:{
                fullscreenControl:false,
                zoomControl: true,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false,
                disableDefaultUi: false,
            },
            map: null,
            infoContent: "",
            infoWindowPos: {
                lat: 0,
                lng: 0
            },
            infoWinOpen: false,
            currentMidx: null,
            fullscreenControl:false,
            infoOptions: {
                pixelOffset: {
                    width: 0,
                    height: -35
                }
            },
            MarkerOptions:{
                zIndex:999999,
                opacity:0.2
    
            },
        };
    }
};
</script>

<style scoped>

</style>