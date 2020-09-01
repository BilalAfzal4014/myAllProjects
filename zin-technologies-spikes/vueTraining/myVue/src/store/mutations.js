export default{
	registerUser(state, id){
		for(var i = 0 ; i < state.users.length ; i++){
		 	if(state.users[i].id == id){
		 		state.users[i].registered = true;
		 		var obj = {
		 			id: state.users[i].id,
		 			name: state.users[i].name,
		 			registered: state.users[i].registered
		 		};
		 		state.registrations.push(obj);
		 	}		 		
		 }
	},
	unregisterUsers(state,id){
		for(var i = 0 ; i < state.users.length ; i++){
		 	if(state.users[i].id == id){
		 		state.users[i].registered = false;
		 	}		 		
		 }
			
		for(var i = 0 ; i < state.registrations.length ; i++){
		 	if(state.registrations[i].id == id){
		 		state.registrations.splice(i,1);
		 		break;
		 	}		 		
		 }
	},
	setUser(state,obj){
		localStorage.setItem('store', JSON.stringify(obj)); // convert obj to string
		// local storage mein store karwa kar state k object mein isliye nahe store karwaya kion k refresh per khatam ho jati hai value		
	},
	logOut(){
		localStorage.removeItem('store');
	}
}