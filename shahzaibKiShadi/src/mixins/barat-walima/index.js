let mixinObj = {
  mounted() {

  },
  methods: {
    fetchLocationAndRedirect() {
      this.callbackGeoLocation = true;
      this.getCurrentLocation(function (currentLocation) {
        if (JSON.stringify(currentLocation) !== "{}") {
          let url = this.makeUrl(currentLocation);
          this.redirectToUrl(url);
        }
      }.bind(this));
    },
    getCurrentLocation(cb) {
      if (navigator.geolocation) {
        //navigator.geolocation.getCurrentPosition(function (position) {
        navigator.geolocation.watchPosition(function (position) {
          if (this.callbackGeoLocation) {
            cb({
              lat: position.coords.latitude,
              longs: position.coords.longitude
            });
          }

          this.callbackGeoLocation = false;
        }.bind(this));
      } else {
        cb({});
        alert("Geolocation is not supported by this browser.");
      }
    },
    makeUrl(currentLocation) {
      let url = `https://www.google.es/maps/dir/'${currentLocation.lat},${currentLocation.longs}'/'${this.fixedLocation.lat},${this.fixedLocation.longs}'`;
      return url;
    },
    redirectToUrl(url) {
      let isOPened = window.open(url);
      if (isOPened === null) {
        window.location.href = url;
      }
    }
  }
};

export default mixinObj;
