from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
t = p.read_text(encoding="utf-8")
needle = 'concat(this.formData.id, "/profile-update")'
replacement = 'concat((this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id), "/profile-update")'
count = t.count(needle)
print("matches", count)
if count:
    p.write_text(t.replace(needle, replacement), encoding="utf-8")
    print("patched")
