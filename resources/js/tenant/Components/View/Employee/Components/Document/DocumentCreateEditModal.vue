<template>
    <modal id="document-modal"
           v-model="showModal"
           :title="selectedUrl ? $t('edit_document') : $t('add_document')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">
        <form ref="form" :data-url="selectedUrl ? selectedUrl : apiUrl.DOCUMENTS">
            <app-form-group
                :label="$t('name')"
                v-model="formData.name"
                type="text"
                :placeholder="$placeholder('name', '')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />
          <app-form-group
              :label="$t('Expiry Date')"
              v-model="formData.expiry_date"
              type="date"
              :placeholder="$placeholder('expiry date', '')"
              :required="true"
              :error-message="$errorMessage(errors, 'expiry_date')"
          />

            <app-form-group
                :label="$t('document')"
                type="file"
                v-model="formData.file"
                :placeholder="$placeholder('document', '')"
                :file-label="$placeholder('document', '')"
                :error-message="$errorMessage(errors, 'file')"
            >
                <template slot="suggestion">
                    <small class="text-muted font-italic mt-3 d-inline-block">
                        {{ $t('document_recommendation') }}
                    </small>
                </template>
            </app-form-group>
        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../../../common/Mixin/Global/FormHelperMixins";
import {formDataAssigner} from "../../../../../../common/Helper/Support/FormHelper";
import moment from "moment-timezone";
import {
  formatDateForServer,
  formatDateToLocal,
  serverDateTimeFormat
} from "../../../../../../common/Helper/Support/DateTimeHelper";

export default {
    name: "DocumentCreateEditModal",
    mixins: [ModalMixin, FormHelperMixins],
    props: {
        userId: {
            required: true
        }
    },
    data() {
        return {}
    },
    methods: {
        submitData() {
            let formData = formDataAssigner(new FormData, this.formData);
            formData.append('user_id', this.userId);
          let expdate = formatDateForServer(formData.get('expiry_date'));
          formData.append('expiry_date', expdate);
            if (this.selectedUrl) {
                // for file update need to send by post.
                formData.append('_method', 'PATCH');
            }
            let url = this.$refs.form.dataset["url"];

            this.submitFromFixin('post', url, formData);
        },
        afterSuccess({data}) {
            this.toastAndReload(data.message, 'documents-table');
            this.formData = {};
            $('#document-modal').modal('hide');
            this.$emit('input', false);
        },
        afterSuccessFromGetEditData({data}) {
            this.formData = data;
            this.formData.expiry_date =  data?.expiry_date ? new Date(data?.expiry_date) : null;
            this.formData.file = '';
            this.preloader = false;
        },
    },
}
</script>

<style scoped>
</style>