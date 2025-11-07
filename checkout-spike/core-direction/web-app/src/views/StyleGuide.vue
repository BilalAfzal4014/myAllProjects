<template>
  <div class="style">
    <div class="container">
      <span class="style-subTitle">Typography</span>
      <hr>
      <br>
      <br>
      <div class="style-row">
        <div class="style-col">
          <H1> Heading 1 </H1>
          <H2> Heading 2 </H2>
          <H3> Heading 3 </H3>
          <H4> Heading 4 </H4>
          <H5> Heading 5 </H5>
          <H6> Heading 6 </H6>
        </div>
      </div>
      <hr>
      <span class="style-subTitle">Colors</span>
      <hr>
      <br>
      <br>
      <div class="style-row">
        <div class="style-col">
          <div class="color-box primary-dark">
            <code>$primary-dark</code>
          </div>
        </div>
        <div class="style-col">
          <div class="color-box primary-default">
            <code>$primary</code>
          </div>
        </div>
        <div class="style-col">
          <div class="color-box primary-second">
            <code>$second-primary</code>
          </div>
        </div>
      </div>
      <br>
      <div class="style-row">
        <div class="style-col">
          <div class="color-box secondary-accent">
            <code>$accent-white</code>
          </div>
        </div>
        <div class="style-col">
          <div class="color-box secondary-white">
            <code>$secondary-white</code>
          </div>
        </div>
        <div class="style-col">
          <div class="color-box neutral-white">
            <code>$white</code>
          </div>
        </div>
      </div>
      <br>
      <div class="style-row">
        <div class="style-col">
          <div class="color-box neutral-cd-black">
            <code>$cd-black</code>
          </div>
        </div>
      </div>
      <br>
      <hr>
      <span class="style-subTitle">Buttons</span>
      <hr>
      <br>
      <br>
      <br>
      <div class="style-row">
        <H5> Default (Shadows) </H5>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.DEFAULT" :size="CONSTANT.SIZES.SMALL">
            Small
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.DEFAULT">
            Default
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.DEFAULT" :size="CONSTANT.SIZES.MEDIUM">
            Medium
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.DEFAULT" :size="CONSTANT.SIZES.LARGE">
            Large
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.DEFAULT" :size="CONSTANT.SIZES.XLARGE">
            Extra large
          </Button>
        </div>
        <H5> Default (Gradients) </H5>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.GRADIENT" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.SMALL">
            Small
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.GRADIENT" :btn-style="BUTTON_STYLE.ROUND_EDGE">
            Default
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.GRADIENT" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.MEDIUM">
            Medium
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.GRADIENT" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.LARGE">
            Large
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.GRADIENT" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.XLARGE">
            Extra large
          </Button>
        </div>
        <br>
        <H5> Primary (Square) </H5>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.SMALL">
            Small
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.SQUARE">
            Default
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.MEDIUM">
            Medium
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.LARGE">
            Large
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.XLARGE">
            Extra large
          </Button>
        </div>
        <br>

        <H5> Primary (Rounded) </H5>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.SMALL">
            Small
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.ROUND_EDGE">
            Default
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.MEDIUM">
            Medium
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.LARGE">
            Large
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.PRIMARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.XLARGE">
            Extra large
          </Button>
        </div>
      </div>
      <br>
      <br>
      <div class="style-row">
        <H5> Secondary (Square) </H5>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.SMALL">
            Small
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.SQUARE">
            Default
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.MEDIUM">
            Medium
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.LARGE">
            Large
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.SQUARE" :size="CONSTANT.SIZES.XLARGE">
            Extra large
          </Button>
        </div>
        <br>
        <H5> Secondary (Rounded) </H5>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.SMALL">
            Small
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.ROUND_EDGE">
            Default
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.MEDIUM">
            Medium
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.LARGE">
            Large
          </Button>
        </div>
        <div class="style-col">
          <Button :btn-type="BUTTON_TYPES.SECONDARY" :btn-style="BUTTON_STYLE.ROUND_EDGE" :size="CONSTANT.SIZES.XLARGE">
            Extra large
          </Button>
        </div>
      </div>
      <hr>
      <span class="style-subTitle">Inputs</span>
      <hr>
      <br>
      <br>
      <div class="style-row">
        <div :style="{width:`39rem`}">
          <InputWithError :validate="`required|email`" inputType="email" inputName="email" placeholder="example@gmail.com" />
        </div>
        <div :style="{width:`39rem`}">
          <InputWithError :validate="`required`" inputName="name" placeholder="Enter your name" />
        </div>
        <div :style="{width:`39rem`}">
          <Input inputType="text" placeholder="Disabled" :disabled="true" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { H1, H2, H3, H4, H5, H6 } from "@/components/Typography";
import { Button, BUTTON_STYLE, BUTTON_TYPES } from "@/components/Buttons";
import { InputWithError, Input } from "@/components/form";
export default {
  name: "StyleGuide",
  components:{
    H1,
    H2,
    H3,
    H4,
    H5,
    H6,
    Button,
    Input,
    InputWithError,
  },
  data(){
    return {
      BUTTON_STYLE,
      BUTTON_TYPES,
    };
  },

};
</script>

<style lang="scss" scoped>
  .style{
    padding: 3rem 0;
    &-subTitle {
      font-size: 2.8rem;
      font-weight: 600;
      color: $cd-black;
      display: inline-block;
      margin: 2rem 0;
    }

    &-row {
      display: flex;
      gap: 6rem;
      margin-bottom: 3rem;
      flex-wrap: wrap;
    }
    .primary {
      &-dark {
        background-color: $primary-dark;
      }
      &-default {
        background-color: $primary;
      }
      &-second {
        background-color: $second-primary;
      }
    }
    .secondary {
      &-accent {
        background-color: $accent-white;
      }
      &-light {
        background-color: $secondary-white;
      }
    }
    .neutral{
      &-cd-black {
        background-color: $cd-black;
      }
      &-white {
        background-color: $white;
      }
    }
    .color-box {
      width: 24rem;
      min-height: 24rem;
      border-radius: 0.7rem;
      position: relative;
      overflow: hidden;

      code {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0px;
        border-radius: 0;
        padding: 1rem;
        font-size: 1.4rem;
        font-weight: 500;
        color: #e83e8c;
        background-color: #eee;
      }
    }
  }

</style>