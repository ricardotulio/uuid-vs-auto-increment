<?php

declare(strict_types=1);

namespace App\Http\Controller\V1\Person;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Service\PersonService;

class Create
{
    /**
     * @param App\Service\PersonService
     */
    public function __construct(PersonService $personService)
    {
        $this->personService = $personService;
    }
    
    /**
     * Listen on POST /v1/person/
     *
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @return Psr\Http\Message\ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $response->write('Criando uma pessoa');
        return $response;
    }
}
