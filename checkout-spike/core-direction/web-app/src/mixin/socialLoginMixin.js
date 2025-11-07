import * as toastr from "toastr";

const socialLoginMixin = {
    data() {
        return {
            aws: {
                domain: process.env.VUE_APP_AWS_COGNITO_USER_POOL_DOMAIN,
                clientId: process.env.VUE_APP_AWS_COGNITO_CLIENT_ID,
                type: "token",
                scope: "openid profile",
                callback:
          window.location.protocol +
          "//" +
          window.location.host +
          "/aws-cognito-social",
            },
        };
    },

    created() {},
    methods: {
        validateRedirectResult(provider) {
            if (provider !== undefined) {
                // Go straight to the provider, skipping the hosted UI
                window.location.href =
          "https://" +
          this.aws.domain +
          "/authorize?identity_provider=" +
          provider +
          "&response_type=" +
          this.aws.type +
          "&client_id=" +
          this.aws.clientId +
          "&redirect_uri=" +
          this.aws.callback +
          "&state=" +
          this.generateVerification() +
          "&scope=" +
          this.aws.scope;
            } else {
                // Use the hosted UI
                window.location.href =
          "https://" +
          this.aws.domain +
          "/login?response_type=" +
          this.aws.type +
          "&client_id=" +
          this.aws.clientId +
          "&redirect_uri=" +
          this.aws.callback +
          "&state=" +
          this.generateVerification() +
          "&scope=" +
          this.aws.scope;
            }
        },
        socialLogin(provider) {
            if (provider.toLowerCase() === "facebook") {
                this.validateRedirectResult("Facebook");
                return;
            }
            if (provider.toLowerCase() === "google") {
                this.validateRedirectResult("Google");
                return;
            }

            if (provider.toLowerCase() === "apple") {
                this.validateRedirectResult("SignInWithApple");
                return;
            } else {
                toastr.error("Provider is not implemented");
            }
        },
        generateVerification() {
            let verification = "";
            let possible =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (let i = 0; i < 32; i++) {
                verification += possible.charAt(
                    Math.floor(Math.random() * possible.length)
                );
            }
            return verification;
        },
    },
};

export default socialLoginMixin;
