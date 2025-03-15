<?php
declare(strict_types=1);

namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Application setup class.
 */
class Application extends BaseApplication
{
    /**
     * Load all the application configuration and bootstrap logic.
     */
    public function bootstrap(): void
    {
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        // Load DebugKit in development mode only
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }
    }

    /**
     * Setup the middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            ->add(new ErrorHandlerMiddleware())
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this)) // ✅ Corrected instantiation
            ->add(new BodyParserMiddleware())
            
            // ✅ Fixed CORS Middleware
            ->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                $response = $handler->handle($request); // ✅ Corrected `$handler->handle($request)`

                // Handle CORS Preflight (OPTIONS Request)
                if ($request->getMethod() === 'OPTIONS') {
                    return $response
                        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                        ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization')
                        ->withHeader('Access-Control-Allow-Credentials', 'true')
                        ->withStatus(200);
                }

                return $response
                    ->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                    ->withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token, Authorization')
                    ->withHeader('Access-Control-Allow-Credentials', 'true');
            });

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     */
    public function services(ContainerInterface $container): void
    {
        // Register services if needed
    }

    /**
     * Bootstrapping for CLI application.
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');
        $this->addPlugin('Migrations');
    }
}
