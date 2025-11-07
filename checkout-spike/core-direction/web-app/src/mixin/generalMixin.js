const generalMixin = {
    methods: {
        convertIS8601ToTime(date) {
            return new Date(date).toLocaleTimeString("en", {
                timeStyle: "short",
                hour12: false,
                timeZone: "UTC",
            });
        },
    },
};

export default generalMixin;
