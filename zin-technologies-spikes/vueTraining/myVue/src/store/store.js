import Vue from 'vue';
import Vuex from 'vuex';
import actions from './actions';
import setters from './mutations';
import getters from './getters';

Vue.use(Vuex);

const store = new Vuex.Store({
	state:{
		userObj: [],
		registrations: [],
		users: [
			{
				id: 1,
				name: 'Amna Bilal',
				registered: false		
			},
			{
				id: 2,
				name: 'Bilal Afzal',
				registered: false			
			},
			{
				id: 3,
				name: 'Mustafa Bilal',
				registered: false
			}
		]
	},
	getters,  // alternate syntax
	mutations: setters,
	actions: actions
});

export default store;