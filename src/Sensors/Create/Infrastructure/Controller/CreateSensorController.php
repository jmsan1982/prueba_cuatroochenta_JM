<?php

namespace App\Sensors\Create\Infrastructure\Controller;

use App\Sensors\Create\Aplication\CreateSensorService;
use App\Services\JwtAuth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateSensorController
{
    private CreateSensorService $createSensorService;

    public function __construct(CreateSensorService $createSensorService)
    {
        $this->createSensorService = $createSensorService;
    }

    /**
     * @Route("/api/sensor", name="api_user_login", methods={"POST"})
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
                        'sensor' => $sensor
                    ];
                } catch (\InvalidArgumentException $e) {
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }
            }
        }
        return new JsonResponse($data);
    }
}
