const initial_state = {};

const tokenReducer = (state = initial_state, action) => {
    let token = localStorage.getItem('token'); //we dont need to do this if we use GET_TOKEN action in app.js component constructor or componentDidMount event and get the token and we will write token = null instead,
    switch (action.type) {
        case "SAVE_TOKEN":
            localStorage.setItem('token', action.payload);
            token = action.payload;
            break;
        case "REMOVE_TOKEN":
            localStorage.removeItem('token');
            token = null;
            break;
        case "GET_TOKEN":
            token = localStorage.getItem('token'); // we dont need get token case bcz we are already getting token in all cases in app component, but if we use this case anyway then we dont need mapStateToProps function in app.js component
            break;
        default:
            break;
    }
    return token;
};

export default tokenReducer;
