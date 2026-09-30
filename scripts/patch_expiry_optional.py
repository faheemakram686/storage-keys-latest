from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
text = p.read_text(encoding="utf-8")

# Make Expiry Date optional in DocumentCreateEditModal render attrs
old_attrs = '''label: _vm.$t("Expiry Date"),
              type: "date",
              placeholder: _vm.$placeholder("expiry date", ""),
              required: true,
              "error-message": _vm.$errorMessage(_vm.errors, "expiry_date"),'''

new_attrs = '''label: _vm.$t("Expiry Date"),
              type: "date",
              placeholder: _vm.$placeholder("expiry date", ""),
              required: false,
              "error-message": _vm.$errorMessage(_vm.errors, "expiry_date"),'''

count = text.count(old_attrs)
print("attrs matches", count)
if count:
    text = text.replace(old_attrs, new_attrs)

# Also handle minified/single-line variant
old2 = 'label: _vm.$t("Expiry Date"), type: "date", placeholder: _vm.$placeholder("expiry date", ""), required: !0'
new2 = 'label: _vm.$t("Expiry Date"), type: "date", placeholder: _vm.$placeholder("expiry date", ""), required: !1'
c2 = text.count(old2)
print("minified matches", c2)
if c2:
    text = text.replace(old2, new2)

# Patch submitData in DocumentCreateEditModal compiled script
old_submit = """      formData.append('user_id', this.userId);
      var expdate = (0,_common_Helper_Support_DateTimeHelper__WEBPACK_IMPORTED_MODULE_4__.formatDateForServer)(formData.get('expiry_date'));
      formData.append('expiry_date', expdate);"""

new_submit = """      formData.append('user_id', this.userId);
      var rawExpiry = formData.get('expiry_date');
      if (rawExpiry) {
        formData.append('expiry_date', (0,_common_Helper_Support_DateTimeHelper__WEBPACK_IMPORTED_MODULE_4__.formatDateForServer)(rawExpiry));
      } else {
        formData.set('expiry_date', '');
      }"""

c3 = text.count(old_submit)
print("submit matches", c3)
if c3:
    text = text.replace(old_submit, new_submit)

p.write_text(text, encoding="utf-8")
print("done")
