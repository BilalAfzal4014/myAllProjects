<template>
  <div class="wallet-body">
    <Table>
      <Thead title="Redeemed Offers" :columns="headings.length">
        <TH v-for="item in headings" :key="item">
          {{ item }}
        </TH>
      </Thead>
      <Tbody :numberOfPages="numberOfPages" @onScroll="handleScroll($event)">
        <TR v-for="(item, index) in data" :key="index" :show="collapseShow">
          <template v-slot:rowCollapse>
            <TRCollapse @onCollapseShow="handleCollapseShow">
              {{ item.name }}
            </TRCollapse>
          </template>
          <TD :title="headings[0]">
            {{ item.name }}
          </TD>
          <TD :title="headings[1]">
            {{ item.offer_type }}
          </TD>
          <TD :title="headings[2]">
            {{ item.company_name }}
          </TD>
          <TD :title="headings[3]">
            {{ item.is_unlimited_redemptions ? 'Unlimited' : item.remaining_redemptions }}
          </TD>
          <TD :title="headings[4]">
            {{ item.redeemed_at }}
          </TD>
        </TR>
      </Tbody>
    </Table>
  </div>
</template>

<script>
import { Table, Thead, TH, Tbody, TR, TRCollapse, TD } from "@/components/Table";
import { getOffersList } from "../../apiManager/offers";

export default {
  name: "WalletRedeemedOffers",
  components: {
    Table,
    Thead,
    TH,
    Tbody,
    TR,
    TRCollapse,
    TD
  },
  data() {
    return {
      headings: ["Offer Name", "Offer Type", "Business Name", "Redemptions Available", "Redemption Date"],
      data: [],
      collapseShow: false,
      limit: 10,
      offset: 0,
      numberOfPages: 1
    };
  },
  created() {
    this.getDealsOfferRedemptions();
  },
  methods: {
    handleCollapseShow() {
      this.collapseShow = !collapseShow;
    },
    async getDealsOfferRedemptions() {
      const res = await getOffersList(this.limit, this.offset);
      if (res.statusCode === 200  && res.data && res.data.length > 0) {
        this.data = [...this.data, ...res.data];
        this.numberOfPages = res?.meta?.total_pages;
      }
    },
    handleScroll(page) {
      if (this.numberOfPages > 1) {
        this.offset = this.limit * page;
        this.getDealsOfferRedemptions(this.limit, this.offset);
      }
    },
  }
};
</script>