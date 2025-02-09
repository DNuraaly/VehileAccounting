@extends('layout.wrapper')

@section('title', 'Список автомобилей')

@section('content')
    <div class="text-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">+</button>
    </div>

    <h2>Зарегистрированные автомобили</h2>

    <form action="{{ route('cars.index') }}" method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control" placeholder="Поиск по номеру авто, владельцу" value="{{ request()->get('search') }}">
        <button type="submit" class="btn btn-primary ms-2">Поиск</button>
    </form>

    @if(count($data) > 0)
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>ФИО владельца</th>
                <th>Номер авто</th>
                <th>Марка</th>
                <th>Модель</th>
                <th>Дата регистрации</th>
                <th>Дата перерегистрации</th>
                <th>Тип регистрации</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['full_name'] }}</td>
                    <td>{{ $item['auto_number'] }}</td>
                    <td>{{ $item['brand'] }}</td>
                    <td>{{ $item['model'] }}</td>
                    <td>{{ $item['registered_at'] }}</td>
                    <td>{{ $item['re_registered_at'] }}</td>
                    <td>{{ $item['registration_type'] }}</td>
                    <td>
                        <a href="{{ route('cars.showHistory', $item['auto_number']) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Детали">
                            <i class="bi bi-eye" style="font-size: 1.5rem;"></i>
                        </a>
                        @if($item['can_re_registration'])
                            <a href="#" data-bs-toggle="modal" data-bs-target="#reRegisterModal" data-car-id="{{ $item['auto_number'] }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Перерегистрировать">
                                <i class="bi bi-pencil" style="font-size: 1.5rem; color: #ffc107;"></i>
                            </a>
                        @else
                            <i class="bi bi-pencil" style="font-size: 1.5rem; color: #6c757d;" title="Перерегистрация недоступна"></i>
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>Нет зарегистрированных автомобилей.</p>
    @endif

    <!-- Модальное окно регистрации -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Регистрация авто</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cars.register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="full_name">ФИО владельца:</label>
                            <input type="text" id="full_name" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="brand">Марка автомобиля:</label>
                            <select id="brand" name="brand_id" class="form-control" onchange="updateModels()" required>
                                <option value="">-- Выберите марку --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand['id'] }}">{{ $brand['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="model">Модель автомобиля:</label>
                            <select id="model" name="model_id" class="form-control" required>
                                <option value="">-- Сначала выберите марку --</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Зарегистрировать</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно перерегистрации -->
    <div class="modal fade" id="reRegisterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Перерегистрация авто</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('cars.reRegister', ':id') }}" method="POST" id="reRegisterForm">
                        @csrf
                        <div class="mb-3">
                            <label>ФИО нового владельца</label>
                            <input type="text" name="new_owner_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Перерегистрировать</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const brands = @json($brands);

        function updateModels() {
            const brandId = document.getElementById("brand").value;
            const modelSelect = document.getElementById("model");

            modelSelect.innerHTML = '<option value="">-- Выберите модель --</option>';

            if (brandId) {
                const selectedBrand = brands.find(brand => brand.id == brandId);
                if (selectedBrand && selectedBrand.models.length > 0) {
                    selectedBrand.models.forEach(model => {
                        let option = document.createElement("option");
                        option.value = model.id;
                        option.textContent = model.title;
                        modelSelect.appendChild(option);
                    });
                } else {
                    let option = document.createElement("option");
                    option.value = "";
                    option.textContent = "-- Нет доступных моделей --";
                    modelSelect.appendChild(option);
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            var reRegisterModal = document.getElementById('reRegisterModal');
            if (reRegisterModal) {
                reRegisterModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var carId = button.getAttribute('data-car-id');
                    var form = document.getElementById('reRegisterForm');
                    form.action = form.action.replace(':id', carId);
                });
            }
        });


        // Enable tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection
