<template>
  <!-- customer-support -->
  <section id="customer-support">
    <div class="customer-support-box-container">
      <div class="customer-support-form">
        <div class="customer-support-form-outer-box">
          <div class="customer-support-form-inner-box">
            <div class="form">
              <p class="form-title">
                Send us a Message
              </p>
              <div class="flexgroup">
                <div class="Name-field-box">
                  <div class="group">
                    <label class="" for="formName">Your Name</label>
                    <input id="formName" v-model="name" class="cool border-b-1 border-black" type="text">
                  </div>
                </div>
                <div class="email-box">
                  <div class="group">
                    <label for="formEmail">Email Address</label>
                    <input id="formEmail" v-model="email" class="cool border-b-1 border-black" type="text">
                  </div>
                </div>
              </div>
              <div class="subject-field-box">
                <div class="group">
                  <label class="" for="formSubject">Subject</label>
                  <input id="formSubject" v-model="subject" class="cool border-b-1 border-black" type="text">
                </div>
              </div>
              <div class="subject-field-box">
                <div class="service-group">
                  <label class="" for="formService">Which services are you interested in:</label>
                  <select id="formService" v-model="type" class="cool border-b-1 border-black">
                    <option>General Enquiry</option>
                    <option>Corporate Wellness</option>
                    <option>Event consultancy</option>
                    <option>Industry Partnership</option>
                  </select>
                </div>
              </div>
              <div class="message-field-box mb-6">
                <div class="group">
                  <label for="formMessage">Message</label>
                  <textarea id="formMessage" v-model="message" class="cool border-b-1 border-black" rows="6" />
                </div>
              </div>
              <div class="form-btn-box">
                <button class="btn-send-form rounded-full" @click="sendEmail">
                  Send Email
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import * as toastr from "toastr";

export default {
    name: "CustomerSupportContactUs",
    data() {
        return {
            name: "",
            email: "",
            type: "",
            subject: "",
            message: ""
        };
    },
    methods: {
        sendEmail() {
            if (this.name === "") {
                toastr.error("Name is required");
                return;
            }
            if (this.email === "") {
                toastr.error("Email is required");
                return;
            }
            if (this.type === "") {
                toastr.error("Type is required");
                return;
            }
            if (this.subject === "") {
                toastr.error("Subject is required");
                return;
            }
            if (this.message === "") {
                toastr.error("Message is required");
                return;
            }

            let payload = {
                "email": this.email,
                "name": this.name,
                "type": this.type,
                "subject": this.subject,
                "message": this.message
            };
            this.oldApi("post",
                this.constants.getUrl("contactUs"),
                payload
            ).then((response) => {
                toastr.success("Form has been submitted successfully.");
                this.name = "";
                this.email = "";
                this.type = "";
                this.subject = "";
                this.message = "";
            }).catch((error) => {
                toastr.error(error[0].response.data.errors[0].error);
            });
        }

    }

};
</script>

<style scoped>

</style>