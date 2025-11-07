<template>
    <section id="restricted">
        <div class="error-code">
            <div class="custom-container">
                <div class="error-code-content-box">
                    <p class="title">Error 503</p>
                    <p class="subtitle">Service Unavailable</p>
                    <p class="desc">Hold tight! Our digital workout space is currently under a brief maintenance session.</p>
                    <a href="#" class="btn-restricted btn-core-premium-subscribe">Learn About Us</a>
                </div>
                <div class="error-img-box">
                    <img src="@/assets/images/service-unavailable.svg" alt="Service Unavailable" class="restricted-img">
                </div>
            </div>
        </div>
        <div class="tmc">
            <div class="custom-container">
                <div class="auth-box">
                    <p class="auth-title">What's Happening?</p>
                    <p class="auth-desc">Our server is taking a moment to recharge and fine-tune its gears. This occasional pit stop helps ensure a smoother and more efficient experience for you.</p>
                </div>
                <div class="tmc-inner-box">
                    <p class="auth-title">What Can You Do?</p>
                    <p class="auth-desc">While we're making some adjustments, here's what you can consider:</p>
                    <ul class="tmc-list">
                        <li class="tmc-item">
                            <strong>Stay Hydrated:</strong> Take a quick break, grab a drink, and come back in a few minutes. Our server will be back in action before you know it.
                        </li>
                        <li class="tmc-item">
                            <strong>Stretch Those Muscles:</strong> Use this time to stretch your legs or get some fresh air. A little movement always does wonders.
                        </li>
                        <li class="tmc-item">
                            <strong>Check Back Soon:</strong> Feel free to check back shortly. We're working diligently to get everything up and running smoothly.
                        </li>
                    </ul>
                    <p class="support-desc">Need Help? <a href="#" class="support">Contact Support</a></p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
 name: "ServiceUnavailablePage"
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