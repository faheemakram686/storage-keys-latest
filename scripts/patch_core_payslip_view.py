from pathlib import Path

p = Path(r"d:\xampp\htdocs\storage-keys-latest\public\js\core.js")
text = p.read_text(encoding="utf-8")
old = """      if (action.actionName === 'view') {
        this.selectedPayslip = row;
        this.payslipViewModal = true;
"""
new = """      if (action.actionName === 'view') {
        this.selectedPayslip = row;
        if (window.__openClassicPayslip) { window.__openClassicPayslip(row.id); } else { this.payslipViewModal = true; }
"""
count = text.count(old)
print("matches", count)
if count:
    p.write_text(text.replace(old, new), encoding="utf-8")
    print("patched")
else:
    print("NOT FOUND")
