let actions = {
    getAllProductId({commit}) {
        return axios.post('/api/truncate-and-get-all-product-id')
            .then(res => {
                commit('SET_ALL_PRODUCT_ID', res.data)
            }).catch(err => {
            console.log(err)
        })
    },
    addProduct({commit}, request) {
        return axios.post('/api/add-product', request)
            .catch(err => {
                console.log(err);
                commit('ADD_ERROR', err);
            })
    },
    getProducts({commit}) {
        return axios.get('/api/get-all-products')
            .then(res => {
                commit('SET_PRODUCTS', res.data)
            }).catch(err => {
                console.log(err)
            })
    },

}

export default actions
