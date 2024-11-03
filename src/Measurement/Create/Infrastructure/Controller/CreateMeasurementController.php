<?php

namespace App\Measurement\Create\Infrastructure\Controller;

use App\Measurement\Create\Aplication\CreateMeasurementService;
use App\Services\JwtAuth;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateMeasurementController extends AbstractController
{
    private CreateMeasurementService $createMeasurementService;

    public function __construct(CreateMeasurementService $createMeasurementService)
    {
        $this->createMeasurementService = $createMeasurementService;
    }

    /**
     * @Route("/api/measurement/createe", name="api_measurement_create", methods={"POST"})
     */
    public function create(Request $request, JwtAuth $jwt_auth):JsonResponse
    {
        $data = [
            'status' => 'error',
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => "Failed to create the Measurement",
        ];

        $token = $request->headers->get('Authorization');

        $authChecker = $jwt_auth->checkToken($token);

        if ($authChecker){
            $json = $request->get('json', null);
            $params = json_decode($json);

            $identity = $jwt_auth->checkToken($token, true);

            if (empty($json)) {
                return new JsonResponse($data);
            }

            $id_wine = (!empty($params->id_wine)) ? $params->id_wine : null;
            $id_sensor = (!empty($params->id_sensor)) ? $params->id_sensor : null;
            $color = (!empty($params->color)) ? $params->color : null;
            $temperature = (!empty($params->temperature)) ? $params->temperature : null;
            $alcohol_content = (!empty($params->alcohol_content)) ? $params->alcohol_content : null;
            $ph = (!empty($params->ph)) ? $params->ph : null;

            if (!empty($id_wine) && !empty($id_sensor) && !empty($color) && !empty($temperature) && !empty($alcohol_content) && !empty($ph)){

                try {
                    $this->createMeasurementService->execute($id_wine, $id_sensor, $color, $temperature, $alcohol_content, $ph);

                    $data = [
                        'status' => 'success',
                        'code' => Response::HTTP_CREATED,
                        'message' => "measurement created successfully",
                    ];
                }catch (\InvalidArgumentException $e){
                    return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
                }
            }

        }
        return new JsonResponse($data);
    }
}