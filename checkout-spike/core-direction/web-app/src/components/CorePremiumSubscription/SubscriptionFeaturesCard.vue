<template>
  <div class="feature-card">
    <div class="feature-icon" v-html="svg" />
    <p class="feature-title">
      {{ featureTitle }}
    </p>
    <p class="feature-desc">
      {{ featureDescription }}
    </p>
  </div>
</template>

<script>
export default {
  name: "SubscriptionFeaturesCard",
  props: {
    svg: {
      type : String,
      default: "",
    },
    featureTitle: {
      type : String,
      default: "",
    },
    featureDescription: {
      type : String,
      default: "",
    }
  },
 
};
</script>

<style lang="scss" scoped>
  .feature-card{
        position: relative;
        z-index: 1;
        @media (min-width:768px) {
            min-height: 308px;
        }
        @media (max-width:767px) {
            min-height: 256px;
        }
        padding: 32px;
        border-radius: 12px;
        background: rgba(255, 255, 250, 0.10);
        box-shadow: 0px 4px 6px 0px rgba(16, 24, 40, 0.03), 0px 12px 16px 0px rgba(16, 24, 40, 0.08);
        .feature-icon{
            @media (min-width:768px) {
                width: 48px;
                height: 48px;
                margin-bottom: 32px;
            }
            @media (max-width:767px) {
                width: 36px;
                height: 36px;
                margin-bottom: 16px;
            }
            border-radius: 10px;
            background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            img,svg{
                @media (min-width:768px) {
                    height: 24px;
                }
                @media (max-width:767px) {
                    height: 18px;
                }
                object-fit: contain;
                object-position: center;
            }
        }
        .feature-title{
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-style: normal;
            font-weight: 600;
            line-height: 30px;
            margin-bottom: 8px;
            @media (min-width:768px) {
                font-size: 20px;
            }
            @media (max-width:767px) {
                font-size: 16px;
            }
        }
        .feature-desc{
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
            opacity: 0.6499999761581421;
            @media (min-width:768px) {
                font-size: 14px;
            }
            @media (max-width:767px) {
                font-size: 12px;
            }
        }
      }
</style>