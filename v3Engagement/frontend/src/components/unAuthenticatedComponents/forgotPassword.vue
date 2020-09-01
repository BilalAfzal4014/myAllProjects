<template>
    <div id="wrapper">
        <div class="login_layer">
            <div class="d-table">
                <div class="v-middle">
                    <form v-bind:class="classActive == true ? 'login_form active': 'login_form'">
                        <fieldset>
                            <h2>
                                <div class="icon"><i class="fas fa-key"></i></div>
                                <span>Reset Password</span>
                            </h2>
                            <div class="fields">
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-user"></i>
                                        <input placeholder="Email" type="email" v-model="user.email">
                                    </div>
                                    <p class="error" v-if="error.email">{{ error.email }}</p>
                                </div>
                                <div class="row">
                                    <input type="button" v-on:click="forgotPassword" value="SEND RESET LINK">
                                </div>
                                <div class="row border">
                                    <div class="col fluid">
                                        <p>Back to
                                            <router-link to="/login"> Login Page?</router-link>
                                        </p>
                                    </div>
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
        name: "forgot-password",
        data() {
            return {
                user: {
                    email: "",
                },
                error: {
                    email: ''

                },
                reg: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/,
                classActive: false,
            };
        },
        mounted() {
            this.classActive = true;
        },
        methods: {
            forgotPassword() {
                let email = this.user.email;
                if (email == "") {
                    this.error.email = 'Email Field is required';
                }
                if (email != "") {
                    (this.reg.test(email)) ? this.error.email = '' : this.error.email = 'Email address not valid';
                }
                if (email != "" && this.reg.test(email)) {
                    this.api("post",
                        this.constants.getUrl("forgot"),
                        this.user
                    ).then((response) => {
                        console.log('response', response);
                        if (response.data.meta.code == 200) {
                            this.$swal({
                                position: 'top-end',
                                type: 'success',
                                title: 'New password has been send to your email please check and login then',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            this.$swal('Failed', 'Email not send.', 'error');
                        }
                    }).catch((error) => {
                        console.log('error', error[0].response.data);
                        if (error[0].response.data.meta.code == 422) {
                            this.$swal('Failed', error[0].response.data.error, 'error');
                        } else {
                            this.$swal('Failed', 'Email not send', 'error');

                        }
                    });
                }
                console.log('email', email);
            }
        },
        watch: {
            user: {
                handler(user) {
                    if (user.email != "") {
                        (this.reg.test(user.email)) ? this.error.email = '' : this.error.email = 'Email address not valid';
                    }
                },
                deep: true
            }

        }
    }
</script>

<style>

</style>