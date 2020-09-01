export default {
	storeMutations_saveCredentails(state, obj){
		state.userObj = obj;
		localStorage.setItem('userObj', JSON.stringify(obj));
	},
	storeMutations_logOut(state){
		state.userObj = {
			id: null,
        	userId: null,
        	Authorization: null
		};
		localStorage.removeItem('userObj');
	}
};