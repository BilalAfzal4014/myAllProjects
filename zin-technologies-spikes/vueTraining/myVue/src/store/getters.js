export default{
	usersGetter(state){	// state will pass automatically
		 var unregisterUsers = [];
		 for(var i = 0 ; i < state.users.length ; i++){
		 	if(!state.users[i].registered){
		 		var obj = {
		 			id: state.users[i].id,
		 			name: state.users[i].name,
		 			registered: state.users[i].registered
		 		};
		 	unregisterUsers.push(obj);	
		 	}
		}
		return unregisterUsers;
	},
	registerUsersGetters(state){
		return state.registrations;
	},
	totalRegistrations(state){
		return state.registrations.length;
	},
	getUser(state){
		state.userObj = JSON.parse(localStorage.getItem('store'));
		return state.userObj; 
	},
	getUserName(state){
		return JSON.parse(localStorage.getItem('store'));
	}
}