<template>
  <div class="wrapper">
    <div id="profile-img-crop" class="custom-modal m-auto">
      <div class="modal-center">
        <div v-click-outside-crop-image-modal="hideCropImageModal" class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <p class="upload-img-title mb-7">
                  Select your image
                </p>
                <p class="upload-img-desc mb-6">
                  Resize your image
                </p>
                <div class="profile-img-box mx-auto">
                  <div class="img-cropper">
                    <vue-cropper
                      ref="cropper"
                      :aspect-ratio="1"
                      :auto-crop="true"
                      :auto-crop-area="0.5"
                      :crop-box-resizable="false"
                      :min-crop-box-height="150"
                      :min-crop-box-width="150"
                      :src="userAvatar"
                      :view-mode="1"
                      :zoom-on-wheel="false"
                    />
                  </div>
                </div>
                <div class="flex items-baseline gap-2 mt-9">
                  <div class="negative-icon">
                    <negative-icon />
                  </div>
                  <div class="range-filter w-full">
                    <input v-model="zoomValue" max="1" min="0.1" step="0.1" type="range" @input="changeZoom">
                  </div>
                  <div class="h-1">
                    <plus-icon />
                  </div>
                </div>
                <div class="flex justify-between">
                  <button class="btn-img-upload mx-auto bg-gradient rounded-full" @click="reset">
                    Reset
                  </button>
                  <button class="btn-img-upload mx-auto bg-gradient rounded-full" @click="cropImage">
                    Upload Image
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import VueCropper from "vue-cropperjs";
import "cropperjs/dist/cropper.css";
import NegativeIcon from "@/svgs/negative-icon";
import PlusIcon from "@/svgs/plus-icon";

export default {
  name: "CropProfileImage",
  components: {PlusIcon, NegativeIcon, VueCropper},
  props: {
    userAvatar: {
      type: String,
      required: true,

    }
  },
  data() {
    return {
      cropImg: "",
      imgSrc: "/assets/images/profile-img-cropped.png",
      zoomValue: (1 + 0.1) / 2,
      minCroppedWidth: 300,
      minCroppedHeight: 320,
      maxCroppedWidth: 300,
      maxCroppedHeight: 320,
      data: {
        width: (this.minCroppedWidth + this.maxCroppedWidth) / 2,
        height: (this.minCroppedHeight + this.maxCroppedHeight) / 2,
      },
    };
  },
  methods: {
    cropImage() {
      this.cropImg = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.$emit("onUploadImage", this.cropImg);

    },
    reset() {
      this.$refs.cropper.reset();
      this.zoomValue = (1 + 0.1) / 2;
    },
    changeZoom(event) {
      this.zoomValue = event.target.value;
      this.$refs.cropper.zoomTo(event.target.value);
    },
    hideCropImageModal() {
      this.$emit("onClickOutsideCropImageModal");
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

@media (max-width: 767px) {
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

#profile-img-crop .modal-outer-box {
  max-width: calc(632px + 2rem);
}

#profile-img-crop .form-container {
  max-width: 310px;
}

#profile-img-crop .range-filter input[type=range] {
  -webkit-appearance: none;
  margin: 10px 0;
  width: 100%;
}

#profile-img-crop .range-filter input[type=range]:focus {
  outline: none;
}

#profile-img-crop .range-filter input[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #000000;
  border-radius: 25px;
}

#profile-img-crop .range-filter input[type=range]::-webkit-slider-thumb {
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border: 1px solid #000000;
  height: 20px;
  width: 20px;
  border-radius: 30px;
  background: #FFFFFF;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -9px;
}

#profile-img-crop .range-filter input[type=range]::-moz-range-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #000000;
  border-radius: 25px;
}

#profile-img-crop .range-filter input[type=range]::-moz-range-thumb {
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border: 1px solid #000000;
  height: 20px;
  width: 20px;
  border-radius: 30px;
  background: #FFFFFF;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -9px;
}

#profile-img-crop .range-filter input[type=range]::-ms-track {
  width: 100%;
  height: 1px;
  cursor: pointer;
  background: #000000;
  border-radius: 25px;
}

#profile-img-crop .range-filter input[type=range]::-ms-fill-lower {
  background: #ac51b5;
  border: 0px solid #000101;
  border-radius: 50px;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
}

#profile-img-crop .range-filter input[type=range]::-ms-fill-upper {
  background: #ac51b5;
  border: 0px solid #000101;
  border-radius: 50px;
  box-shadow: 0px 0px 0px #000000, 0px 0px 0px #0d0d0d;
}

#profile-img-crop .range-filter input[type=range]::-ms-thumb {
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border: 1px solid #000000;
  height: 20px;
  width: 20px;
  border-radius: 30px;
  background: #FFFFFF;
  cursor: pointer;
  -webkit-appearance: none;
  margin-top: -9px;
}

</style>