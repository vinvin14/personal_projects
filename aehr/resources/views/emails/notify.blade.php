@component('mail::message')
# AEHR-EMAIL NOTIFICATION SYSTEM
<div class="container mb-3">
    @if($message['from'] == 'resources_notifications')
        @foreach ($message['message'] as $list)
            <div class="p-2">{{$list}}</div>
        @endforeach
    @endif
</div>

{{--@component('mail::button', ['url' => ''])--}}
{{--Button Text--}}
{{--@endcomponent--}}

<br>Thanks,<br>
{{ config('app.name') }}
@endcomponent
