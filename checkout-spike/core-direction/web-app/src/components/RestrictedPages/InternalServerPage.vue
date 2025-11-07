<template>
   <section id="restricted">
        <div class="error-code">
            <div class="custom-container">
                <div class="error-code-content-box">
                    <p class="title">Error 401</p>
                    <p class="subtitle">Unauthorized Access</p>
                    <p class="desc">Hold on there, fitness enthusiast! This area is off-limits without proper credentials.</p>
                    <button class="btn-restricted btn-core-premium-subscribe">Log in</button>
                </div>
                <div class="error-img-box">
                    <img src="@/assets/images/internal-server.svg" alt="Internal Server" class="restricted-img">
                </div>
            </div>
        </div>
        <div class="tmc">
            <div class="custom-container">
                <div class="auth-box">
                    <p class="auth-title">Authentication Required</p>
                    <p class="auth-desc">You've reached a zone reserved for our valued members. It's like a hidden chamber of exclusive fitness insights and resources.</p>
                </div>
                <div class="tmc-inner-box">
                    <p class="auth-title">Unlock the Door</p>
                    <p class="auth-desc">Here's how you can get inside:</p>
                    <ul class="tmc-list">
                        <li class="tmc-item">
                            <strong>Member Login:</strong> If you're already part of our fitness community, go ahead and log in using your authorized credentials.
                        </li>
                        <li class="tmc-item">
                            <strong>New to Us? Join Up:</strong> Ready to embrace a healthier lifestyle with us? Sign up to gain access to our curated fitness realm.
                        </li>
                        <li class="tmc-item">
                            <strong>Need Assistance?</strong> Reset Password: If the combination to the door is escaping you, request a password reset. We'll help you reset and regain entry.
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
 name: "InternalServerPage"
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