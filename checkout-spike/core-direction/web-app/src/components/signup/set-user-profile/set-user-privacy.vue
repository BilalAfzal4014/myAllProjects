<template>
  <div class="privacy-policy-box">
    <p class="section-title">
      Select your Privacy
    </p>
    <ul class="account-privacy-list flex items-center">
      <li class="account-privacy-item">
        <input id="public" name="radio" type="radio" value="public" @change="onChangePrivacy($event)">
        <label for="public">Visible to Public</label>
      </li>
      <li v-click-outside-parent-element="hideToolTip" class="account-privacy-item flex items-center relative">
        <input id="friend" checked="" name="radio" type="radio" value="private" @change="onChangePrivacy($event)">
        <label for="friend">Private</label>
        <div class="tooltip-box relative flex" @click="isHideTooltip = !isHideTooltip">
          <button class="btn-tooltip">
            <information-icon />
          </button>
          <div v-if="isHideTooltip">
            <tooltip-content>
              <template v-slot:tooltip-content>
                <div class="tooltip-content-inner-box relative">
                  <p class="tooltip-desc">
                    By setting your profile to Private, users will be able to search for you and
                    request to follow you but will not be able to view details on your profile until you accept their
                    follow
                    request.
                  </p>
                  <p class="tooltip-desc">
                    You can accept, decline or ignore follow requests. If you accept a follow request,
                    the follower may view your profile including your bio, daily wellness score, activity calendar and
                    upcoming public bookings.
                  </p>
                  <p class="tooltip-desc">
                    Followers can also invite you to their schedules and booked activity so you can
                    inspire movement together.
                  </p>
                </div>
              </template>
            </tooltip-content>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import InformationIcon from "@/svgs/information-icon";
import TooltipContent from "@/components/tooltip-content";

export default {
    name: "SetUserPrivacy",
    components: {TooltipContent, InformationIcon},
    data() {
        return {
            isHideTooltip: false
        };

    },
    methods: {
        onChangePrivacy(event) {
            this.$emit("onChangePrivacy", event.target.value);
        },
        hideToolTip() {
            this.isHideTooltip = false;
        }
    }
};
</script>
<style scoped>
@media only screen and (max-width: 768px) {
  .tooltip-content-box {
    display: block;
    position: absolute;
    left: -100px !important;
    top: 100px !important;
  }
}
</style>