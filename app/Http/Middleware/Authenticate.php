<?php

namespace App\Http\Middleware;

use Closure;
use Starter\Users\UsersRepository;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    private $repository;

    public function __construct(UsersRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        $authHeader = str_replace('Token ', '', $authHeader);

        $user = $this->repository->findWithApiKey($authHeader);

        // Checks the API key against the user and the auth header exists
        if ($authHeader && $user) {
            $this->addMultitenantIdentifier($user);
            return $next($request);
        }

        return $this->returnWithInvalidApiKey();
    }

    /**
     * @return mixed
     */
    public function returnWithInvalidApiKey()
    {
        return response()->json([
            "status" => 401,
            "message" => "Invalid API Key",
        ], 401);
    }

    /**
     * @param $user
     */
    protected function addMultitenantIdentifier($user)
    {
        app('Infrastructure\TenantScope\TenantScope')->addTenant('user_id', $user->id);
        Config::set('user_id', $user->id);
    }
}
