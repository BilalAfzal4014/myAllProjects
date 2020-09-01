<template>
    <div id="wrapper">
        <div class="login_layer">
            <div class="d-table">
                <div class="v-middle">

                    <form v-bind:class="classActive == true ? 'login_form active': 'login_form'"
                          v-on:submit.prevent="login">
                        <fieldset>
                            <div class="hermis_logo">
                                <img src="../../assets/images/hermisLogo.png">
                            </div>
                            <h2>
                                <div class="icon"><i class="fas fa-lock"></i></div>
                                <span>Member Login</span>
                            </h2>
                            <div class="fields">
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-user"></i>
                                        <input placeholder="Email" type="email" v-model="user.email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-unlock-alt"></i>
                                        <input placeholder="Password" type="password" v-model="user.password">
                                    </div>
                                </div>
                                <div class="row b_margin">
                                    <div class="col">
                                        <router-link to="/forgot-password">Forgot Password?</router-link>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="submit" v-on:click="login" value="Login">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                user: {
                    email: "",
                    password: ""
                },
                classActive: false,
            };
        },
        mounted() {
            this.classActive = true;
        },
        methods: {
            login($event) {
                $event.preventDefault();
                this.api("post",
                    this.constants.getUrl("login"),
                    this.user
                ).then((response) => {
                    if (response.data.meta.code == 200) {
                        this.fetchUserInfo(response.data.data.token);
                    } else {
                        this.$swal('Failed', 'These credentials do not match our records.', 'error');
                    }
                }).catch((error) => {
                    if (error[0].response.data.meta.code == 422) {
                        this.$swal('Failed', error[0].response.data.error[0], 'error');
                    } else {
                        this.$swal('Failed', 'These credentials do not match our records.', 'error');

                    }
                });
            },
            fetchUserInfo(token) {

                let payload = {
                    resource: "user",
                    action: "get",
                    data: {}
                };

                let authHeaders = {
                    headers: {
                        'Authorization': "Bearer " + token,
                    }
                };

                this.api("post",
                    this.constants.getUrl("backendApiUrl"),
                    payload,
                    authHeaders
                ).then((response) => {
                    if (response.status == 200) {
                        this.storeToken(token);
                        this.$store.dispatch("saveUserInfoAction", response.data);

                        if (response.data.is_admin == 1) {
                            this.$router.go("/dashboard/company-listing");
                        } else {
                            this.$router.go("/dashboard/company-userListing");
                        }

                    }
                }).catch((error) => {
                });

            }
        }
    }
</script>

<style scoped>
    .hermis_logo {
        text-align: center;
        padding: 8px 0px;
    }
</style>