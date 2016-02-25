<?php

class AuthenticateTest extends TestCase
{
    private $usersRepository;
    private $request;
    private $closureNext;
    private $authMiddleware;
    private $multiTenantScope;

    public function setUp()
    {
        parent::setUp();
        $this->usersRepository = Mockery::mock(Starter\Users\UsersRepository::class);
        $this->request = Mockery::mock('Illuminate\Http\Request');
        $this->multiTenantScope = $this->mock('Infrastructure\TenantScope\TenantScope');

        $request = $this->request;

        $this->closureNext = function() use ($request) {
            return $request;
        };

        $this->authMiddleware = new \App\Http\Middleware\Authenticate(
            $this->usersRepository
        );
    }

    public function tearDown()
    {
        parent::tearDown();
        unset(
            $this->usersRepository,
            $this->request,
            $this->multiTenantScope,
            $this->closureNext,
            $this->authMiddleware
        );
    }

    public function testInitialize_ReturnsInstanceOfAuthenticateMiddleware()
    {
        $this->assertInstanceOf(
            App\Http\Middleware\Authenticate::class,
            $this->authMiddleware
        );
    }

    public function testHandle_ReturnsUnauthorizedResponse()
    {
        $this->request->shouldReceive('header')
            ->with('Authorization')
            ->once()
            ->andReturn(null);

        $this->usersRepository->shouldReceive('findWithApiKey')
            ->once()
            ->andReturn(null);

        $this->response = $this->authMiddleware->handle($this->request, $this->closureNext);
        $this->assertResponseStatus(401);
        $this->seeJsonEquals([
            'status' => 401,
            'message' => 'Invalid API Key',
        ]);
    }

    public function testHandle_ValidatesCountryAndFollowsThroughRouter()
    {
        $apiKey = 'Token someValidApiKey';

        $this->request->shouldReceive('header')
            ->with('Authorization')
            ->once()
            ->andReturn($apiKey);

        $user = Mockery::mock('User');
        $user->id = 1;

        $this->usersRepository->shouldReceive('findWithApiKey')
            ->once()
            ->andReturn($user);

        $this->multiTenantScope->shouldReceive('addTenant')
            ->with('user_id', $user->id)
            ->once();

        $request = $this->authMiddleware->handle($this->request, $this->closureNext);
        $this->assertInstanceOf(get_class($this->request), $request);
    }
}
