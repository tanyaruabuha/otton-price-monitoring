function parallelDispatch(dispatchName, productIdList, updateValue, maxParallel = 1) {
    let count = productIdList.length,
        limit = Math.min(count, maxParallel),
        loaded = 0,
        started = 0;
    return new Promise((res, rej) => {
        for(let i = 0; i < limit; i++) {
            dispatch.call(this, dispatchName, res, rej, productIdList[i]);
        }
    });

    function dispatch(dispatchName, res, rej, productId) {
        started ++;
        this.$store.dispatch(dispatchName, {
            productId: productId
        })
            .then(() => {
                loaded ++;
                updateValue(loaded);
                let nextDispatchId = productIdList[started];
                if(count === loaded) {
                    return res();
                }
                if (nextDispatchId) {
                    dispatch.call(this, dispatchName, res, rej, nextDispatchId);
                }
            })
            .catch((err) => {
                rej(err);
            });
    }
}

export {parallelDispatch}
