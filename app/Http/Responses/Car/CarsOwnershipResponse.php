<?php

namespace App\Http\Responses\Car;

use App\Enums\RegistrationTypeEnum;
use App\Models\Car\Car;
use App\Models\Car\CarBrand;
use App\Models\Car\CarModel;
use App\Models\Car\CarOwnership;
use App\Models\Owner;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class CarsOwnershipResponse implements Responsable
{
    protected array $payload;
    protected string $view = 'pages/car/list';
    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    /**
     * @param $request
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response|\Symfony\Component\HttpFoundation\Response
    {
        $data = [];
        $carOwnership = $this->payload['cars_ownership'];
        $carBrands = $this->payload['car_brands'];
        foreach ($carOwnership as $item) {
            /** @var CarOwnership $item */
            /** @var ?Car $car */
            /** @var ?Owner $owner */
            /** @var ?CarBrand $carBrand */
            /** @var ?CarModel $carModel */
            $car  = $item->relationLoaded('car') ? $item->car : null;
            $carBrand = $car?->relationLoaded('brand') ? $car->brand : null;
            $carModel = $car?->relationLoaded('model') ? $car->model : null;
            $owner = $item->relationLoaded('owner') ? $item->owner : null;
            $data[] = [
                'id' => $item->id,
                'full_name' => $owner?->full_name ?? '---',
                'auto_number' => $car->auto_number ?? '---',
                'brand' => $carBrand?->title ?? '---',
                'model' => $carModel?->title ?? '---',
                'registered_at' => $item->registered_at ?? '---',
                're_registered_at' => $item->re_registered_at ?? '---',
                'registration_type' => $item->registration_type  === RegistrationTypeEnum::PRIMARY->value ? 'Первичная регистрация' : 'Вторичная регистрация',
                'can_re_registration' => !!!$item->re_registered_at
            ];
        }

        return (new Response(view($this->view, ['data' => $data, 'brands' => $carBrands])));
    }
}
