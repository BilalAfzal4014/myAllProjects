export default {
	storeAction_saveCredentails(context, obj){
		context.commit("storeMutations_saveCredentails", obj);
	},
	storeAction_logOut(context){
		context.commit('storeMutations_logOut');
	}
};