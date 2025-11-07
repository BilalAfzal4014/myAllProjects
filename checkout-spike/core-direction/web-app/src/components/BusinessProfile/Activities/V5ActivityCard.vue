<template>
  <div>
    <ConfirmBooking v-if="isConfirm" @clicked="closeConfirmModal" />
    <SocialSharingOnSignup :show-modal="showSharingModal" @setShowActivityModal="hideSocialSharingModal" />
    <div class="activity-card">
      <div class="card-header">
        <img alt="activity img" class="activity-img" height="180" src="@/assets/images/activity-card-img.png"
             width="400"
        >
      </div>
      <div class="card-body">
        <div class="activity-info-box">
          <div class="logo-box">
            <img :alt="activityName" :src="activitiesBaseUrl+facilityImage" class="logo" height="42" width="42">
          </div>
          <p class="business-name">
            {{ activityName }}
          </p>
        </div>
        <div class="activity-time-box">
          <p class="activity-type-info">
            <span class="activity-date">{{ activityStartDate }}</span>|<span class="activity-time">{{
              activityStartTime
            }} - {{
              activityEndTime
            }}</span>
          </p>
          <p class="tag">
            {{ activityFree ? "Free" : "Paid" }}
          </p>
          <p class="tag">
            Virtual
          </p>
        </div>
        <ul class="activity-info-list">
          <li class="activity-info-item">
            <span class="activity-info-item-icon-box">
              <svg fill="none" height="16" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M0.5 12.6214C0.807548 11.7908 1.4463 11.4823 2.29797 11.4823C5.42077 11.4823 8.54357 11.4823 11.6664 11.4823C11.9739 11.4823 12.1395 11.3874 12.2578 11.1026C13.0148 9.25162 13.7955 7.42437 14.5999 5.57338C14.6709 5.38353 14.6709 5.26488 14.5289 5.1225C14.1031 4.67162 13.7009 4.197 13.2751 3.74612C13.1331 3.60374 13.1095 3.46136 13.2987 3.36643C13.4643 3.27151 13.559 2.96301 13.8192 3.24778C14.3397 3.84105 14.8838 4.43431 15.4042 5.02758C15.5225 5.16996 15.5225 5.28861 15.4516 5.431C14.6472 7.32944 13.8429 9.22789 13.0385 11.1026C13.0148 11.1738 12.9912 11.2213 12.9675 11.2925C12.8966 11.4348 12.8965 11.506 13.1095 11.5535C13.7482 11.6484 14.2214 12.1468 14.3397 12.74C14.4579 13.4045 14.1741 14.0452 13.6063 14.3774C13.346 14.5198 13.0858 14.591 12.8019 14.591C9.22963 14.591 5.681 14.591 2.10871 14.591C1.35167 14.591 0.85486 14.1876 0.57097 13.4994C0.499997 13.1909 0.5 12.9061 0.5 12.6214ZM7.408 13.8316C7.64458 13.8316 7.8575 13.8316 8.09407 13.8316C9.65547 13.8316 11.2169 13.8316 12.7783 13.8316C13.3224 13.8316 13.6536 13.4757 13.6063 12.9773C13.5826 12.5264 13.2278 12.2417 12.6836 12.2417C9.15866 12.2417 5.63368 12.2417 2.10871 12.2417C2.03774 12.2417 1.96677 12.2417 1.91945 12.2417C1.51727 12.2654 1.20972 12.6214 1.18607 13.0248C1.18607 13.4994 1.49362 13.8316 2.01408 13.8316C3.81205 13.8316 5.61003 13.8316 7.408 13.8316Z"
                  fill="black"
                />
                <path
                  d="M9.18258 0.493164C9.67938 0.683009 10.0342 0.991507 10.1525 1.51358C10.3181 2.20177 9.91596 2.88996 9.22989 3.10353C8.52016 3.34084 7.78678 3.00861 7.50289 2.34415C7.219 1.70342 7.47923 0.967776 8.11799 0.611817C8.18896 0.564356 8.28358 0.516895 8.37821 0.493164C8.63845 0.493164 8.92234 0.493164 9.18258 0.493164ZM9.4428 1.82208C9.4428 1.46612 9.15892 1.18135 8.7804 1.18135C8.40187 1.18135 8.09433 1.48985 8.11799 1.84581C8.14164 2.17804 8.44919 2.4628 8.80405 2.4628C9.15892 2.4628 9.46646 2.17804 9.4428 1.82208Z"
                  fill="black"
                />
                <path
                  d="M5.49126 6.35329C5.34931 6.49568 5.20737 6.61433 5.04176 6.66179C4.28472 6.94656 3.57499 6.37702 3.69328 5.57018C3.76425 5.09557 3.88254 4.66842 4.0718 4.24127C4.71056 2.91235 5.86978 2.39028 7.28923 2.76997C7.69141 2.88862 8.06992 3.03101 8.40113 3.24458C9.0872 3.67173 9.58401 3.57681 10.2228 3.05474C10.3411 2.95982 10.4357 2.81743 10.554 2.72251C10.9561 2.36655 11.5949 2.39028 11.9734 2.76997C12.3283 3.14966 12.3046 3.67173 11.9025 4.09888C11.2164 4.83453 10.3884 5.33288 9.37109 5.45153C9.22915 5.47526 9.13451 5.52272 9.11086 5.6651C9.11086 5.68884 9.0872 5.71257 9.0872 5.71257C8.96891 5.99733 8.75599 6.30583 8.75599 6.5906C8.75599 6.87537 9.13451 7.04148 9.32377 7.25505C10.0572 8.10936 10.6486 9.05858 10.9798 10.1502C11.0981 10.5536 11.0508 10.9096 10.7196 11.1943C10.3884 11.4554 10.0335 11.5266 9.63133 11.3604C9.32378 11.2418 9.18183 10.9808 9.06355 10.6723C8.73234 9.69931 8.16455 8.84501 7.43117 8.15682C7.17094 7.91951 7.17094 7.91951 7.029 8.25174C6.36659 9.7705 5.01811 10.5299 3.36208 10.3163C2.93624 10.2688 2.62869 9.98407 2.53406 9.55692C2.43943 9.17723 2.60503 8.77381 2.9599 8.56024C3.17281 8.41785 3.43305 8.34666 3.69328 8.37039C4.37935 8.44158 4.82885 8.20428 5.16005 7.58728C5.37297 7.25505 5.49126 6.8279 5.49126 6.35329ZM6.39024 3.38697C6.24829 3.38697 6.01172 3.4107 5.7988 3.48189C5.04176 3.76666 4.30838 4.95319 4.40301 5.76003C4.42667 5.87868 4.47398 5.9736 4.61592 5.99733C4.75787 6.02106 4.87616 5.99733 4.92348 5.85495C4.97079 5.76003 4.99445 5.64137 4.99445 5.52272C5.04176 5.23795 5.11274 4.97692 5.25468 4.71588C5.6332 3.90904 6.15367 3.69546 7.029 3.9565C7.26557 4.02769 7.33654 4.09888 7.21825 4.35992C6.98168 4.88199 6.79242 5.4278 6.50853 5.94987C6.22464 6.49568 6.17732 7.11267 5.96441 7.68221C5.56223 8.65516 4.8525 9.12977 3.81157 9.12977C3.69328 9.12977 3.575 9.12977 3.48037 9.1535C3.36208 9.17723 3.2911 9.27216 3.2911 9.41454C3.31476 9.55692 3.40939 9.60438 3.52768 9.60438C4.49764 9.69931 5.34931 9.462 5.98807 8.67889C6.36659 8.20428 6.55585 7.65847 6.67413 7.06521C6.72145 6.8279 6.79242 6.78044 7.00534 6.92283C7.95164 7.51609 8.66136 8.27547 9.22914 9.22469C9.46572 9.62812 9.65498 10.079 9.82058 10.5061C9.89156 10.6723 9.98619 10.7672 10.1754 10.696C10.341 10.6248 10.3884 10.5061 10.3411 10.34C10.3174 10.2926 10.3174 10.2688 10.2937 10.2214C9.84424 8.96366 9.11086 7.89578 8.09359 7.04148C7.95164 6.92283 7.90433 6.8279 7.9753 6.63806C8.21187 6.09226 8.44845 5.54645 8.66136 4.97692C8.73234 4.78707 8.82697 4.73961 9.01623 4.73961C10.0098 4.73961 10.7905 4.28873 11.453 3.57681C11.5239 3.50562 11.5949 3.4107 11.5003 3.29204C11.4056 3.19712 11.311 3.14966 11.1691 3.22085C11.0508 3.26831 10.9561 3.38697 10.8615 3.48189C10.1754 4.19381 9.15817 4.59723 8.04627 3.88531C7.57312 3.57681 7.05265 3.38697 6.39024 3.38697Z"
                  fill="black"
                />
                <path
                  d="M2.96038 15.4905C2.81844 15.4905 2.70015 15.4905 2.55821 15.4905C2.17969 15.4905 2.17969 15.4905 2.17969 15.1108C2.17969 14.8498 2.25066 14.7549 2.51089 14.7549C2.8421 14.7786 3.14965 14.7786 3.48085 14.7549C3.76474 14.7549 3.74109 14.9447 3.74109 15.1108C3.74109 15.3007 3.74108 15.4905 3.48085 15.4668C3.29159 15.4905 3.12599 15.4905 2.96038 15.4905Z"
                  fill="black"
                />
                <path
                  d="M11.5715 15.4925C11.4059 15.4925 11.2403 15.4688 11.0983 15.4925C10.8144 15.5162 10.8145 15.3501 10.8145 15.1365C10.8145 14.923 10.8144 14.7569 11.0983 14.7806C11.4295 14.8043 11.7844 14.7806 12.1156 14.7806C12.3995 14.7806 12.3522 14.9704 12.3759 15.1603C12.3759 15.3264 12.3995 15.5162 12.1393 15.4925C11.9264 15.4925 11.7607 15.4925 11.5715 15.4925Z"
                  fill="black"
                />
              </svg>
            </span>
            <strong class="activity-name"> {{ activityIconName }} </strong> with {{ facilityName }}
          </li>
          <li class="activity-info-item">
            <span class="activity-info-item-icon-box">
              <svg fill="none" height="12" viewBox="0 0 10 12" width="10" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M5.00002 0.641602C2.75939 0.641602 0.937012 2.46398 0.937012 4.70461C0.937012 5.44252 1.26862 6.23421 1.28058 6.27006C1.38813 6.524 1.59725 6.91835 1.74961 7.14839L4.53696 11.3667C4.65048 11.54 4.82077 11.6386 5.00002 11.6386C5.17927 11.6386 5.34956 11.54 5.46308 11.3667L8.25043 7.1454C8.40279 6.91536 8.61192 6.52101 8.71947 6.26708C8.7344 6.23421 9.06303 5.43954 9.06303 4.70162C9.06303 2.46398 7.24065 0.641602 5.00002 0.641602ZM5.00002 6.85562C3.81398 6.85562 2.84902 5.89065 2.84902 4.70461C2.84902 3.51857 3.81398 2.55361 5.00002 2.55361C6.18606 2.55361 7.15103 3.51857 7.15103 4.70461C7.15103 5.89065 6.18606 6.85562 5.00002 6.85562Z"
                  fill="black"
                />
              </svg>
            </span>
            {{ activityAddress }}
          </li>
        </ul>
      </div>
      <div class="card-footer">
        <button class="btn-more" name="button" type="button">
          Learn more
        </button>
        <button class="btn-book-now" name="button" type="button" @click="bookActivity(activity)">
          Book Now
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import {BASE_URLS} from "@/common/constants/constants";
import ConfirmBooking from "@/partials/ConfirmBooking";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";

