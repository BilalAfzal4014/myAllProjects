const initialState = [
    /*{
        id: 1,
        name: "John Doe",
        gender: "Male",
    },
    {
        id: 2,
        name: "Francis Nagannou",
        gender: "Male",
    }*/
];

const sampleReducer = (state = initialState, action) => {

    switch (action.type) {
        case "INSERT_USER":
            state = [...state, action.payLoad];
            break;
        case "REMOVE_USER":
            let index = 0;
            for (let user of state) {
                if (user.id === action.payLoad.id) {
                    let newState = [...state];
                    newState.splice(index, 1);
                    state = newState;
                }
                index++;
            }
            break
    }

    return state;
}

export default sampleReducer;