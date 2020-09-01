
let userRegistration = {
    signUp(data, callback) {
        let users = localStorage.getItem("registered_users");

        if (users == null) {
            users = [];
        } else {
            users = JSON.parse(users);
        }

        users.push(data);
        localStorage.setItem("registered_users", JSON.stringify(users));

        callback();
    },
    fetchUsersList(callback) {
        let users = localStorage.getItem("registered_users");
        callback(users == null ? [] : JSON.parse(users));
    }
};

export default userRegistration;