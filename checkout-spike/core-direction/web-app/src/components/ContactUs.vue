<template>
  <div>
    <ContactUsBanner />
    <CustomerSupportContactUs />
    <ContactInformation />
    <SocialMediaLinksContactUs />
  </div>
</template>

<script>

import ContactUsBanner from "../partials/contact/ContactUsBanner";
import SocialMediaLinksContactUs from "../partials/contact/SocialMediaLinksContactUs";
import CustomerSupportContactUs from "../partials/contact/CustomerSupportContactUs";
import ContactInformation from "../partials/contact/ContactInformation";
import {updateMetaInformation} from "../utils";

export default {
    name: "ContactUs",
    components: {
        ContactInformation,
        CustomerSupportContactUs,
        SocialMediaLinksContactUs,
        ContactUsBanner
    },
    data() {
        return {
            isLogin: false,
        };
    },
    created() {
        this.isLogin = (this.$store.getters.getStoreTokenGetters ? true : false);

    },
    mounted() {
        var zohoChat = document.querySelector(".zsiq_floatmain");
        if (typeof (zohoChat) != "undefined" && zohoChat != null) {
            zohoChat.style.display = "block";
            return;
        }
        this.embedZohoReport();
        updateMetaInformation("Contact Us | Core Direction","Core Direction, Coredirection,contact us, contact, core direction email, core direction phone number, core direction live cha","Have a question about Core Direction? Get in touch here, you can drop us an email, call us or chat instantly on our live chat.");
    },
    methods: {
        embedZohoReport() {
            $zoho.salesiq = $zoho.salesiq || {
                widgetcode: "8351374cff1f9d891f5bf0e6bd4958b747ea687c748d06ca514bd97d1ed1d510ed9114799768c36d281683f5d7c37f49",
                values: {},
                ready: function () {
                }
            };
            var d = document;
            var s = d.createElement("script");
            s.type = "text/javascript";
            s.id = "zsiqscript";
            s.defer = true;
            s.src = "https://salesiq.zoho.com/widget";
            var t = d.getElementsByTagName("script")[0];
            t.parentNode.insertBefore(s, t);
        }
    },
    beforeDestroy() {
        const zohoChat = document.querySelector(".zsiq_floatmain");
        zohoChat.style.display = "none";
    // $(".zsiq_floatmain").hide();
    }
};
</script>



