<template>
  <div class="payment-success-box">
    <div class="payment-success-inner-box">
      <div class="icon-box">
        <svg fill="none" height="64" viewBox="0 0 64 64" width="64" xmlns="http://www.w3.org/2000/svg">
          <circle cx="32.0013" cy="32.0003" fill="#CAA8F5" r="5.33333" />
          <circle cx="32" cy="32" fill="#690FAD" r="32" />
          <path
            d="M49.8797 19.0125C48.5761 17.7089 46.4631 17.7091 45.1598 19.0125L27.3524 36.8199L19.0749 29.1105C17.7262 27.8542 15.6143 27.9293 14.3579 29.2779C13.102 30.6271 13.1769 32.7386 14.5256 33.9949L25.1565 43.8961C26.4632 45.1131 28.4859 45.0805 29.7533 43.8511C29.7994 43.8093 29.8453 43.7667 29.8898 43.7222L49.8797 23.7323C51.183 22.429 51.1832 20.3161 49.8797 19.0125Z"
            fill="#FFFFFA"
          />
        </svg>
      </div>
      <p class="title">
        Payment successful
      </p>
      <div class="payment-received-box">
        <p class="payment-received-message">
          Payment has been received successfully, Thank you!
        </p>
        <p class="payment-received-amount">
          AED {{ paidAmount }}
        </p>
      </div>
      <div class="payment-detail-box">
        <div class="payment-detail-row">
          <p class="label">
            Network
          </p>
          <p class="value">
            **** {{ cardLastDigits }}
          </p>
        </div>
        <div class="payment-detail-row">
          <p class="label">
            Paid in
          </p>
          <p class="value">
            {{ paidAmount }} AED
          </p>
        </div>
        <div class="payment-detail-row">
          <p class="label">
            Transaction time
          </p>
          <p class="value">
            {{ new Date(startDate).toLocaleDateString("en-US", {year: "numeric", month: "short", day: "numeric",}) }}
          </p>
        </div>
        <div class="payment-detail-row">
          <p class="label">
            Next schedule payment on
          </p>
          <p class="value">
            {{ new Date(endDate).toLocaleDateString("en-US", {year: "numeric", month: "short", day: "numeric",}) }}
          </p>
        </div>
      </div>
    </div>
    <div class="payment-success-btn-box">
      <a class="btn-core-premium-subscribe" href="#core-premium-features">Explore Features</a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    paidAmount: {
      type: String,
      default: null
    },
    cardLastDigits: {
      type: String,
      default: "",
    },
    startDate: {
      type: String,
      default: "",
    },
    endDate: {
      type: String,
      default: "",
    }
  }
};
</script>

<style lang="scss" scoped>
.payment-success-box {
  width: 100%;

  .icon-box {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    margin-bottom: 24px;
    margin-left: auto;
    margin-right: auto;
    animation: zoom-in-zoom-out 1s ease;
    -webkit-animation: zoom-in-zoom-out 1s ease;
    @media(max-width: 767px) {
      width: 32px;
      height: 32px;
      margin-bottom: 12px;
    }

    svg,
    img {
      width: 100%;
      height: 100%;
    }
  }

  .title {
    padding: 8px;
    color: #000;
    text-align: center;
    font-family: "Montserrat", sans-serif;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    text-transform: capitalize;
    margin-bottom: 24px;
    animation: fade-in 2s ease;
    -webkit-animation: fade-in 2s ease;
    @media (max-width: 767px) {
      margin-bottom: 12px;
    }
  }

  .payment-received-box {
    border: 1px solid rgba(6, 7, 14, 0.45);
    border-radius: 8px;
    -webkit-border-radius: 8px;
    -moz-border-radius: 8px;
    -ms-border-radius: 8px;
    -o-border-radius: 8px;
    margin-bottom: 58px;
    animation: fade-in 2s ease;
    -webkit-animation: fade-in 2s ease;
    @media (max-width: 767px) {
      margin-bottom: 29px;
    }

    .payment-received-message {
      padding: 24px;
      border-bottom: 1px solid rgba(6, 7, 14, 0.45);
      color: #06070E;
      text-align: center;
      font-family: "Montserrat", sans-serif;
      font-size: 14px;
      font-style: normal;
      font-weight: 500;
      line-height: normal;
      @media (max-width: 767px) {
        padding: 12px;
      }
    }

    .payment-received-amount {
      color: #06070E;
      text-align: center;
      font-family: "Montserrat", sans-serif;
      font-size: 20px;
      font-style: normal;
      font-weight: 600;
      line-height: normal;
      padding: 17px 24px;
      @media (max-width: 767px) {
        padding: 17px 12px;
      }
    }
  }

  .payment-detail-box {
    padding: 8px 24px;
    border-radius: 8px;
    border: 1px solid rgba(6, 7, 14, 0.45);
    margin-bottom: 62px;
    display: flex;
    flex-direction: column;
    row-gap: 16px;
    animation: fade-in 2s ease;
    -webkit-animation: fade-in 2s ease;
    @media (max-width: 767px) {
      row-gap: 8px;
      margin-bottom: 32px;
    }

    .payment-detail-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      column-gap: 16px;
      @media (max-width: 767px) {
        column-gap: 8x;
      }

      .label,
      .value {
        color: #06070E;
        font-family: "Montserrat", sans-serif;
        font-size: 14px;
        font-style: normal;
        line-height: normal;
        @media (max-width: 767px) {
          font-size: 12px;
        }
      }

      .label {
        text-align: left;
        font-weight: 400;
      }

      .value {
        text-align: right;
        font-weight: 500;
      }
    }
  }

  .payment-success-btn-box {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    column-gap: 16px;
    row-gap: 16px;
    animation: fade-in 3s ease;
    -webkit-animation: fade-in 3s ease;

    .btn-download {
      padding: 11px 24px;
      border-radius: 24px;
      border: 1px solid #690FAD;
      background: #FFFFFA;
      color: #690FAD;
      text-align: center;
      font-family: "Montserrat", sans-serif;
      font-size: 14px;
      font-style: normal;
      font-weight: 600;
      line-height: normal;
      @media (max-width: 767px) {
        width: 100%;
      }
    }

    .btn-core-premium-subscribe {
      display: flex;
      align-items: center;
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
      width: max-content;
    }

    .btn-core-premium-subscribe {
      @media (max-width: 767px) {
        width: 100%;
        justify-content: center;
      }
    }
  }
}
</style>