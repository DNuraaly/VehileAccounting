@extends('layout.wrapper')

@section('title', 'Список автомобилей')

@section('content')
    <h2>Зарегистрированные модели</h2>

    @if(count($models) > 0)
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Марка</th>
                <th>Модель</th>
                <th>Количество зарегистрированных авто</th>
            </tr>
            </thead>
            <tbody>
            @foreach($models as $model)
                <tr>
                    <td>{{ $model['brand']}}</td>
                    <td>{{ $model['model'] }}</td>
                    <td>{{ $model['cars_count'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет зарегистрированных автомобилей.</p>
    @endif
@endsection
