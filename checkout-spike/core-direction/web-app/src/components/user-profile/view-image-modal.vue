<template>
  <div class="wrapper">
    <div id="profile-img-upload" class="custom-modal m-auto">
      <div class="modal-center">
        <div v-click-outside-parent-element="hideViewImageModal" class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <p class="upload-img-title mb-7">
                  Update Your Profile Image
                </p>
                <p class="upload-img-desc mb-6">
                  Please upload your image in
                  aspect ratio of <em>1:1</em> or size of <em>500 X 500 px</em>
                </p>
                <img :src="userAvatar" alt="" class="profile-img mx-auto rounded-full">
                <button class="btn-img-upload mx-auto bg-gradient rounded-full" @click="$emit('cropImage')">
                  Upload Image
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ViewImageModal",
  props: {
    userAvatar: {
      type: String,
      required: true,
    }
  },
  methods: {
    hideViewImageModal() {
      this.$emit("onClickOutsideViewImageModal");
    },
  }
};
</script>

<style scoped>
#profile-img-upload .modal-outer-box,
#profile-img-crop .modal-outer-box {
  background: #FFFFFF;
  border-radius: 11px;
  padding-top: 53px;
  padding-bottom: 47px;
}

#profile-img-upload .form-container,
#profile-img-crop .form-container {
  width: 100%;
}

#profile-img-upload .upload-img-title,
#profile-img-crop .upload-img-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: 21px;
  letter-spacing: 0em;
  text-align: center;
}

#profile-img-upload .upload-img-desc,
#profile-img-crop .upload-img-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 16px;
  letter-spacing: 0em;
  text-align: center;
}

#profile-img-upload .upload-img-desc em,
#profile-img-crop .upload-img-desc em {
  font-weight: 700;
}

#profile-img-upload .profile-img,
#profile-img-crop .profile-img {
  width: 234px;
  max-width: 234px;
  height: 234px;
  max-height: 234px;
  object-fit: fill;
  object-position: center;
}

#profile-img-upload .profile-img {
  object-fit: cover;
}

@media (max-width:767px){
  #profile-img-upload .profile-img,
  #profile-img-crop .profile-img {
    width: 200px;
    max-width: 200px;
    height: 200px;
    max-height: 200px;
  }
}

#profile-img-upload .btn-img-upload,
#profile-img-crop .btn-img-upload {
  display: block;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 500;
  line-height: 19px;
  letter-spacing: 0em;
  text-align: center;
  padding: 11px 30px 12px;
  color: #FFFFFF;
  margin-top: 56px;
}

#profile-img-upload .modal-outer-box {
  max-width: calc(396px + 2rem);
}

#profile-img-upload .form-container {
  max-width: 278px;
}

#profile-img-upload .upload-img-desc,
#profile-img-crop .upload-img-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 16px;
  letter-spacing: 0em;
  text-align: center;
}

#profile-img-upload .upload-img-desc em,
#profile-img-crop .upload-img-desc em {
  font-weight: 700;
}
</style>