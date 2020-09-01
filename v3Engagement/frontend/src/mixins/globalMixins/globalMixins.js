const mixin = {
    data() {
        return {
            authHeaders: {
                headers: {
                    'Authorization': '',
                }
            }
        }
    },
    mounted() {
        this.authHeaders.headers.Authorization = this.getToken();
        // this.authHeaders.headers.Content-Type = 'multipart/form-data';
        $(".mCustomScrollbar").mCustomScrollbar({
            mouseWheelPixels: 100
        });

        $(".bs-container").css({
            display: "none"
        });
    },
    methods: {
        storeToken(token) {
            this.$store.dispatch('setStoreTokenAction', token);
        },
        getToken() {
            return this.$store.getters.getStoreTokenGetters;
        },
        removeToken() {
            this.$store.dispatch('removeStoreTokenAction');
        },
        setCompanyImage(path) {
            console.log('path', path);
        },
        logOut() {

            alert("asd");
            //this.removeToken();
            //this.$router.go("/login");
        }
    }
};

export default mixin;