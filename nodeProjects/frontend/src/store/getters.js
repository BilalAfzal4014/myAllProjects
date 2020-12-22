export default {
	getUserObj(state){
		var obj = JSON.parse(localStorage.getItem('userObj'));
		if(obj != null)
		{
			return state.userObj = obj;
		}
		return null;
	},
	getSocket(state){
		return state.socket;
	}
};