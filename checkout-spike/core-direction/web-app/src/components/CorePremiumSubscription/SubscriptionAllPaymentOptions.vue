<template>
  <div class="payment-mode-box">
    <div class="inputGroup">
      <input id="payMode1" checked name="payMode" type="radio">
      <label for="payMode1">
        <span class="icon-box">
          <svg fill="none" height="16" viewBox="0 0 24 16" width="24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.5 11.6304V9.77778H2.96698V11.6304H12.5Z" fill="#FFFFFA" />
            <path clip-rule="evenodd" d="M4.2307 0C1.89415 0 0 1.66154 0 3.71114V12.2889C0 14.3385 1.89415 16 4.2307 16H19.7693C22.1059 16 24 14.3385 24 12.2889V3.71114C24 1.66154 22.1059 0 19.7693 0H4.2307ZM23.0341 3.53684H0.965936C1.06864 2.03348 2.49106 0.842105 4.2307 0.842105H19.7693C21.5089 0.842105 22.9314 2.03348 23.0341 3.53684ZM0.96 5.38947H23.04V12.2889C23.04 13.8734 21.5757 15.1579 19.7693 15.1579H4.2307C2.42434 15.1579 0.96 13.8734 0.96 12.2889V5.38947Z"
                  fill="#FFFFFA"
                  fill-rule="evenodd"
            />
          </svg>
        </span>
        <span class="content">Card</span>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubscriptionAllPaymentOptions",
};
</script>

<style lang="scss" scoped>
.payment-mode-box {
  display: grid;
  grid-template-columns: 1fr 1fr;
  column-gap: 12px;
  row-gap: 12px;
  margin: 24px 0;
  @media (max-width: 767px) {
    column-gap: 8px;
    row-gap: 8px;
    margin: 12px 0 16px;
  }

  .inputGroup {
    display: inline-block;
    position: relative;
    border-radius: 15px;
    box-shadow: 0px 2px 4px 0px #0000001A;

    label {
      padding: 12px 16px;
      width: 100%;
      height: 61px;
      display: flex;
      flex-direction: column;
      row-gap: 4px;
      text-align: left;
      cursor: pointer;
      position: relative;
      z-index: 2;
      overflow: hidden;
      border-radius: 8px;
      font-family: 'Montserrat', sans-serif;
      color: rgba(#06070E, 0.44999998807907104);
      font-size: 14px;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
      border: 1px solid #000000;
      margin: 0;

      svg, path {
        fill: #000;
      }

      .payment-mode-icon-box {
        width: max-content;
        border-radius: 100px;
        border: 0.5px solid #06070E;
        background: #FFF;
        padding: 2px 8px;
      }

      &:after {
        width: 10px;
        height: 10px;
        content: '';
        border: 6px solid rgba(#06070E, 0.44999998807907104);
        outline: 1px solid #000;
        outline-offset: 4px;
        background-color: #ffff;
        border-radius: 50%;
        z-index: 2;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
      }
    }

    input:checked ~ label {
      padding: 13px 17px;
      color: #FFFFFA;
      border: none;
      background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);

      svg, path {
        fill: #fff;
      }

      &:before {
        transform: translate(-50%, -50%) scale3d(56, 56, 1);
        opacity: 1;
      }

      &:after {
        border-color: #fff;
        outline-color: #fff;
      }
    }

    input {
      width: 10px;
      height: 10px;
      order: 1;
      z-index: 2;
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      visibility: hidden;
    }
  }
}
</style>