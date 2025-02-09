<?php

namespace App\Http\Responses\Car;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;

class CarRegisterFormResponse implements Responsable
{
    protected array $payload;
    protected string $view = 'pages/car/registration-form';
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
        return (new Response(view($this->view, $this->payload)));
    }
}
