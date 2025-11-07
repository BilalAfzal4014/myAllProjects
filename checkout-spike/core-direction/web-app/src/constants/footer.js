import FACEBOOK_ICON from "@/assets/images/social_svg/facebook.svg";
import INSTAGRAM_ICON from "@/assets/images/social_svg/instagram.svg";
import LINKEDIN_ICON from "@/assets/images/social_svg/linkedin.svg";
import YOUTUBE_ICON from "@/assets/images/social_svg/youtube.svg";
import DOWN_ARROW from "@/assets/images/down_arrow.svg";
import { EXTERNAL_LINKS } from "@/common/constants/constants";

export const FOOTER_DESCRIPTION = `A fitness, health & wellness company powered by technology
              committed to finding the most inspiring businesses, events, activities, classes and
              passes in an effort to inspire fitness, health & happiness globally.`;

export const FOOTER_SOCIAL_LINKS = [
  {
    platform: "facebook",
    url: "https://www.facebook.com/coredirection",
    icon: FACEBOOK_ICON,
  },
  {
    platform: "instagram",
    url: "https://www.instagram.com/coredirection/",
    icon: INSTAGRAM_ICON,
  },
  {
    platform: "linkedin",
    url: "https://www.linkedin.com/company/coredirection/",
    icon: LINKEDIN_ICON,
  },
  {
    platform: "youtube",
    url: "https://www.youtube.com/@coredirectionDXB",
    icon: YOUTUBE_ICON,
  },
];

export const FOOTER_LINKS = [
  {
    title: "Company",
    links: [
      {
        title: "Home",
        url: "/",
        icon: "",
        children: null,
        isExternal: false,
      },
      {
        title: "About Us",
        url: "",
        icon: DOWN_ARROW,
        children: [
          {
            title: "Consumer Solutions",
            url: "",
            icon: "",
          },
          {
            title: "Corporate Wellness Solutions",
            url: "/corporate-wellness",
            icon: "",
            isExternal: false,
          },
        ],
      },
      {
        title: "Contact Us",
        url: EXTERNAL_LINKS.CONTACT_US,
        icon: "",
        isExternal: true,
      },
    ],
  },
  {
    title: "Explore",
    links: [
      {
        title: "Locations",
        url: "/listing",
        icon: "",
        children: null,
        isExternal: false,
      },
      {
        title: "Activity Listings",
        url: "/activity-listing",
        icon: "",
        children: null,
        isExternal: false,
      },
      {
        title: "Partner with Us",
        url: "/our-partner",
        icon: "",
        children: null,
        isExternal: false,
      },
    ],
  },
  {
    title: "FAQ & Legal",
    links: [
      {
        title: "User Guides",
        url: EXTERNAL_LINKS.USER_GUIDES,
        icon: "",
        children: null,
      },
      {
        title: "Privacy Policy",
        url: EXTERNAL_LINKS.PRIVACY_POLICY,
        icon: "",
        isExternal: true,
        children: null,
      },
      {
        title: "Terms & Conditions",
        url: EXTERNAL_LINKS.TERMS_AND_CONDITIONS,
        icon: "",
        isExternal: true,
        children: null,
      },
    ],
  },
];

const currentYear = new Date().getFullYear();
export const COPYRIGHT_TEXT =
  " Copyright© (" +
  currentYear +
  "). Core Direction Holding Ltd. All right reserved";
