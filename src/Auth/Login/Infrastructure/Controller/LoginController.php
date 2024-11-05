<?php
namespace App\Auth\Login\Infrastructure\Controller;

use App\Auth\Login\Aplication\UserLoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class LoginController extends AbstractController
{
    private UserLoginService $userLoginService;

    public function __construct(UserLoginService $userLoginService)
    {
        $this->userLoginService = $userLoginService;
    }

    /**
     * @Route ("/api/login", name="api_user_login", methods={"POST"})
     * @OA\Post(
     * path="/api/login",
     * summary="User login",
     * description="Authenticates a user and returns a JWT token",
     * tags={"Auth"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="email", type="string", description="User's email address"),
     * @OA\Property(property="password", type="string", description="User's password")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Login successfully",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="code", type="integer", example=201),
     * @OA\Property(property="message", type="string", example="Login successfully"),
     * @OA\Property(property="token", type="object",
     * @OA\Property(property="token", type="string", example="your_jwt_token_here")
     * )
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Error, user not found or invalid credentials",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Error, user not found")
     * )
     * )
     * )
     */
    public function login(Request $request):JsonResponse
    {
        $json = $request->get('json', null);
        $params = json_decode($json);

        $data = [
            'status' => 'error',
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => "Error, user not found",
        ];

        if (empty($json)) {
            return new JsonResponse($data);
        }

        $email = (!empty($params->email)) ? $params->email : null;
        $password = (!empty($params->password)) ? $params->password : null;

        $validator = Validation::createValidator();
        $validate_email = $validator->validate($email, [
            new Email()
        ]);

        if (!empty($email) && count($validate_email) == 0 && !empty($password)){
            try {
                $token = $this->userLoginService->loginUser($email, $password);

                $data = [
                    'status' => 'success',
                    'code' => Response::HTTP_OK,
                    'message' => "Login successfully",
                    'token' => json_decode($token->getContent()),
                ];
                return new JsonResponse($data);
            }catch (\InvalidArgumentException $e){
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            }
        }
        return new JsonResponse($data);
    }
}