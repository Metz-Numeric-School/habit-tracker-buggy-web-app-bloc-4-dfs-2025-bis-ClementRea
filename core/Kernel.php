<?php

namespace Mns\Buggy\Core;

use App\Controller\ErrorController;

final class Kernel
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function handle()
    {
        $route = $this->router->match($this->router->uri);
        if ($route) {
            $controller = new $route['controller']();
            $action = $route['action'];

            if(!empty($route['guard'])){
                $guard = $route['guard'];
                $guard::check();
            }

            $_SESSION['current_uri'] = $route['path'];
            $controller->$action();
        } else {
            http_response_code(404);
            //echo "404 Not Found";
            $controller = new ErrorController();
            $controller->notFound();
            exit;
        }
    }

    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = $_SERVER['REQUEST_URI'];

        $this->handle();
        if ($this->serveStaticFile($requestPath)) {
          return;
        }
    }

  protected function serveStaticFile($path)
  {
    $allowedPaths = [
      '/swagger-ui/',
      '/api-docs/'
    ];

    foreach ($allowedPaths as $allowedPath) {
      if (strpos($path, $allowedPath) === 0) {
        $filePath = __DIR__ . '/../public' . $path;
          if (is_dir($filePath)) {
          $filePath .= '/index.html';
        }

        if (file_exists($filePath)) {
          $extension = pathinfo($filePath, PATHINFO_EXTENSION);
          $mimeTypes = [
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'ico' => 'image/x-icon',
            'map' => 'application/json'
          ];

          if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
          }

          readfile($filePath);
          return true;
        }
      }
    }
    return false;
  }
}

