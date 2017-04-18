import Vue from 'vue';
import Resource from 'vue-resource';
// import router from '../app_config/route-config';
import { LOGIN, LOGOUT, VALIDATE_ERROR, PLATFORM } from './mutation-types';

Vue.use(Resource);
const client = Vue.http;
import { host } from '../app_config/base_config'
export const login = ({ commit }, payload) => {
    console.log('payload=>')
    console.log(typeof payload)
    console.log(payload)
    // client.post(host + '/api/login', payload)
    // return new Promise((resolve, reject) => {
        client.jsonp('http://localhost:5001', payload).then((response) => {
            console.log(response);
            console.log(response.body.data.name);
            console.log(response.body.data.token);
            commit(LOGIN, {
                name: response.body.data.name,
                token: response.body.data.token,
            });
            // resolve(123);

            // router.push({ name: 'index' });
        }, (response) => {
            commit(VALIDATE_ERROR, [response.data.error]);
        });
    // })
};

export const register = ({ commit }, payload) => {
    client.post(host + '/api/register', payload).then((response) => {
        commit(LOGIN, {
            name: response.data.name,
            token: response.data.token,
        });
        // router.push({ name: 'profile' });
    }, (response) => {
        commit(VALIDATE_ERROR, response.data.errors);
    });
};

export const logout = ({ commit }) => {
    commit(LOGOUT);
};
export const setApp = ({commit}, payload) => {
    commit(PLATFORM, payload);
}
