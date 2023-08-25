@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}" style="height: 50px;width: 50px;" class="logo" alt="Storage Keys">
@else
{{--        <img src="{{ asset('sk-assets/assets/images/frontend/front-logo.png') }}"  class="logo" alt="Storage Keys">--}}
{{ $slot }}
@endif
</a>
</td>
</tr>
