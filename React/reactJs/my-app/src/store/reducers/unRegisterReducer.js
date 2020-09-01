const initial_state = [];

const unRegisterReducer = (state = initial_state, action) => {
    switch (action.type) {
        case "REGISTER_USER":
            state = JSON.parse(JSON.stringify(state));
            state = removeUser(state, action.payload);
            break;
        case "UNREGISTER_LIST":
            state = JSON.parse(JSON.stringify(state));
            state = InsertUser(state, action.payload);
            break;
        default:
            break;
    }
    return state;
};

export default unRegisterReducer;

function removeUser(state, user) {
    for (let i = 0; i < state.length; i++) {
        if (state[i].id == user.id) {
            state.splice(i, 1);
            break;
        }
    }
    return state;
}

function InsertUser(state, user) {
    state.push(user);
    return state;
}