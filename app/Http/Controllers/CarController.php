<?php

namespace App\Http\Controllers;

use App\Enums\RegistrationTypeEnum;
use App\Http\Requests\CreateCarRequest;
use App\Http\Responses\Car\CarModelListResponse;
use App\Http\Responses\Car\CarOwnerHistoryResponse;
use App\Http\Responses\Car\CarsOwnershipResponse;
use App\Models\Car\Car;
use App\Models\Car\CarBrand;
use App\Models\Car\CarModel;
use App\Models\Car\CarOwnership;
use App\Models\Owner;
use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * @param Request $request
     * @return Responsable
     */
    public function index(Request $request): Responsable
    {
        $search = $request->get('search');
        $carsOwnershipQuery = CarOwnership::query();
        if ($search) {
            $carsOwnershipQuery
                ->select('car_ownerships.*', 'cars.auto_number as auto_number', 'owners.full_name as full_name')
                ->leftJoin('cars', 'cars.id', '=', 'car_ownerships.car_id')
                ->leftJoin('owners', 'owners.id', '=', 'car_ownerships.owner_id')
                ->OrWhere('auto_number', 'LIKE', "%{$search}%")
                ->OrWhere('full_name', 'LIKE', "%{$search}%");
        }
        $carsOwnership = $carsOwnershipQuery->with(['car.brand', 'car.model', 'owner'])->orderByDesc('id')->get();
        $carBrands = CarBrand::query()->with(['models'])->get();
        return new CarsOwnershipResponse(['cars_ownership' => $carsOwnership, 'car_brands' => $carBrands]);
    }

    // Первичная регистрация

    /**
     * @param CreateCarRequest $request
     * @return RedirectResponse
     */
    public function registerCar(CreateCarRequest $request): RedirectResponse
    {

        $validator = $request->getValidator();
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $owner = Owner::query()->create(['full_name' => $request->full_name]);
            $generatedNumber = DB::select("
                SELECT (t.n + 900000) AS free_car_number
                FROM generate_series(0, 99999) t(n)
                LEFT JOIN cars ON cars.generated_number = t.n + 900000
                WHERE cars.generated_number IS NULL
                ORDER BY RANDOM()
                LIMIT 1;
            ");
            $generatedNumber = $generatedNumber[0]->free_car_number ?? null;
            if (!$generatedNumber) {
                // TODO if cars => 999999
                abort(500);
            }

            $car = Car::query()->create([
                'auto_number' => "AUTO-{$generatedNumber}",
                'generated_number' => $generatedNumber,
                'car_brand_id' => request('brand_id'),
                'car_model_id' => request('model_id'),

            ]);

            CarOwnership::query()->create([
                'owner_id' => $owner->id,
                'car_id' => $car->id,
                'registered_at' => Carbon::now(),
                'registration_type' => RegistrationTypeEnum::PRIMARY->value,
            ]);
            DB::commit();
            return redirect()->route('cars.index');

        } catch (Exception $e) {
            DB::rollBack();
            abort(500);
        }
    }

    // Перерегистрация

    /**
     * @param Request $request
     * @param string $autoNumber
     * @return RedirectResponse
     */
    public function reRegisterCar(Request $request, string $autoNumber): RedirectResponse
    {
        $request->validate([
            'new_owner_name' => 'required|string',
        ]);
        $car = Car::query()->where('auto_number', $autoNumber)->firstOrFail();
        $newOwner = Owner::query()->create(['full_name' => $request->new_owner_name]);

        DB::beginTransaction();
        try {
            CarOwnership::query()
                ->where('car_id', $car->id)
                ->latest('id')
                ->first()
                ->update(['re_registered_at' => Carbon::now()]);

            CarOwnership::query()->create([
                'owner_id' => $newOwner->id,
                'car_id' => $car->id,
                'registered_at' => Carbon::now(),
                'registration_type' => RegistrationTypeEnum::RE_REGISTERED->value,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            abort(500);
        }

        return redirect()->route('cars.index');
    }

    // История владельцев по номеру авто
    public function showOwnerHistory($autoNumber): Responsable
    {
        $car = Car::query()->where('auto_number', $autoNumber)
            ->with([
                'ownerHistories' => function ($query) {
                    $query->orderByDesc('id');
                    $query->with(['owner']);
                },
            ])
            ->firstOrFail();

        return new CarOwnerHistoryResponse(['car' => $car]);
    }

    public function showCarModels(): Responsable
    {
        $models = CarModel::query()->whereHas('cars')
            ->with(['brand'])
            ->withCount('cars')
            ->get();

        return new CarModelListResponse(['models' => $models]);
    }
}

