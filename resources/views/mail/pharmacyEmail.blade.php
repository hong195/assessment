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


<p style="text-align: center;">
@foreach($employees as $employee)
@foreach($finalGrades as $finalGrade)
@if ((string) $employee->getId() === (string) $finalGrade->getEmployeeId())
<span style="font-size: 14px; display: block;">
<em>{!! (string) $employee->getName() !!}</em> : <em>{!! $finalGrade->getScored() !!}/{!! $finalGrade->getTotal() !!}</em>
</span>
@endif
@endforeach
@endforeach
</p>

<p>
Для более подробной информации о рейтинге сотрудников и аптек по всей сети перейдите по <a href="{{ url('/#/home') }}">ссылке</a>
</p>
    {{-- Footer --}}
@slot('footer')
@component('mail::footer')
    © Система оценки сотрудников аптек <em>Oxymed</em> {{ date('Y') }}.
@endcomponent
@endslot
@endcomponent
