<?php

namespace App\Sensors\Create\Infrastructure\Controller;

use App\Sensors\Create\Aplication\CreateSensorService;
use App\Services\JwtAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class CreateSensorController extends AbstractController
{
    private CreateSensorService $createSensorService;

    public function __construct(CreateSensorService $createSensorService)
    {
        $this->createSensorService = $createSensorService;
    }

    /**
     * @Route("/api/sensor/create", name="api_sensor_create", methods={"POST"})
     * @OA\Post(
     *      path="/api/sensor/create",
     *      summary="Create a new sensor",
     *      description="Creates a new sensor",
     *      tags={"Sensor"},
     *      security={{"bearer":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", description="Name of the sensor")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sensor created successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="code", type="integer", example=201),
     *              @OA\Property(property="message", type="string", example="Sensor created successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Failed to create the sensor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="status", type="string", example="error"),
     *              @OA\Property(property="code", type="integer", example=400),
     *              @OA\Property(property="message", type="string", example="Failed to create the sensor")
     *          )
     *      )
     *  )
     */
    public function create(Request $request, JwtAuth $jwt_auth): JsonResponse
    {
        $data = [
            'status' => 'error',
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => "Failed to create the sensor",
        ];

        $token = $request->headers->get('Authorization');

        $authChecker = $jwt_auth->checkToken($token);

        if ($authChecker) {
            $json = $request->get('json', null);
            $params = json_decode($json);

            $identity = $jwt_auth->checkToken($token, true);

            if (empty($json)) {
                return new JsonResponse($data);
            }

            $name = (!empty($params->name)) ? $params->name : null;

            if (!empty($name)) {
                try {
                    $sensor = $this->createSensorService->execute($name);

                    $data = [
                        'status' => 'success',
                        'code' => Response::HTTP_CREATED,
                        'message' => "Sensor created successfully",
                    ];
                } catch (\InvalidArgumentException $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        return new JsonResponse($data);
    }
}
