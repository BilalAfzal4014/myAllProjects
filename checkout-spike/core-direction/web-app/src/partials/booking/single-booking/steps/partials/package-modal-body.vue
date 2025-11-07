<template>
  <div class="form-container mx-auto">
    <div class="activity-detail-card">
      <div class="activity-detail-header flex items-center">
        <div class="brand-img rounded-full">
          <img :src="packageProviderImage" alt="" class="rounded-full">
        </div>
        <div class="brand-info-box">
          <p class="brand-label">
            Provider Name
          </p>
          <p class="brand-name">
            {{ packageDetails.company_title }}
          </p>
        </div>
      </div>
      <div class="activity-detail-body">
        <div class="grid grid-cols-3 gap-2">
          <div class="package-name col-span-2">
            Package Name - {{ packageDetails.name }}
          </div>
          <div class="package-price">
            {{ packageDetails.price }} AED
          </div>
        </div>
        <div class="package-info-box">
          <p class="package-info-label">
            Type
          </p>
          <p class="package-info-title">
            Package
          </p>
        </div>
        <div class="package-info-box">
          <p class="package-info-label">
            Payment Type
          </p>
          <p class="package-info-title">
            One time purchase
          </p>
        </div>
        <div class="package-info-box">
          <p class="package-info-label">
            No. of Sessions
          </p>
          <p class="package-info-title">
            {{ packageDetails.visits }}
          </p>
        </div>
        <div class="package-info-box">
          <p class="package-info-label">
            Validity
          </p>
          <p class="package-info-title">
            {{ packageDetails.validity_days }} Days
          </p>
        </div>
        <div class="package-info-box">
          <p class="package-t-c">
            Package Terms &amp; Conditions
          </p>
          <p class="package-t-c--item" v-html="packageDetails.description" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    props: {
        packageProviderImage: {
            type: String,
            required: true
        },
        packageDetails: {
            type: Object,
            required: true
        }

    }
};
</script>