<?php

namespace App\Wines\GetAll\Infrastructure\Controller;


use App\Services\JwtAuth;
use App\Wines\GetAll\Aplication\GetAllWinesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WineController extends AbstractController
{
    private GetAllWinesService $getAllWinesService;

    public function __construct(GetAllWinesService $getAllWinesService)
    {
        $this->getAllWinesService = $getAllWinesService;
    }

    /**
     * @Route("/api/wines/getAll", name="api_wines_getAll", methods={"GET"})
     */
    public function getAll(Request $request, JwtAuth $jwt_auth):JsonResponse
    {
        $data = [
            'status' => 'error',
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Error, The Wines cannot be displayed',
        ];

        $token = $request->headers->get('Authorization');

        $authChecker = $jwt_auth->checkToken($token);

        $wines = [];

        if ($authChecker){
            $identity = $jwt_auth->checkToken($token, true);
            $wineObject = $this->getAllWinesService->getAllWines();

            foreach ($wineObject as $wine) {
                $measurementsArray = [];
                foreach ($wine->getMeasurements() as $measurement) {

                    if ($measurement->getWine()->getId() === $wine->getId()){
                        $measurementsArray[] = [
                            'id' => $measurement->getId(),
                            'id_wine' => $measurement->getWine()->getId(),
                            'id_sensor' => $measurement->getSensor()->getId(),
                            'color' => $measurement->getColor(),
                            'temperature' => $measurement->getTemperature(),
                            'alcohol_content' => $measurement->getAlcoholContent(),
                            'ph' => $measurement->getPh(),
                        ];
                    }
                }
                $wines[] = [
                    'id' => $wine->getId(),
                    'name' => $wine->getName(),
                    'year' => $wine->getYear(),
                    'measurements' => $measurementsArray,
                ];
            }

            $data = [
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Correct wine list',
                'wines' => $wines,
            ];
        }
        return new JsonResponse($data);
    }
}