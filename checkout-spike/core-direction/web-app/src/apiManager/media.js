import api from "@/apiManager/apiManager";

export const uploadMedia = (data) => {
    const url = "/upload-file";
    return api("post", url, data, "media", {
        "Content-Type": "multipart/form-data",
    });
};
