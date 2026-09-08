<template>
    <modal id="payslip-view-modal"
           size="extra-large"
           v-model="showModal"
           :title="this.$t('payslip')"
           :cancel-btn-label="$t('close')"
           :hide-submit-button="true"
           @submit=""
           :preloader="preloader">
        <iframe
            v-if="payslip && payslip.id"
            :src="previewUrl"
            class="classic-payslip-iframe"
            title="Payslip"
        ></iframe>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {PAYSLIP} from "../../../Config/ApiUrl";

export default {
    name: "PayslipViewModal",
    mixins: [ModalMixin],
    props: {
        payslip: {},
        settings: {}
    },
    computed: {
        previewUrl() {
            return urlGenerator(`${PAYSLIP}/${this.payslip.id}/html`);
        }
    }
}
</script>

<style scoped>
.classic-payslip-iframe {
    width: 100%;
    min-height: 78vh;
    border: 0;
    background: #fff;
}
</style>
