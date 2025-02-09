@extends('layout.wrapper')

@section('title', 'Список автомобилей')

@section('content')
    <h2>История владельцев</h2>

    @if(count($data) > 0)
        <div class="mb-3">
            <strong>Номер машины:</strong> {{ $car->auto_number}}
        </div>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>ФИО владельца</th>
                <th>Дата регистрации</th>
                <th>Дата перерегистрации</th>
                <th>Тип регистрации</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['full_name'] }}</td>
                    <td>{{ $item['registered_at'] }}</td>
                    <td>{{ $item['re_registered_at'] }}</td>
                    <td>{{ $item['registration_type'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет зарегистрированных автомобилей.</p>
    @endif
@endsection
