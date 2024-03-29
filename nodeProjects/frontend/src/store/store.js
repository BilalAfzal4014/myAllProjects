import Vue from 'vue';
import Vuex from 'vuex';
import getters from './getters';
import mutations from './mutations';
import actions from './actions';

Vue.use(Vuex);

const store = new Vuex.Store({
    state:{
        userObj:{
        	id: null,
        	userId: null,
        	Authorization: null
        },
        socket: io.connect('http://localhost:3000'),
    },
    getters,
    mutations,
    actions,
});

export default store;
