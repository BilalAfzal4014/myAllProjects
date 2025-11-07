<template>
   <section id="restricted">
        <div class="error-code">
            <div class="custom-container">
                <div class="error-code-content-box">
                    <p class="title">No Access</p>
                    <p class="subtitle">You're trying to access a private page.</p>
                    <p class="desc">If you think you should be able to access this private account, please contact your administrator and request access.</p>
                    <router-link to="/listing" class="btn-restricted btn-core-premium-subscribe">Go Back Home</router-link>
                </div>
                <div class="error-img-box">
                    <img src="@/assets/images/no-access.svg" alt="No Access" class="restricted-img">
                </div>
            </div>
        </div>
        <div class="tmc">
            <div class="custom-container">
                <div class="auth-box">
                    <p class="auth-title">What's Going On?</p>
                    <p class="auth-desc">You're trying to access a private page within the Core Direction Eco-System and you don't have the required permissions to see this page.</p>
                </div>
                <div class="tmc-inner-box">
                    <p class="auth-title">What Can You Do?</p>
                    <p class="auth-desc">Contact your Wellness Admin</p>
                    <ul class="tmc-list">
                        <li class="tmc-item">
                            Contact your organisations admin and ask them to share an invitation link.
                        </li>
                    </ul>
                    <p class="support-desc">Need Help? <a href="https://coredirection.com/contact-us" target="_blank" class="support">Contact Support</a></p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
 name: "ForbiddenPage",
};
</script>

<style lang="scss" scoped>
    #restricted{
    display: flex;
    flex-direction: column;
    row-gap: 60px;
    .error-code{
        position: relative;
        @media (min-width:768px) {
            padding-top: 175px;
        }
        @media (max-width:767px) {
            padding-top: 62px;
        }
        &::before{
            position: absolute;
            content: "";
            left: -343px;
            top: -240px;
            width: 686px;
            height: 686px;
            background: rgba(105, 15, 173, 0.30);
            filter: blur(500px);
            z-index: -1;
        }
        &::after{
            position: absolute;
            content: "";
            bottom: -234px;
            right: -456px;
            width: 686px;
            height: 686px;
            background: rgba(105, 15, 173, 0.30);
            filter: blur(500px);
            z-index: -1;
        }
        .custom-container{
            @media (min-width: 768px) {
                display: grid;
                grid-template-columns: 1fr 1fr;
                justify-content: space-between;
                align-items: center;
            }
            @media (max-width:767px) {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                flex-direction: column-reverse;
            }
            column-gap: clamp(32px, 4.444vw, 64px);
            row-gap: 32px;
            .title{
                color: #690FAD;
                font-family: "Montserrat", sans-serif;
                font-style: normal;
                font-weight: 700;
                line-height: 1.2;
                @media (min-width:768px) {
                    font-size: 96px;
                    margin-bottom: 24px;
                }
                @media (max-width:767px) {
                    font-size: 32px;
                    margin-bottom: 12px;
                }
            }
            .subtitle{
                color: #06070E;
                font-family: "Montserrat", sans-serif;
                font-style: normal;
                font-weight: 600;
                line-height: 1.2;
                @media (min-width:768px) {
                    font-size: 36px;
                }
                @media (max-width:767px) {
                    font-size: 20px;
                }
            }
            .desc{
                color: #06070E;
                font-family: "Montserrat", sans-serif;
                font-style: normal;
                font-weight: 400;
                line-height: 1.2;
                @media (min-width:768px) {
                    margin: 24px 0;
                    font-size: 20px;
                }
                @media (max-width:767px) {
                    font-size: 16px;
                    margin: 12px 0;
                }
            }
            .btn-restricted{
                min-width: 190px;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                -webkit-column-gap: 10px;
                column-gap: 10px;
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
                width: -webkit-max-content;
                width: -moz-max-content;
                width: max-content;
                cursor: pointer;
                &:hover{
                    background: linear-gradient(270.17deg, rgba(93, 13, 153, 0.65) 15.31%, #7812C6 99.74%);
                }
                &:active{
                    opacity: 0.65;
                    background: linear-gradient(272.52deg, #5D0D99 16.63%, rgba(120, 18, 198, 0.65) 49.42%, #5D0D99 83.46%);
                }
            }
            .error-code-content-box{
                width: 100%;
            }
            .error-img-box{
                width: 100%;
                .restricted-img{
                    margin-left: auto;
                    @media (max-width: 767px) {
                        margin-right: auto;
                    }
                }
            }
        }
    }
    .tmc{
        position: relative;
        @media (min-width:768px) {
            padding-bottom: 124px;
        }
        @media (max-width:767px) {
            padding-bottom: 62px;
        }
        &::before{
            position: absolute;
            content: "";
            left: -38px;
            bottom: -17px;
            width: 381px;
            height: 381px;
            background: rgba(105, 15, 173, 0.30);
            filter: blur(500px);
            z-index: -1;
        }
        .auth-title{
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 1.25;
            @media (min-width:768px) {
                padding: 8px 4px;
            }
            margin-bottom: 4px;
        }
        .auth-desc{
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: 1.25;
            @media (min-width:768px) {
                padding: 4px;
            }
        }
        .tmc-list{
            list-style: inside;
            list-style-type: decimal;
            margin: 4px 0 8px;
            @media (min-width:768px) {
                padding: 4px;
            }
            .tmc-item{
                color: #06070E;
                font-family: "Montserrat", sans-serif;
                font-size: 16px;
                font-style: normal;
                font-weight: 400;
                line-height: 1.5;
                strong{
                    font-weight: 500;
                }
            }
        }
        .support-desc{
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 1.25;
            @media (min-width:768px) {
                padding: 4px;
            }
            a{
                color: #690FAD;
                font-weight: 600;
                text-decoration-line: underline;
            }
        }
        .auth-box{
            max-width: 715px;
            margin-bottom: 24px;
        }
    }
}
</style>