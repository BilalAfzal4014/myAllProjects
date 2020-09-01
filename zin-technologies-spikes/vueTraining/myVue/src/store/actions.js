export default{
	
	register(context,id){ //context name can vary but it will pass by default
		context.commit('registerUser',id);
	},
	unRegister(context,id){
		context.commit('unregisterUsers',id);
	},
	setUserAction(context,obj){
		context.commit('setUser',obj);
	},
	logOutAction(context){
		context.commit('logOut');
	}
}