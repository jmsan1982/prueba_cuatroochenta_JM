<?php

namespace App\Wines\GetAll\Infrastructure\Controller;


use App\Services\JwtAuth;
use App\Wines\GetAll\Aplication\GetAllWinesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class WineController extends AbstractController
{
    private $getAllWinesService;
    private $serializer;

    public function __construct(GetAllWinesService $getAllWinesService, SerializerInterface $serializer)
    {
        $this->getAllWinesService = $getAllWinesService;
        $this->serializer = $serializer;
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

        if ($authChecker){
            $identity = $jwt_auth->checkToken($token, true);
            $wineObject = $this->getAllWinesService->getAllWines();
            $measurements = [];
            foreach ($wineObject as $wine) {
                $measurements[] = $wine->getMeasurements()->toArray();
            }
            $wineObject['measurements'] = $measurements;
            $wines = $this->serializer->serialize($wineObject, 'json');

            $data = [
                'status' => 'success',
                'code' => Response::HTTP_OK,
                'message' => 'Correct wine list',
                'wines' => json_decode($wines),
            ];
        }
        return new JsonResponse($data);
    }
}