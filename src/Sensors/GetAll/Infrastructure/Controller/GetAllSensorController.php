<?php

namespace App\Sensors\GetAll\Infrastructure\Controller;

use App\Sensors\GetAll\Aplication\GetAllSensorService;
use App\Services\JwtAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class GetAllSensorController extends AbstractController
{
    private GetAllSensorService $getAllSensorService;
    private SerializerInterface $serializer;

    public function __construct(GetAllSensorService $getAllSensorService, SerializerInterface $serializer)
    {
        $this->getAllSensorService = $getAllSensorService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/sensor/getAll", name="api_sensor_getAll", methods={"GET"})
     *
     * @OA\Get(
     * path="/api/sensor/getAll",
     * summary="Get all sensors",
     * description="Retrieve a list of all sensors",
     * tags={"Sensor"},
     * security={{"bearer":{}}},
     * @OA\Response(
     * response=200,
     * description="Correct sensor list",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="code", type="integer", example=200),
     * @OA\Property(property="message", type="string", example="Correct sensor list"),
     * @OA\Property(property="sensors", type="array", @OA\Items(type="object"))
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Failed to retrieve the sensor list",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="code", type="integer", example=400),
     * @OA\Property(property="message", type="string", example="Error, The Sensors cannot be displayed")
     * )
     * )
     * )
     */
    public function getAll(Request $request, JwtAuth $jwt_auth): JsonResponse
    {
        $data = [
            'status' => 'error',
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Error, The Sensors cannot be displayed',
        ];

        $token = $request->headers->get('Authorization');

        $authChecker = $jwt_auth->checkToken($token);

        if ($authChecker) {
            $idetity = $jwt_auth->checkToken($token, true);
            $sensorsObject = $this->getAllSensorService->getAll();

            $sensors = $this->serializer->serialize($sensorsObject, 'json');

            $data = [
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Correct sensor list',
                'sensors' => json_decode($sensors),
            ];
        }

        return new JsonResponse($data);
    }


}