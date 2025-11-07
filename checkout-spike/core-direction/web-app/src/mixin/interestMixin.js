import { getInterestList } from "@/apiManager/interest";

const interestMixin = {
    data() {
        return {
            interestLists: [],
        };
    },
    mounted() {
        getInterestList()
            .then((response) => {
                let interestObject = response.data;
                interestObject.sort((a, b) =>
                    a.name.toUpperCase() > b.name.toUpperCase() ? 1 : -1
                );
                this.interestLists = interestObject.map((interest) => ({
                    ...interest,
                    isInterestSelected: false,
                }));
            })
            .catch((error) => toastr.error(error));
    },
};

export default interestMixin;
