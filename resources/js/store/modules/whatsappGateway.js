import axios from 'axios';

export const whatsappGateway = {
    namespaced: true,
    state: {
        settings: {},
    },
    getters: {
        settings: function (state) {
            return state.settings;
        },
    },
    actions: {
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get('admin/whatsapp-gateway').then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        save: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/whatsapp-gateway', payload).then((res) => {
                    context.commit('lists', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        testConnection: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post('admin/whatsapp-gateway/test-connection').then((res) => {
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.settings = payload;
        },
    },
};
