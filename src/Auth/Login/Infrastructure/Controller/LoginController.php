<?php
namespace App\Auth\Login\Infrastructure\Controller;

use App\Auth\Login\Aplication\UserLoginService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;

class LoginController extends AbstractController
{
    private UserLoginService $userLoginService;

    public function __construct(UserLoginService $userLoginService)
    {
        $this->userLoginService = $userLoginService;
    }

    /**
     * @Route("/api/login", name="api_user_login", methods={"POST"})
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
                    'code' => Response::HTTP_CREATED,
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