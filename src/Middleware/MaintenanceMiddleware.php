<?php
declare(strict_types=1);

namespace App\Middleware;

use Cake\Core\Configure;
use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Maintenance middleware
 */
class MaintenanceMiddleware implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Check if maintenance mode is enabled
        if (file_exists(TMP . 'maintenance.flag')) {
            Configure::write('maintenance', true);
            $controller = new \App\Controller\AppController();
            $controller
                ->viewBuilder()
                ->setTemplatePath('Error')
                ->setTemplate('maintenance')
            ;
            $controller->render();
            return $controller->getResponse()->withStatus(503);
        }

        return $handler->handle($request);
    }
}
