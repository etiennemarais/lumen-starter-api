<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Starter\Users\User;
use Starter\Users\UsersRepository;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @param UsersRepository $repository
     * @return mixed
     */
    public function index(Request $request, UsersRepository $repository)
    {
        $user = $repository->findWithAttributes([
            'id' => Config::get('user_id'), # auth middleware set this
        ]);

        return $this->respondWithSuccess('Successfully fetched user data', $user);
    }

    /**
     * @param Request $request
     * @param UsersRepository $repository
     * @return Response
     */
    public function create(Request $request, UsersRepository $repository)
    {
        $validator = Validator::make($request->all(), User::$rules);

        if ($validator->fails()) {
            return $this->respondWithErrorMessage($validator);
        }

        $user = $repository->createWithAttributes($request->all());

        return $this->respondWithSuccess('User added successfully', $user);
    }

    /**
     * @param $message
     * @param $user
     * @return mixed
     */
    private function respondWithSuccess($message, $user)
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
            'data' => [
                'user' => [
                    'id' => $user->id,
                ]
            ],
        ], 200);
    }
}
