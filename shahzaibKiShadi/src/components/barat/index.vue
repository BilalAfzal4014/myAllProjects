<template>
  <div class="outer-content-div">
    <div class="inner-content-div">
      <h1>Barat</h1>
      <p>Programme on In Sha Allah <b>30th, October 2020</b></p>
      <p><b>Address:</b> 109-R block Model Town Extension Lahore</p>
      <ul class="outer-ul">
        <b>Timings</b>
        <li class="outer-li">
          <ul>
            <li>Sehra Bandi: <b>7:00PM</b></li>
            <li>Departure of barat: <b>7:30PM</b></li>
          </ul>
        </li>
      </ul>
      <div class="menu-button-main-div">
        <div class="menu-button-inner-div">
          <button class="menu-button" v-on:click="$router.push('/')">Goto Menu</button>
        </div>
        <div class="menu-button-inner-div">
          <button class="location-button" v-on:click="fetchLocationAndRedirect()"><b>Barat address from your location</b>
          </button>
        </div>
      </div>
      <p><b>Note:</b> turn on your locations from settings, if you can't get the location</p>
    </div>
  </div>
</template>

<script>
import baratWalimaMixin from '../../mixins/barat-walima/index';

export default {
  name: "Barat",
  mixins: [baratWalimaMixin],
  data() {
    return {
      fixedLocation: {
        lat: 31.474520,
        longs: 74.332176,
      },
      callbackGeoLocation: true,
    };
  }
}
</script>
