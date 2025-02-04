@component('mail::message')

Welcome! 
You had been join to Firefly AIS. Yay!

Your login: **{{ $address }}**

Your password: **{{ $password }}**

* {{ trans('email.registered_pw', ['address' => $address]) }}
* {{ trans('email.registered_help') }}
* {{ trans('email.registered_doc_text') }}

{{ trans('email.registered_closing') }}

* {{ trans('email.registered_firefly_iii_link')}} [{{$address }}]({{$address }})
* {{ trans('email.registered_pw_reset_link')}} [{{ $address }}/password/reset]({{ $address }}/password/reset)
* {{ trans('email.registered_doc_link')}} [https://docs.firefly-iii.org/](https://docs.firefly-iii.org/)

@endcomponent
