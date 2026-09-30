from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
t = p.read_text(encoding="utf-8")
old = "var employeeId = this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id;"
new = (
    "var routeEmployee = this.$route && this.$route.params ? this.$route.params.employee : null;\n"
    "      var employeeId = routeEmployee || (this.employeeDetails && this.employeeDetails.id ? this.employeeDetails.id : this.formData.id);"
)
count = t.count(old)
print("count", count)
if count != 1:
    raise SystemExit(1)
p.write_text(t.replace(old, new), encoding="utf-8")
print("patched ok", p.stat().st_size)
