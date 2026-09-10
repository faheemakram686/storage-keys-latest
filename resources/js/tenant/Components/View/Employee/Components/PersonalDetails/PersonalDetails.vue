<template>
    <div>
        <app-overlay-loader v-if="preloader"/>
        <form v-else ref="form">
            <app-form-group
                page="page"
                :label="$t('first_name')"
                type="text"
                v-model="formData.first_name"
                :placeholder="$placeholder('first_name','')"
                :error-message="$errorMessage(errors, 'first_name')"
            />
            <app-form-group
                page="page"
                :label="$t('last_name')"
                type="text"
                v-model="formData.last_name"
                :placeholder="$placeholder('last_name','')"
                :error-message="$errorMessage(errors, 'last_name')"
            />
            <app-form-group
                page="page"
                :label="$t('email')"
                type="text"
                v-model="formData.email"
                :placeholder="$placeholder('email','')"
                :error-message="$errorMessage(errors, 'email')"
            />
            <app-form-group
                page="page"
                :label="$t('employee_id')"
                type="text"
                v-model="formData.employee_id"
                :placeholder="$placeholder('employee_id','')"
                :error-message="$errorMessage(errors, 'employee_id', true, true)"
            />
          <app-form-group
              page="page"
              :label="$t('res_visa_loc')"
              type="text"
              v-model="formData.res_visa_loc"
              :placeholder="$placeholder('res_visa_loc','')"
              :error-message="$errorMessage(errors, 'res_visa_loc')"
          />
          <app-form-group
              page="page"
              :label="$t('emirate_id')"
              type="text"
              v-model="formData.emirate_id"
              :placeholder="$placeholder('emirate_id','')"
              :error-message="$errorMessage(errors, 'emirate_id')"
          />
          <app-form-group
              page="page"
              :label="$t('notice_period')"
              type="number"
              v-model="formData.notice_period"
              :placeholder="$placeholder('notice_period','')"
              :error-message="$errorMessage(errors, 'notice_period')"
          />
            <app-form-group
                page="page"
                :label="$t('phone_number')"
                type="tel-input"
                v-model="formData.phone_number"
                :placeholder="$placeholder('phone_number','')"
                :error-message="$errorMessage(errors, 'phone_number')"
            />
            <app-form-group
                page="page"
                :label="$t('gender')"
                type="radio"
                :list="[
                {id:'male',value: $t('male')},
                {id:'female', value:  $t('female')},
                {id:'other', value:  $t('others')}
            ]"
                v-model="formData.gender"
                :error-message="$errorMessage(errors, 'gender')"

            />
            <app-form-group
                page="page"
                :label="$t('birthday')"
                type="date"
                v-model="formData.date_of_birth"
                :placeholder="$placeholder('date_of_birth','')"
                :error-message="$errorMessage(errors, 'date_of_birth')"
            />
            <app-form-group
                page="page"
                :label="$t('about_me')"
                type="textarea"
                v-model="formData.about_me"
                :placeholder="$textAreaPlaceHolder('about_me','')"
                :error-message="$errorMessage(errors, 'about_me')"
            />
            <div class="form-group mt-5 mb-0">
                <app-submit-button @click="submitData" :title="$t('save')" :loading="loading"/>
            </div>
        </form>
    </div>

</template>
<script>
import FormHelperMixins from "../../../../../../common/Mixin/Global/FormHelperMixins";
import {formatDateForServer} from "../../../../../../common/Helper/Support/DateTimeHelper";
import {mapState} from "vuex";
import {EMPLOYEES} from "../../../../../Config/ApiUrl";
import optional from "../../../../../../common/Helper/Support/Optional";

export default {
    name: "EmployeePersonalDetails",
    mixins: [FormHelperMixins],
    data() {
        return {
            formData: {},
            preloader: true
        }
    },
    methods: {
        submitData() {
            this.loading = true;
            // Always use the loaded employee user id (not nested profile id).
            const employeeId = this.employeeDetails?.id || this.formData.id;
            const formData = {
                id: employeeId,
                first_name: this.formData.first_name,
                last_name: this.formData.last_name,
                email: this.formData.email,
                employee_id: this.formData.employee_id,
                res_visa_loc: this.formData.res_visa_loc,
                emirate_id: this.formData.emirate_id,
                notice_period: this.formData.notice_period,
                phone_number: this.formData.phone_number,
                gender: this.formData.gender ? String(this.formData.gender).toLowerCase() : '',
                date_of_birth: formatDateForServer(this.formData.date_of_birth),
                about_me: this.formData.about_me,
            };
            this.submitFromFixin(`patch`, `${EMPLOYEES}/${employeeId}/profile-update`, formData);
        },
        afterSuccess(response) {
            this.loading = false;
            this.$toastr.s('', response.data.message);
            this.scrollToTop(false)
            setTimeout(() => location.reload())
        },
    },

    computed: {
        ...mapState({
            employeeDetails: state => state.employees.employee
        }),

    },

    watch: {
        employeeDetails: {
            handler: function (employee) {
                if (!!Object.keys(employee).length) {
                    this.preloader = false
                }
                this.formData = {
                    id: employee.id,
                    first_name: employee.first_name,
                    last_name: employee.last_name,
                    email: employee.email,
                    employee_id: employee.profile ? employee.profile.employee_id : '',
                    gender: employee.profile?.gender ? String(employee.profile.gender).toLowerCase() : '',
                    about_me: employee.profile ? employee.profile.about_me : '',
                    phone_number: employee.profile ? employee.profile.phone_number : '',
                    res_visa_loc: employee.profile ? employee.profile.res_visa_loc : '',
                    emirate_id: employee.profile ? employee.profile.emirate_id : '',
                    notice_period: employee.profile ? employee.profile.notice_period : '',
                    date_of_birth: optional(employee, 'profile', 'date_of_birth') ? new Date(employee.profile.date_of_birth) : ''
                }
            },
            deep: true,
            immediate: true
        }

    }
}
</script>