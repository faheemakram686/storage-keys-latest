from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
original_size = p.stat().st_size
t = p.read_text(encoding="utf-8", errors="strict")
print("loaded", len(t), "chars, file", original_size)

# 1) CreateEditModal
old1 = """      this.formData = _objectSpread(_objectSpread({}, this.formData), data);
      this.formData.employee_id = (_data$profile = data.profile) === null || _data$profile === void 0 ? void 0 : _data$profile.employee_id;
      this.formData.roles = this.collection(data.roles).pluck();
      this.formData.designation_id = (_data$designation = data.designation) === null || _data$designation === void 0 ? void 0 : _data$designation.id;
      this.formData.department_id = (_data$department = data.department) === null || _data$department === void 0 ? void 0 : _data$department.id;
      this.formData.employment_status_id = (_data$employment_stat = data.employment_status) === null || _data$employment_stat === void 0 ? void 0 : _data$employment_stat.id;
      this.formData.dont_show_in_employee = parseInt(this.formData.is_in_employee) ? 0 : 1;
      this.formData.joining_date = (_data$profile2 = data.profile) !== null && _data$profile2 !== void 0 && _data$profile2.joining_date ? new Date((_data$profile3 = data.profile) === null || _data$profile3 === void 0 ? void 0 : _data$profile3.joining_date) : null;
      this.formData.gender = (_data$profile4 = data.profile) === null || _data$profile4 === void 0 ? void 0 : _data$profile4.gender;"""

new1 = """      this.formData = _objectSpread(_objectSpread({}, this.formData), data);
      this.formData.id = data.id;
      this.formData.employee_id = (_data$profile = data.profile) === null || _data$profile === void 0 ? void 0 : _data$profile.employee_id;
      this.formData.roles = this.collection(data.roles).pluck();
      this.formData.designation_id = (_data$designation = data.designation) === null || _data$designation === void 0 ? void 0 : _data$designation.id;
      this.formData.department_id = (_data$department = data.department) === null || _data$department === void 0 ? void 0 : _data$department.id;
      this.formData.employment_status_id = (_data$employment_stat = data.employment_status) === null || _data$employment_stat === void 0 ? void 0 : _data$employment_stat.id;
      this.formData.dont_show_in_employee = parseInt(this.formData.is_in_employee) ? 0 : 1;
      this.formData.joining_date = (_data$profile2 = data.profile) !== null && _data$profile2 !== void 0 && _data$profile2.joining_date ? new Date((_data$profile3 = data.profile) === null || _data$profile3 === void 0 ? void 0 : _data$profile3.joining_date) : null;
      this.formData.gender = (_data$profile4 = data.profile) !== null && _data$profile4 !== void 0 && _data$profile4.gender ? String(data.profile.gender).toLowerCase() : '';"""

c1 = t.count(old1)
print("CreateEditModal", c1)
if c1 == 1:
    t = t.replace(old1, new1)

# 2) InviteEditModal
old2 = """      this.formData = data;
      this.formData.employee_id = (_data$profile = data.profile) === null || _data$profile === void 0 ? void 0 : _data$profile.employee_id;
      this.formData.roles = this.collection(data.roles).pluck();
      this.formData.designation_id = (_data$designation = data.designation) === null || _data$designation === void 0 ? void 0 : _data$designation.id;
      this.formData.department_id = (_data$department = data.department) === null || _data$department === void 0 ? void 0 : _data$department.id;
      this.formData.employment_status_id = (_data$employment_stat = data.employment_status) === null || _data$employment_stat === void 0 ? void 0 : _data$employment_stat.id;
      this.formData.dont_show_in_employee = parseInt(this.formData.is_in_employee) ? 0 : 1;
      this.formData.joining_date = (_data$profile2 = data.profile) !== null && _data$profile2 !== void 0 && _data$profile2.joining_date ? new Date((_data$profile3 = data.profile) === null || _data$profile3 === void 0 ? void 0 : _data$profile3.joining_date) : null;
      this.formData.gender = (_data$profile4 = data.profile) === null || _data$profile4 === void 0 ? void 0 : _data$profile4.gender;"""

