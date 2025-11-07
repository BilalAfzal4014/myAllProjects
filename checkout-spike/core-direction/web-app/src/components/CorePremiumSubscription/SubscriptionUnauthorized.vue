<template>
  <div class="premium-unauth">
    <signin />
    <div class="premium-logo">
        <svg width="64" height="70" viewBox="0 0 64 70" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1.05412 33.0473C1.05412 28.1496 1.09504 23.2524 1.0132 18.3549C0.956699 17.7521 1.08242 17.1462 1.37417 16.6152C1.66592 16.0843 2.11036 15.6525 2.65027 15.3756C11.245 10.4779 19.7579 5.53986 28.3526 0.642396C30.2353 -0.418751 30.5218 -0.255477 30.5218 1.94837C30.5627 5.49906 30.4399 9.00888 30.5627 12.5596C30.6445 14.6821 29.9488 15.947 28.0661 16.9673C24.219 19.0079 20.4946 21.3342 16.6884 23.4156C16.1322 23.6679 15.6682 24.086 15.3603 24.6123C15.0524 25.1386 14.9158 25.7471 14.9694 26.3541C15.0103 30.9659 14.9285 35.6185 15.0103 40.2302C15.0683 40.9008 14.9139 41.5726 14.5688 42.151C14.2237 42.7294 13.7054 43.1853 13.0867 43.4544C9.85351 45.1685 6.66115 47.0459 3.46884 48.8824C1.13596 50.2292 1.0132 50.1476 1.0132 47.3724C1.05412 42.5565 1.05412 37.8223 1.05412 33.0473Z" fill="#690FAD"/>
            <path d="M62.9771 33.0069V47.9033C62.9771 49.9847 62.7316 50.1071 60.8898 49.0869C57.5747 47.2095 54.3005 45.2505 50.9445 43.4139C50.2897 43.1273 49.7428 42.6411 49.3825 42.0251C49.0222 41.4092 48.8669 40.6952 48.939 39.9857C49.0209 35.4148 48.939 30.8846 48.98 26.3137C49.0328 25.7119 48.9006 25.1083 48.6008 24.5833C48.301 24.0584 47.848 23.6369 47.302 23.3752C43.3729 21.2121 39.5257 18.8858 35.5967 16.7228C34.8884 16.3997 34.2988 15.8643 33.91 15.1913C33.5212 14.5183 33.3526 13.7412 33.4276 12.9681C33.5504 9.45825 33.4685 5.90757 33.4685 2.35694C33.4685 -0.377477 33.6731 -0.499945 36.006 0.806085C43.3729 5.0505 50.7808 9.25417 58.1068 13.5395C64.0413 17.0085 62.8543 15.0495 62.9362 21.9467C63.0178 25.6607 62.9771 29.3338 62.9771 33.0069Z" fill="#690FAD"/>
            <path d="M32.1173 69.9418C31.583 69.7164 31.0636 69.4574 30.5621 69.1664C21.8855 64.2281 13.209 59.2082 4.4914 54.2699C3.05898 53.4537 2.81341 52.9231 4.45048 52.0253C8.09302 50.0254 11.6949 47.944 15.2553 45.8218C16.4013 45.128 17.3017 45.4953 18.2839 46.0667C22.213 48.3522 26.2238 50.556 30.1119 52.9231C31.4625 53.7394 32.4857 53.7802 33.8363 52.964C37.6835 50.6376 41.6125 48.4746 45.5006 46.1891C46.8921 45.3729 48.0381 45.2913 49.4296 46.1483C52.622 48.1073 55.8961 49.9438 59.1294 51.8212C61.2985 53.0864 61.2985 53.2496 59.2112 54.4332C51.6806 58.7592 44.1227 63.099 36.5375 67.4523C35.0644 68.2685 33.5907 69.1256 32.1173 69.9418Z" fill="#690FAD"/>
            <rect x="28" y="18" width="7" height="24" rx="3.5" fill="#FFFFFA"/>
            <rect x="28" y="46" width="7" height="7" rx="3.5" fill="#FFFFFA"/>
        </svg>
    </div>
    <h5 class="premium-title">get Started with Core Premium</h5>
    <p class="premium-description">Unlock Exclusive Benefits: Elevate your fitness, health and happiness journey with Core Premium.</p>
    <button class="premium-button" @click="showSignInModal">Sign In</button>
    <span class="premium-link">or</span>
    <router-link class="premium-link" to="/signup">Create an account</router-link>
    
  </div>
</template>

<script>
import Emitter from "tiny-emitter/instance";
import Signin from "@/partials/modal/signin";
export default {
    name: "SubscriptionUnauthorized",
    components: {Signin},
    methods: {
        showSignInModal: function () {
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            Emitter.emit("sign_in_modal", "show sign in modal");
        },
    }
};
</script>

<style lang="scss" scoped>
    .premium-unauth{
        width: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        @media (max-width: 991px){
          width: 100%;
        }
        .premium-logo{
            margin-top: 60px;
            margin-bottom: 32px;
            @media (max-width: 991px){
                margin-top: 20px;
            }
        }
        .premium-title{
            color: #000;
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 24px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
            text-transform: capitalize;
            @media (max-width: 991px){
                font-size: 22px;
            }
        }
        .premium-description{
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 18px;
            font-style: normal;
            font-weight: 400;
            color: #06070E;
            line-height: 29px;
            margin: 15px 0px 24px 0px;
            @media (max-width: 991px){
                font-size: 14px;
                line-height: 24px;
            }
        }
        .premium-button{
            width: 194px;
            color: #FFFFFA;
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            padding: 12px 24px;
            background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
            border-radius: 30px;
            -webkit-border-radius: 30px;
            -moz-border-radius: 30px;
            -ms-border-radius: 30px;
            -o-border-radius: 30px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 6px;
            &:hover{
                background: linear-gradient(270.17deg, rgba(93, 13, 153, 0.65) 15.31%, #7812C6 99.74%);
            }
            &:active{
                opacity: 0.65;
                background: linear-gradient(272.52deg, #5D0D99 16.63%, rgba(120, 18, 198, 0.65) 49.42%, #5D0D99 83.46%);
            }
        }
        .premium-link{
            color: #690FAD;
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 20px;
            @media (max-width: 991px){
                font-size: 14px;
            }
        }
    }
</style>