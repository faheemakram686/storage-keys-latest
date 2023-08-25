@php
// $color = $settings['theme_color'] ? $settings['theme_color'] : '#47991f';

$primarycolor =  '#f58320'; @endphp

<style>

a:hover {
  color: {{$primarycolor}};
}

.is-light .nk-menu-link:hover, .is-light .active > .nk-menu-link {
  color: {{$primarycolor}};
}

.nk-menu-link:hover, .is-light .active > .nk-menu-link {
  color: {{$primarycolor}};
}

.nk-menu-link:hover .nk-menu-icon, .nk-menu-item.active > .nk-menu-link .nk-menu-icon, .nk-menu-item.current-menu > .nk-menu-link .nk-menu-icon {
  color: {{$primarycolor}};
}
.a_link {
  color: {{$primarycolor}};
}

.link-list a:hover {
  color: {{$primarycolor}};
}

.link-list-opt a:hover {
  color: {{$primarycolor}};
  background: #f5f6fa;
}

.btn-dim.btn-outline-primary {
  color: {{$primarycolor}};
  background-color: {{$primarycolor}}0a;
  border-color: {{$primarycolor}}42;
}

.btn-dim.btn-outline-primary:not(:disabled):not(.disabled):hover {
  color: #fff;
  background-color: {{$primarycolor}};
  border-color: {{$primarycolor}};
}

.btn-primary {
  color: #fff;
  background-color: {{$primarycolor}}d4;
  border-color: {{$primarycolor}}d4;
}

.btn-primary:hover {
  color: #fff;
  background-color: {{$primarycolor}};
  border-color: {{$primarycolor}};
}

.btn-dim.btn-outline-light:not(:disabled):not(.disabled):hover {
  color: #fff;
  background-color: {{$primarycolor}};
  border-color: {{$primarycolor}};
}

.btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active, .show >
.btn-primary.dropdown-toggle {
  color: #fff;
  background-color: {{$primarycolor}}d4;
  border-color: {{$primarycolor}}d4;
}

.btn-outline-primary:not(:disabled):not(.disabled):active, .btn-outline-primary:not(:disabled):not(.disabled).active,
.show > .btn-outline-primary.dropdown-toggle {
  color: #fff;
  background-color: {{$primarycolor}}d4;
  border-color: {{$primarycolor}}d4;
}

.btn-outline-primary:not(:disabled):not(.disabled):active:focus,
.btn-outline-primary:not(:disabled):not(.disabled).active:focus, .show > .btn-outline-primary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0.2rem {{$primarycolor}}40;
}

.btn-primary:not(:disabled):not(.disabled):active:focus, .btn-primary:not(:disabled):not(.disabled).active:focus, .show
> .btn-primary.dropdown-toggle:focus {
  box-shadow: 0 0 0 0.2rem {{$primarycolor}}40;
}

.btn-outline-primary:focus, .btn-outline-primary.focus {
  box-shadow: 0 0 0 0.2rem {{$primarycolor}}40;
}

.form-control:focus, div.dataTables_wrapper div.dataTables_filter input:focus, .dual-listbox .dual-listbox__search:focus, .custom-select:focus, div.dataTables_wrapper div.dataTables_length select:focus {
    border-color: {{$primarycolor}};
    box-shadow: {{ $primarycolor }}1a;
}

.btn-primary:focus, .btn-primary.focus {
  color: #fff;
  background-color: {{$primarycolor}}d4;
  border-color: {{$primarycolor}}d4;
  box-shadow: 0 0 0 0.2rem {{$primarycolor}}40;
}

.btn-primary.disabled, .btn-primary:disabled {
  color: #fff;
  background-color: {{$primarycolor}}d4;
  border-color: {{$primarycolor}}d4;
}

.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #fff;
  border-color: {{$primarycolor}};
  background-color: {{$primarycolor}};
}

.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: {{$primarycolor}};
}

.custom-control-input:checked ~ .custom-control-label::before {
  color: #fff;
  border-color: {{$primarycolor}};
  background-color: {{$primarycolor}};
}

.form-control:focus {
  color: #3c4d62;
  background-color: #fff;
  border-color: {{$primarycolor}};
  box-shadow: 0 0 0 3px {{$primarycolor}}10;
}

.select2-container--default .select2-selection--single:focus {
  box-shadow: 0 0 0 3px {{$primarycolor}}10;
  border-color: {{$primarycolor}};
}

.link-list-plain a .icon {
  color: {{$primarycolor}};
}

.link-list-plain a:hover {
  color: {{$primarycolor}};
  background: #f5f6fa;
}

.nk-file-name-text a.title:hover {
  color: {{$primarycolor}};
}

.badge-dim.badge-outline-primary {
  color: {{$primarycolor}};
  background-color: {{$primarycolor}}0a;
  border-color: {{$primarycolor}}42;
}

.nav-tabs .nav-link.active {
  color: {{$primarycolor}};
  background-color: transparent;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border: 1px solid {{$primarycolor}};
    outline: 0;
}

.timeline-status {
  background-color: {{$primarycolor}}!important;
}

.nav-tabs .nav-link:after {
  background: {{$primarycolor}};
}

.nav-tabs .nav-link:focus {
  color: {{$primarycolor}};
}

.text-primary {
  color: {{$primarycolor}}!important;
}

