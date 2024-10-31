<?php
namespace App\Auth\Register\Infrastructure\Controller;

use App\Auth\Register\Aplication\UserRegistrationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private $userRegistrationService;

    public function __construct(UserRegistrationService $userRegistrationService)
    {
        $this->userRegistrationService = $userRegistrationService;
    }

    /**
     * @Route("/api/register", name="api_user_register", methods={"POST"})
     *
     * @OA\Post(
     *      summary="Register a new user",
     *      description="Registers a new user in the system",
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="password", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request"
     *      )
     *  )
     */
    public function register(Request $request):JsonResponse
    {
        $json = $request->get('json', null);

        $params = json_decode($json);

        $data = [
            'status' => 'error',
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => "Failed to register the user",
        ];

        if (!isset($json)) {
            return new JsonResponse($data);
        }

        $name = (!empty($params->name)) ? $params->name : null;
        $surname = (!empty($params->surname)) ? $params->surname : null;
        $email = (!empty($params->email)) ? $params->email : null;
        $password = (!empty($params->password)) ? $params->password : null;

        try {

            $this->userRegistrationService->registerUser($name, $surname, $email, $password);

            $data = [
                'status' => 'success',
                'code' => Response::HTTP_CREATED,
                'message' => "User created successfully",
            ];

        }catch (\InvalidArgumentException $e){
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($data);
    }
}