let getters = {
    allProductId: state => {
        return state.allProductId
    },
    getErrors : state => {
        return state.errors
    },
    getProducts: state => {
        return state.products
    }
};

export default  getters
