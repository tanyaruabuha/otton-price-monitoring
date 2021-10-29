<template>
    <div class="container">
        <div class="pt-5 d-flex flex-column justify-content-center align-items-center">
            <h2 style="">Update values</h2>
            <b-button class="mt-3" @click="showWarnMsgBox" v-if="buttonVisibility">Update</b-button>
            <p class="mt-3">{{ message }}
                <b-badge variant="danger" class="errors" @click="$bvModal.show('modal-no-backdrop')"
                         v-if="getErrors.length > 0">
                    Errors: {{ getErrors.length }}
                </b-badge>
            </p>
            <b-progress v-show="max !== 0 " :max="max" show-progress animated style="display: block; width: 100%">
                <b-progress-bar :value="value" :label="`${value} / ${max}`"
                                :max="max"></b-progress-bar>
            </b-progress>
            <b-modal id="modal-no-backdrop" hide-footer hide-backdrop content-class="shadow" title="Errors" size="lg">
                <ol>
                    <li v-for="error in getErrors" class="mb-4">{{ error.response.data.message }}</li>
                </ol>
            </b-modal>
        </div>
    </div>
</template>


<script>
    import {mapGetters} from 'vuex'
    import {parallelDispatch} from '../utils.js'

    export default {
        computed: {
            max() {
                return this.allProductId.length;
            },
            ...mapGetters([
                'allProductId', 'getErrors'
            ])
        },
        data() {
            return {
                value: 0,
                message: '',
                buttonVisibility: true,
            }
        },
        methods: {
            updateHandler() {
                this.buttonVisibility = false;
                this.message = 'Processing...';
                return this.$store.dispatch('getAllProductId')
                    .then(() => {
                        this.message = `${this.max} product IDs received`;
                        return parallelDispatch.call(this, 'addProduct', this.allProductId, this.updateValue);
                    })
                    .then(() => {
                        this.message = this.getErrors.length ? `Updated with errors` : 'Successfully updated!';
                    })
            },
            updateValue(count) {
                this.value = count;
            },
            showWarnMsgBox() {
                this.boxOne = '';
                this.$bvModal.msgBoxConfirm('All data will be ERASED from the database! Are you sure?', {
                    title: 'Please Confirm',
                    size: 'sm',
                    buttonSize: 'sm',
                    okVariant: 'danger',
                    okTitle: 'YES',
                    cancelTitle: 'NO',
                    footerClass: 'p-2',
                    hideHeaderClose: false,
                    centered: true
                })
                    .then(value => {
                        if (value) {
                            this.updateHandler();
                        }
                    })
            }
        }
    }
</script>

<style scoped>
    .errors {
        cursor: pointer;
    }
</style>