.ql-toolbar button:hover, .ql-toolbar button:focus, .ql-toolbar button.ql-active, .ql-toolbar .ql-picker-label:hover,
.ql-toolbar .ql-picker-label.ql-active, .ql-toolbar .ql-picker-item:hover, .ql-toolbar .ql-picker-item.ql-selected {
  color: {{$primarycolor}}!important;
}

.ql-toolbar button:hover .ql-stroke, .ql-toolbar button:focus .ql-stroke, .ql-toolbar button.ql-active .ql-stroke,
.ql-toolbar .ql-picker-label:hover .ql-stroke, .ql-toolbar .ql-picker-label.ql-active .ql-stroke, .ql-toolbar
.ql-picker-item:hover .ql-stroke, .ql-toolbar .ql-picker-item.ql-selected .ql-stroke, .ql-toolbar button:hover
.ql-stroke-miter, .ql-toolbar button:focus .ql-stroke-miter, .ql-toolbar button.ql-active .ql-stroke-miter, .ql-toolbar
.ql-picker-label:hover .ql-stroke-miter, .ql-toolbar .ql-picker-label.ql-active .ql-stroke-miter, .ql-toolbar
.ql-picker-item:hover .ql-stroke-miter, .ql-toolbar .ql-picker-item.ql-selected .ql-stroke-miter {
  stroke: {{$primarycolor}}!important;
}

.ql-toolbar button:hover .ql-fill, .ql-toolbar button:focus .ql-fill, .ql-toolbar button.ql-active .ql-fill, .ql-toolbar
.ql-picker-label:hover .ql-fill, .ql-toolbar .ql-picker-label.ql-active .ql-fill, .ql-toolbar .ql-picker-item:hover
.ql-fill, .ql-toolbar .ql-picker-item.ql-selected .ql-fill, .ql-toolbar button:hover .ql-stroke.ql-fill, .ql-toolbar
button:focus .ql-stroke.ql-fill, .ql-toolbar button.ql-active .ql-stroke.ql-fill, .ql-toolbar .ql-picker-label:hover
.ql-stroke.ql-fill, .ql-toolbar .ql-picker-label.ql-active .ql-stroke.ql-fill, .ql-toolbar .ql-picker-item:hover
.ql-stroke.ql-fill, .ql-toolbar .ql-picker-item.ql-selected .ql-stroke.ql-fill {
  fill: {{$primarycolor}}!important;
}

.datepicker table tr td.today:active, .datepicker table tr td.today:hover:active, .datepicker table tr
td.today.disabled:active, .datepicker table tr td.today.disabled:hover:active, .datepicker table tr td.today.active,
.datepicker table tr td.today:hover.active, .datepicker table tr td.today.disabled.active, .datepicker table tr
td.today.disabled:hover.active {
  background-color: {{$primarycolor}}!important;
}

.datepicker table tr td.today:hover, .datepicker table tr td.today:hover:hover, .datepicker table tr
td.today.disabled:hover, .datepicker table tr td.today.disabled:hover:hover, .datepicker table tr td.today:active,
.datepicker table tr td.today:hover:active, .datepicker table tr td.today.disabled:active, .datepicker table tr
td.today.disabled:hover:active, .datepicker table tr td.today.active, .datepicker table tr td.today:hover.active,
.datepicker table tr td.today.disabled.active, .datepicker table tr td.today.disabled:hover.active, .datepicker table tr
td.today.disabled, .datepicker table tr td.today:hover.disabled, .datepicker table tr td.today.disabled.disabled,
.datepicker table tr td.today.disabled:hover.disabled, .datepicker table tr td.today[disabled], .datepicker table tr
td.today:hover[disabled], .datepicker table tr td.today.disabled[disabled], .datepicker table tr
td.today.disabled:hover[disabled] {
  background-color:{{$primarycolor}}!important;
}

.datepicker table tr td.active:active, .datepicker table tr td.active:hover:active, .datepicker table tr
td.active.disabled:active, .datepicker table tr td.active.disabled:hover:active, .datepicker table tr td.active.active,
.datepicker table tr td.active:hover.active, .datepicker table tr td.active.disabled.active, .datepicker table tr
td.active.disabled:hover.active {
  background-color:{{$primarycolor}}!important;
}

.datepicker table tr td.today, .datepicker table tr td.today:hover, .datepicker table tr td.today.disabled, .datepicker
table tr td.today.disabled:hover {
  background-color: #e7dcff;
  color: {{$primarycolor}}!important;
}

.badge-dim.badge-primary {
color: {{$primarycolor}};
}

.border-primary {
  border-color: {{$primarycolor}}!important;
}

.user-avatar, [class^="user-avatar"]:not([class*="-group"]) {
  background: {{$primarycolor}};
}

.accordion-s2 .accordion-head .title {
  color: {{$primarycolor}};
}

#hwpwrap .wp-core-ui .button-primary {
  background: {{$primarycolor}}d4!important;
  border-color: {{$primarycolor}}d4!important;
}

#hwpwrap .wp-core-ui .button-primary.focus, #hwpwrap .wp-core-ui .button-primary.hover, #hwpwrap .wp-core-ui
.button-primary:focus, #hwpwrap .wp-core-ui .button-primary:hover {
  background: {{$primarycolor}}!important;
  border-color: {{$primarycolor}}!important;
}

.page-item.active .page-link {
  color: #fff;
  background-color: {{$primarycolor}};
  border-color: {{$primarycolor}};
}

.page-link:hover {
  color: {{$primarycolor}};
  text-decoration: none;
  background-color: #ebeef2;
  border-color: #e5e9f2;
}

</style>