new2 = """      this.formData = data;
      this.formData.id = data.id;
      this.formData.employee_id = (_data$profile = data.profile) === null || _data$profile === void 0 ? void 0 : _data$profile.employee_id;
      this.formData.roles = this.collection(data.roles).pluck();
      this.formData.designation_id = (_data$designation = data.designation) === null || _data$designation === void 0 ? void 0 : _data$designation.id;
      this.formData.department_id = (_data$department = data.department) === null || _data$department === void 0 ? void 0 : _data$department.id;
      this.formData.employment_status_id = (_data$employment_stat = data.employment_status) === null || _data$employment_stat === void 0 ? void 0 : _data$employment_stat.id;
      this.formData.dont_show_in_employee = parseInt(this.formData.is_in_employee) ? 0 : 1;
      this.formData.joining_date = (_data$profile2 = data.profile) !== null && _data$profile2 !== void 0 && _data$profile2.joining_date ? new Date((_data$profile3 = data.profile) === null || _data$profile3 === void 0 ? void 0 : _data$profile3.joining_date) : null;
      this.formData.gender = (_data$profile4 = data.profile) !== null && _data$profile4 !== void 0 && _data$profile4.gender ? String(data.profile.gender).toLowerCase() : '';"""

c2 = t.count(old2)
print("InviteEditModal", c2)
if c2 == 1:
    t = t.replace(old2, new2)

# 3) PersonalDetails submit — only inside that component section
start = t.find('name: "EmployeePersonalDetails"')
end = t.find("\n/***/", start + 1)
assert start > 0 and end > start
section = t[start:end]

old3 = (
    '      this.loading = true;\n'
    '      var formData = _objectSpread({}, this.formData);\n'
    '      formData.date_of_birth = (0,_common_Helper_Support_DateTimeHelper__WEBPACK_IMPORTED_MODULE_1__.formatDateForServer)(formData.date_of_birth);\n'
    '      this.submitFromFixin("patch", "".concat(_Config_ApiUrl__WEBPACK_IMPORTED_MODULE_2__.EMPLOYEES, "/").concat((this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id), "/profile-update"), formData);'
)
# HEAD may still have the employeeDetails ternary from earlier commit
old3b = (
    '      this.loading = true;\n'
    '      var formData = _objectSpread({}, this.formData);\n'
    '      formData.date_of_birth = (0,_common_Helper_Support_DateTimeHelper__WEBPACK_IMPORTED_MODULE_1__.formatDateForServer)(formData.date_of_birth);\n'
    '      // Always use the loaded employee user id (not nested profile id).\n'
    '      this.submitFromFixin("patch", "".concat(_Config_ApiUrl__WEBPACK_IMPORTED_MODULE_2__.EMPLOYEES, "/").concat((this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id), "/profile-update"), formData);'
)

new3 = (
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

if old3b in section:
    section = section.replace(old3b, new3, 1)
    print("PersonalDetails submit patched (with comment)")
elif old3 in section:
    section = section.replace(old3, new3, 1)
    print("PersonalDetails submit patched")
else:
    print("WARN: PersonalDetails submit not found")
    i = section.find("submitData")
    print(repr(section[i:i+500]))

old_g = "gender: employee.profile ? employee.profile.gender : '',"
new_g = "gender: employee.profile && employee.profile.gender ? String(employee.profile.gender).toLowerCase() : '',"
if old_g in section:
    section = section.replace(old_g, new_g, 1)
    print("PersonalDetails gender patched")

t = t[:start] + section + t[end:]

# Write via temp then replace to avoid truncation
tmp = p.with_suffix(".js.tmp")
tmp.write_text(t, encoding="utf-8", newline="\n")
new_size = tmp.stat().st_size
print("new size", new_size, "old", original_size, "delta", new_size - original_size)
if new_size < original_size * 0.9:
    raise SystemExit("Refusing to write: file shrank too much")
tmp.replace(p)
print("ok", p.stat().st_size)
