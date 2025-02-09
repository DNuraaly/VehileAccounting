<?php

namespace App\Http\Responses\Car;

use App\Enums\RegistrationTypeEnum;
use App\Models\Car\Car;
use App\Models\Car\CarOwnership;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class CarOwnerHistoryResponse implements Responsable
{
    protected array $payload;
    protected string $view = 'pages/car/owner-history';
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
        /** @var Car $car */
        $car = $this->payload['car'];
        $histories = $car->relationLoaded('ownerHistories') ? $car->ownerHistories : [];
        $data = [];

        foreach ($histories as $item) {
            /** @var CarOwnership $item */
            $data[] = [
                'full_name' => $item->owner?->full_name ?? '---',
                'registered_at' => $item->registered_at ?? '---',
                're_registered_at' => $item->re_registered_at ?? '---',
                'registration_type' => $item->registration_type  === RegistrationTypeEnum::PRIMARY->value ? 'Первичная регистрация' : 'Вторичная регистрация',

            ];
        }

        return (new Response(view($this->view, ['data' => $data, 'car' => $car])));
    }
}
