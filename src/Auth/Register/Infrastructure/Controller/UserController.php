<?php
namespace App\Auth\Register\Infrastructure\Controller;

use App\Auth\Register\Aplication\UserRegistrationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    private UserRegistrationService $userRegistrationService;

    public function __construct(UserRegistrationService $userRegistrationService)
    {
        $this->userRegistrationService = $userRegistrationService;
    }

    /**
     * @Route("/api/register", name="api_user_register", methods={"POST"})
     *
     * @OA\Post(
     * path="/api/register",
     * summary="Register a new user",
     * description="Registers a new user in the system",
     * tags={"Auth"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="name", type="string", description="User's first name"),
     * @OA\Property(property="surname", type="string", description="User's surname"),
     * @OA\Property(property="email", type="string", description="User's email address"),
     * @OA\Property(property="password", type="string", description="User's password")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="User registered successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="code", type="integer", example=201),
     * @OA\Property(property="message", type="string", example="User created successfully")
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Failed to register the user",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="code", type="integer", example=400),
     * @OA\Property(property="message", type="string", example="Failed to register the user")
     * )
     * )
     * )
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

        if (empty($json)) {
            return new JsonResponse($data);
        }

        $name = (!empty($params->name)) ? $params->name : null;
        $surname = (!empty($params->surname)) ? $params->surname : null;
        $email = (!empty($params->email)) ? $params->email : null;
        $password = (!empty($params->password)) ? $params->password : null;

        $validator = Validation::createValidator();
        $validate_email = $validator->validate($email, [
            new Email()
        ]);

        if (!empty($email) && count($validate_email) == 0 && !empty($password) && !empty($name) && !empty($surname)){
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
        }

        return new JsonResponse($data);
    }
}