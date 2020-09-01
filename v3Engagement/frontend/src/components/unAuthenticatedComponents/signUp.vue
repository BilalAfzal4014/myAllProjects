<template>
    <div id="wrapper">
        <div class="login_layer">
            <div class="d-table">
                <div class="v-middle">
                    <form v-bind:class="classActive == true ? 'login_form active': 'login_form'">
                        <fieldset>
                            <h2>
                                <div class="icon"><i class="fas fa-user-plus"></i></div>
                                <span>Member Sign up</span>
                            </h2>
                            <div class="fields">
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-user"></i>
                                        <input type="text" placeholder="Name" v-model="user.name">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-user"></i>
                                        <input type="email" placeholder="Email" v-model="user.email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-unlock-alt"></i>
                                        <input type="password" placeholder="Password" v-model="user.password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input_holder">
                                        <i class="fas fa-unlock-alt"></i>
                                        <input type="password" placeholder="Confirm Password" v-model="user.confirmPassword">
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="button" value="SIGN IN" v-on:click="signUp">
                                </div>
                                <div class="row border">
                                    <div class="col fluid">
                                        <p>Back to
                                            <router-link to="/login">Login Page?</router-link>
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
        name: "sign-up",
        data() {
            return {
                user: {
                    name: "",
                    email: "",
                    password: "",
                    confirmPassword: ""
                },
                classActive: false,
            };
        },
        mounted() {
            this.classActive = true;
        },
        methods: {
            signUp() {
                alert("in signup");
            }
        }
    }
</script>

<style>

</style>