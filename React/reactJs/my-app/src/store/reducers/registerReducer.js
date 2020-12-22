const initialState = [
    {
        id: 1,
        name: "Bilal Afzal",
        gender: "Male",
    },
    {
        id: 2,
        name: "Abdul Majid",
        gender: "Male",
    },
    {
        id: 3,
        name: "Minahil Bilal",
        gender: "female",
    },
];

const registerReducer = (state = initialState, action) => {
    switch (action.type) {
        case "UNREGISTER_USER":
            state = JSON.parse(JSON.stringify(state));
            state = removeUser(state, action.payload);
            break;
        case "REGISTER_LIST":
            state = JSON.parse(JSON.stringify(state));
            state = InsertUser(state, action.payload);
            break;
        default:
            break;
    }
    return state;
};

export default registerReducer;

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