const followStatusMixin = {
    computed: {
        buttonLabel() {
            const {status, isFriend, friendship, privacy} = this.user;
            if (privacy === "private" && status === "requested") {
                return "Request Sent";
            }
            if (status === "unknown" || (friendship === "follower" && !isFriend)) {
                return privacy === "public" ? "Follow" : "Send Request";
            }


            if (status === "accepted" && isFriend) {
                return "Following";
            }

            return "Following";
        },
    },
};
export default followStatusMixin;
