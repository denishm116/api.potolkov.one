@component('mail::message')
    # Заявка с сайта - Краснодар

    Время: {{ date("d/m/Y") }}

    Данные формы: {{$formData['formName']}}
    Имя: {{$formData['userName']}}
    Номер телефона: {{$formData['phone']}}



    {{ config('app.name') }}
@endcomponent
