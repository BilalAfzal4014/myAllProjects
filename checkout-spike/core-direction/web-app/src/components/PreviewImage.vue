<template>
  <img v-if="useSrcset" :alt="alt"
       :class="classes"
       :src="getImageUrl(src, type)"
       :srcset="getSrcset(src)"
       loading="lazy"
       sizes="(max-width: 320px) 280px,
            (max-width: 480px) 440px,
            800px"
  >
  <img v-else :alt="alt"
       :class="classes"
       :src="getImageUrl(src, type)"
       loading="lazy"
  >
</template>

<script>
import DefaultImage from "@/assets/images/default_profile_img.png";

export default {
  name: "PreviewImage",
  props: {
    src: {
      type: String,
      required: false,
      default: "",
    },
    type: {
      type: String,
      required: true,
    },
    alt: {
      type: String,
      default: "",
    },
    classes: {
      type: String,
      default: "",
    },
    useSrcset: {
      type: Boolean,
      default: true
    },
    additionalParams: {
      type: Object,
      default: () => ({
        optimizer: "image",
        format: "webp",
        width: null,
        aspect_ratio: null,
        sharpen: null,
      }),
    },
  },
  methods: {
    getImageUrl(imagePath, type) {
      let url = DefaultImage;

      if (imagePath) {
        url = `${imagePath}?class=${type === "logo" ? "thumbnail" : "logo"}`;

        Object.entries(this.additionalParams)
          .filter(([_, value]) => value)
          .forEach(([key, value]) => {
            url += `&${key}=${value}`;
          });
      }

      return this.constants.getImageOptimisedUrl(url);
    },
    getOptimiserImage(image) {
      return this.constants.getImageOptimisedUrl(image);
    },
    getSrcset(image) {
      const baseOptimiserImage = this.getOptimiserImage(image);
      return `
            ${baseOptimiserImage}?optimizer=image&format=webp&width=608 320w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=848 480w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=608 800w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=1032 1032w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=1216 1216w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=1456 1456w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=1696 1696w,
            ${baseOptimiserImage}?optimizer=image&format=webp&width=1920 1920w,`;
    }
  }
};
</script>
