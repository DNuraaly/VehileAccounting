<?php

namespace App\Http\Responses\Car;

use App\Models\Car\CarModel;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class CarModelListResponse implements Responsable
{
    protected array $payload;
    protected string $view = 'pages/car/model-list';
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
        $models = $this->payload['models'] ?? [];
        foreach ($models as $model) {
            /** @var CarModel $model */
            $data[] = [
                'brand' => $model->brand->title,
                'model' => $model->title,
                'cars_count' => $model->cars_count,
            ];
        }
        return (new Response(view($this->view,['models' => $data])));
    }
}
