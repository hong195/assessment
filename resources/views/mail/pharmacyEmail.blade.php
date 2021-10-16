@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            Рейтинг сотрудников
        @endcomponent
    @endslot

    {{-- Body --}}
    <h3 style="text-align: center; font-size: 14px;">
        Здравствуйте, система рейтинга видеонаблюдения оценила работу сотрудника.
    </h3>
    <p style="text-align: center;">
        Текущий рейтинг сотрудника(ов):
    </p>


    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © Система оценки сотрудников аптек <em>Oxymed</em> {{ date('Y') }}.
        @endcomponent
    @endslot
@endcomponent
