from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
t = p.read_text(encoding="utf-8")

old = (
    '      this.loading = true;\n'
    '      var formData = _objectSpread({}, this.formData);\n'
    '      formData.date_of_birth = (0,_common_Helper_Support_DateTimeHelper__WEBPACK_IMPORTED_MODULE_1__.formatDateForServer)(formData.date_of_birth);\n'
    '      this.submitFromFixin("patch", "".concat(_Config_ApiUrl__WEBPACK_IMPORTED_MODULE_2__.EMPLOYEES, "/").concat((this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id), "/profile-update"), formData);'
)

new = (
    '      this.loading = true;\n'
    '      var employeeId = this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id;\n'
    '      var formData = {\n'
    '        id: employeeId,\n'
    '        first_name: this.formData.first_name,\n'
    '        last_name: this.formData.last_name,\n'
    '        email: this.formData.email,\n'
    '        employee_id: this.formData.employee_id,\n'
    '        res_visa_loc: this.formData.res_visa_loc,\n'
    '        emirate_id: this.formData.emirate_id,\n'
    '        notice_period: this.formData.notice_period,\n'
    '        phone_number: this.formData.phone_number,\n'
    "        gender: this.formData.gender ? String(this.formData.gender).toLowerCase() : '',\n"
    '        date_of_birth: (0,_common_Helper_Support_DateTimeHelper__WEBPACK_IMPORTED_MODULE_1__.formatDateForServer)(this.formData.date_of_birth),\n'
    '        about_me: this.formData.about_me\n'
    '      };\n'
    '      this.submitFromFixin("patch", "".concat(_Config_ApiUrl__WEBPACK_IMPORTED_MODULE_2__.EMPLOYEES, "/").concat(employeeId, "/profile-update"), formData);'
)

start = t.find('name: "EmployeePersonalDetails"')
end = t.find("\n/***/", start + 1)
section = t[start:end]
print("found old in section:", old in section)
if old in section:
    t = t[:start] + section.replace(old, new, 1) + t[end:]
    p.write_text(t, encoding="utf-8")
    print("patched PersonalDetails submit")
else:
    print("FAILED to find submit block")
