from pathlib import Path

p = Path(r"d:/xampp/htdocs/storage-keys-latest/public/js/core.js")
text = p.read_text(encoding="utf-8")

old = "var formatUpcomingWorkingShift = this.employee.upcoming_working_shift.map(function (item) {"
new = "var formatUpcomingWorkingShift = (this.employee.upcoming_working_shift || []).map(function (item) {"
c1 = text.count(old)
print("map matches", c1)
if c1:
    text = text.replace(old, new)

old2 = "this.employee.working_shifts_with_upcoming = formatUpcomingWorkingShift.concat(this.employee.working_shifts);"
new2 = "this.employee.working_shifts_with_upcoming = formatUpcomingWorkingShift.concat(this.employee.working_shifts || []);"
c2 = text.count(old2)
print("concat matches", c2)
if c2:
    text = text.replace(old2, new2)

p.write_text(text, encoding="utf-8")
print("done")