export default {
  name: "ActivityCard",
  components: {SocialSharingOnSignup, ConfirmBooking},

  data() {
    return {
      showModal: false,
      bookClass: [],
      isConfirm: false,
      showSharingModal: false
    };
  },
  props: {
    activityName: {
      type: String,
      default: "",
    },
    activity: {
      type: Object,
      required: true
    },
    activityStartDate: {
      type: String,
      default: "",
    },
    activityStartTime: {
      type: String,
      default: "",
    },
    activityEndTime: {
      type: String,
      default: "",
    },
    activityIcon: {
      type: String,
      default: "",
    },
    activityIconName: {
      type: String,
      default: "",
    },
    activityTypeImage: {
      type: String,
      default: "",
    },
    activityAddress: {
      type: String,
      default: "",
    },
    activityFree: {
      type: Boolean,
      default: false,
    },
    facilityImage: {
      type: String,
      default: "",
    },
    facilityName: {
      type: String,
      default: "",
    }
  },
  computed: {
    activitiesBaseUrl() {
      return BASE_URLS.ASSETS_PREFIX_MEMBER;
    },
  },
  methods: {
    bookActivity(data) {
      this.$emit("onBookActivity", data);
    }

  }

};
</script>

<style lang="scss" scoped>
.activity-card {
  background: #FFFFFA;
  box-shadow: 0px 4px 6px rgba(28, 4, 47, 0.03), 0px 12px 16px rgba(28, 4, 47, 0.08);
  border-radius: 12px;
  overflow: hidden;

  .card-header {
    width: 100%;
    height: 180px;

    .activity-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }
  }

  .card-body {
    padding: 24px 20px;

    .activity-info-box {
      display: flex;
      align-items: center;
      column-gap: 8px;
      margin-bottom: 4px;

      .logo-box {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);

        .logo {
          min-width: 42px;
          min-height: 42px;
          max-width: 42px;
          max-height: 42px;
          object-fit: cover;
          object-position: center;
        }
      }

      .business-name {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-weight: 700;
        line-height: 17px;
        letter-spacing: 0em;
        text-align: left;
        color: #000000;
      }
    }

    .activity-time-box {
      display: flex;
      align-items: center;
      margin-bottom: 32px;

      .activity-type-info {
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        line-height: 14.63px;
        font-weight: 400;

        .activity-date {
          font-weight: 700;
          margin-right: 7px;
        }

        .activity-time {
          margin-left: 5px;
          margin-right: 10px;
        }
      }

      .tag {
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        line-height: 14.63px;
        padding: 2px 5px;
        color: #ffff;
        background-color: #690FAD;
        border-radius: 3px;
        font-weight: 600;
        margin-left: 5px;
      }
    }

    .activity-info-list {
      display: flex;
      flex-direction: column;
      row-gap: 4px;

      .activity-info-item {
        display: flex;
        align-items: center;
        font-family: 'Montserrat', sans-serif;
        font-size: 12px;
        line-height: 14.63px;
        font-weight: 400;
        padding-left: 22px;
        padding-top: 2px;
        padding-bottom: 2px;

        strong {
          font-weight: 700;
          margin-right: 2px;
        }

        .activity-info-item-icon-box {
          position: absolute;
          width: 15px;
          margin-left: -22px;

          svg,
          img {
            min-width: 10px;
            max-width: 16px;
          }
        }
      }
    }
  }

  .card-footer {
    display: flex;
    text-align: center;
    justify-content: center;
    column-gap: 16px;
    margin-bottom: 24px;

    .btn-book-now,
    .btn-more {
      font-family: 'Montserrat', sans-serif;
      font-size: 14px;
      font-weight: 700;
      line-height: 17.07px;
      text-align: center;
      padding: 16px 32px;
      border-radius: 30px;
      -webkit-border-radius: 30px;
      -moz-border-radius: 30px;
      -ms-border-radius: 30px;
      -o-border-radius: 30px;
    }

    .btn-book-now {
      background-color: #690FAD;
      color: #FFFFFF;

      &:hover {
        background-color: #8C14E6;
      }

      &:active,
      &.active {
        color: #690FAD;
        background-color: #CAA8F5;
      }
    }

    .btn-more {
      background-color: #F2F5EA;
      color: #690FAD;
    }
  }
}

</style>