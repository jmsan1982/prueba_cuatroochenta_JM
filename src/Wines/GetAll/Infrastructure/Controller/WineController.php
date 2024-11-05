<?php

namespace App\Wines\GetAll\Infrastructure\Controller;


use App\Services\JwtAuth;
use App\Wines\GetAll\Aplication\GetAllWinesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class WineController extends AbstractController
{
    private GetAllWinesService $getAllWinesService;

    public function __construct(GetAllWinesService $getAllWinesService)
    {
        $this->getAllWinesService = $getAllWinesService;
    }

    /**
     * @Route("/api/wines/getAll", name="api_wines_getAll", methods={"GET"})
 *
* @OA\Get(
     * path="/api/wines/getAll",
     * summary="Get all wines",
     * description="Retrieve a list of all wines with their measurements",
     * tags={"Wine"},
     * security={{"bearer":{}}},
     * @OA\Response(
     * response=200,
     * description="Correct wine list",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="code", type="integer", example=200),
     * @OA\Property(property="message", type="string", example="Correct wine list"),
     * @OA\Property(
     * property="wines",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="cune"),
     * @OA\Property(property="year", type="string", example="2020"),
     * @OA\Property(
     * property="measurements",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="id_wine", type="integer", example=1),
     * @OA\Property(property="id_sensor", type="integer", example=4),
     * @OA\Property(property="color", type="string", example="red"),
     * @OA\Property(property="temperature", type="string", example="5.5"),
     * @OA\Property(property="alcohol_content", type="string", example="5.5"),
     * @OA\Property(property="ph", type="string", example="5.5")
     * )
     * )
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Failed to retrieve the wine list",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="code", type="integer", example=400),
     * @OA\Property(property="message", type="string", example="Error, The Wines cannot be displayed")
     * )
     * )
     * )
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