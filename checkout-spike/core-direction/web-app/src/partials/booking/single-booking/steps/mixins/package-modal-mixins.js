import generalModal from "../../../../modal/general-modal";

const packageModalMixins = {
    components: {
        generalModal
    },
    data() {
        return {
            modal: {
                isModalOpen: false,
                packageDetails: {},
            },
        };
    },
    methods: {
        closeModal() {
            this.modal.isModalOpen = false;
        },
        openModal() {
            this.modal.isModalOpen = true;
        },
        getPackageDescription(details) {
            this.modal.packageDetails = details;
            this.openModal();
        }
    }
};

export default packageModalMixins;