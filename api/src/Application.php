<?php
namespace App;

use App\Controller\ConvertController;
use App\Controller\LoginController;
use App\Controller\PrizeController;
use App\Exception\NoPendingPrizeException;
use App\Exception\WrongPrizeTypeException;
use App\Service\AuthService;
use App\Service\ConvertService;
use App\Service\PrizeService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Application implements HttpKernelInterface
{
    protected RouteCollection $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();
    }

    public function handle(Request $request, $type = HttpKernelInterface::MAIN_REQUEST, $catch = true): Response
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->register('auth.service', AuthService::class);
        $containerBuilder->register('prize.service', PrizeService::class);
        $containerBuilder->register('convert.service', ConvertService::class);
        $containerBuilder->register(LoginController::class, LoginController::class)
            ->addArgument(new Reference('auth.service'));
        $containerBuilder->register(PrizeController::class, PrizeController::class)
            ->addArgument(new Reference('prize.service'));
        $containerBuilder->register(ConvertController::class, ConvertController::class)
            ->addArgument(new Reference('convert.service'));


        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);
        $argumentResolver = new ArgumentResolver();

        try {
            $match = $matcher->match($request->getPathInfo());

            [$controller, $method] = explode("::", $match['controller']->getDefaults()["_controller"], 2);

            $controller = [$containerBuilder->get($controller), $method];
            $arguments = $argumentResolver->getArguments($request, $controller);

            try {
                $response = call_user_func_array($controller, $arguments);
            } catch (JsonException | NotFoundHttpException | WrongPrizeTypeException | NoPendingPrizeException $e) {
                $response = new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
            }
        } catch (ResourceNotFoundException $e) {
            $response = new JsonResponse(['error' => 'Not found!'], Response::HTTP_NOT_FOUND);
        }

        return $response;
    }

    public function map($path, $controller) {
        $this->routes->add($path, new Route(
            $path,
            array('controller' => $controller)
        ));
    }
}
