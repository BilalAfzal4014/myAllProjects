<template>
  <main id="main">
    <section id="wallet">
      <div class="custom-container">
        <tabs :active-tab="activeTab" @clicked="switchComponent" />
        <keep-alive>
          <component :is="currentComponent" />
        </keep-alive>
      </div>
    </section>
  </main>
</template>

<script>
import {
  PackageMembershipComponent,
  Tabs,
  WalletBookingHistory,
  WalletRedeemedOffers,
  WalletSaveCardComponent
} from "../partials/wallet";
import {updateMetaInformation} from "@/utils";

export default {
  name: "WalletComponent",
  components: {WalletSaveCardComponent, WalletBookingHistory, PackageMembershipComponent, Tabs},
  data() {
    return {
      activeTab: "package",
      currentComponent: PackageMembershipComponent,
    };
  },
  methods: {
    switchComponent(value) {
      const componentMap = {
        history: WalletBookingHistory,
        package: PackageMembershipComponent,
        offers: WalletRedeemedOffers,
        default: WalletSaveCardComponent
      };

      this.currentComponent = componentMap[value] || componentMap.default;
      this.activeTab = value;
    }
  },
  mounted() {
    updateMetaInformation("My Wallet | Core Direction", "Core Direction, Coredirection,wallet, packages, membership, booking history", "Keep track of your purchased Packages & Memberships and view your booking history.", "My Wallet | Core Direction", "Keep track of your purchased Packages & Memberships and view your booking history.", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/wallet");
  }
};
</script>

<style scoped>
@import "../assets/css/wallet.css";

.custom-container {
  width: 100%;
  max-width: calc(1240px + 40px);
  padding-left: 20px;
  padding-right: 20px;
  margin-left: auto;
  margin-right: auto;
}
</style>