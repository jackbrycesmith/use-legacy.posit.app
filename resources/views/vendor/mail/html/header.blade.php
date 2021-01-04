<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="file://{{ resource_path('images/posit-logo-app-email.png') }}" data-auto-embed class="logo" alt="Posit.app">
{{--{{ $slot }}--}}
@endif
</a>
</td>
</tr>
