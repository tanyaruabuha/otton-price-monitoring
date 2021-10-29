let mutations = {
    SET_ALL_PRODUCT_ID(state, allProductId) {
        return state.allProductId = allProductId
    },
    ADD_ERROR(state, error) {
        return state.errors.push(error)
    },
    SET_PRODUCTS(state, products) {
        return state.products = products;
    }
};
export default mutations
