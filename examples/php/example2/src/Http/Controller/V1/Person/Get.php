<?php

namespace App\Http\Controller\V1\Person;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Service\PersonService;

class Get
{
    /**
     * @param App\Service\PersonService $personService
     */
    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }

    /**
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $personId = $request->getAttribute('id');
        $person = $this->personService->get($personId);
        return $response->withJson($person->toArray(), 200);
    }
}
