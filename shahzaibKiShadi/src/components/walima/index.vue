<template>
  <div class="outer-content-div">
    <div class="inner-content-div">
      <h1>Walima</h1>
      <p>Programme on In Sha Allah <b>31st, October 2020</b></p>
      <p><b>Address:</b> Nice Marque</p>
      <ul class="outer-ul">
        <b>Timings</b>
        <li class="outer-li">
          <ul>
            <li>Reception: <b>7:00PM</b></li>
          </ul>
        </li>
      </ul>
      <div class="menu-button-main-div">
        <div class="menu-button-inner-div">
          <button class="menu-button" v-on:click="$router.push('/')">Goto Menu</button>
        </div>
        <div class="menu-button-inner-div">
          <button class="location-button" v-on:click="fetchLocationAndRedirect()"><b>Walima address from your location</b>
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
  name: "Walima",
  mixins: [baratWalimaMixin],
  data() {
    return {
      fixedLocation: {
        lat: 31.427904,
        longs: 74.305687,
      },
      callbackGeoLocation: true,
    };
  },

}
</script>
