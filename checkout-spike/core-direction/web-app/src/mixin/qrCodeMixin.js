import * as toastr from "toastr";

const qrCodeMixin = {
    data() {
        return {
            username: "",
            qrCode: "",
        };
    },
    created() {
        this.oldApi("get", this.constants.getUrl("getProfile"))
            .then((response) => {
                this.qrCode = response.data.qr_code;
            })
            .catch((error) => {
                toastr.error(error.message);
            });
    },
};
export default qrCodeMixin;
