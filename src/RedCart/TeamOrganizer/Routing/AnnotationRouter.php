<?php

namespace RedCart\TeamOrganizer\Routing;

use RedCart\TeamOrganizer\Foundation\AbstractController;
use Redcart\TeamOrganizer\Foundation\Container;
use RedCart\TeamOrganizer\Routing\Exception\ControllerNotFoundException;
use RedCart\TeamOrganizer\Routing\Exception\ControllerNotMatchedException;
use RedCart\TeamOrganizer\Routing\Exception\IncorrectControllerException;
use ReflectionClass;
use ReflectionException;

/**
 * Dopasowuje ścieżkę przychodzącego żądania do odpowiedniego routa
 *
 * Aby dodać nową ścieżkę dla routera należy napisać annotację dla metody w kontrolerze
 * Przykładowa annotacja może mieć postać @route GET /example
 * Klasa kontrolera musi kończyć się na słowo Controller
 * Kontroler może być w dowolnym katalogu źródłowych
 */
class AnnotationRouter
{
    /** Absolutna ścieżka do pliku w którym znajdują się ścieżki routingu */
    private $routesFile;

    /**
     * @var array Tablica zawierająca wszystkie ścieżki routingu
     * $routes[GET][/example] = ExampleController::index
     */
    private $routes = [];

    /** @var Container */
    private $container;

    /**
     * SegmentRouter constructor.
     * @param $routesFile string Absolutna ścieżka do pliku zawierającego ścieżki routingu
     * @param Container $container
     */
    public function __construct(Container $container, $routesFile)
    {
        $this->container = $container;
        $this->routesFile = $routesFile;
        if (!is_readable($routesFile) or DEBUG) {
            $this->parseControllerAnnotations();
        }
        $this->routes = require $this->routesFile;
    }

    /**
     * Uruchamia odpowiedni kontroler na podstawie żądania
     *
     * @param $requestMethod string Metoda protokołu HTTP
     * @param $requestUri string Adres zasobu. Najczęściej $_SERVER['REQUEST_URI']
     * @return mixed W zależności co zwróci kontroller
     * @throws ControllerNotMatchedException
     * @throws ControllerNotFoundException
     */
    public function executeControllerByRequest($requestMethod, $requestUri)
    {
        $requestMethod = strtoupper($requestMethod);
        $requestUri = '/'.trim(parse_url($requestUri, PHP_URL_PATH), '/');

        if (!isset($this->routes[$requestMethod])) {
            throw new ControllerNotMatchedException(sprintf(
                "Request method (%s) was not found in routes file",
                $requestMethod
            ));
        }

        foreach ($this->routes[$requestMethod] as $request => $route) {
            if (strpos($requestUri, $request) === 0) {
                $afterRoute = substr($requestUri, strlen($request));
                $segments = explode('/', trim($afterRoute, '/'));
                list($className, $actionName) = explode('::', $route);

                try {
                    return $this->runControllerAction($className, $actionName, $segments);
                } catch (ControllerNotFoundException $exception) {
                    $this->parseControllerAnnotations();
                    return $this->runControllerAction($className, $actionName, $segments);
                }
            }
        }

        throw new ControllerNotMatchedException(
            "Router cannot match any controller"
        );
    }

    /**
     * Wyszukuje pliki kontrolerów i odczytuje z nich odpowiednią annotację route
     */
    public function parseControllerAnnotations()
    {
        // załaduj wszystkie pliki które kończą się na Controller.php
        $files = globRecursive(SOURCE_PATH . '/*Controller.php');
        foreach ($files as $file) {
            include_once $file;
        }

        // przeanalizuj annotację @route dla każdej metody w controllerze
        $this->routes = [];
        $declaredClasses = get_declared_classes();
        foreach ($declaredClasses as $class) {
            if (!preg_match("~Controller$~", $class)) {
                continue;
            }
            $reflectionClass = new ReflectionClass($class);
            $methods = $reflectionClass->getMethods();
            foreach ($methods as $method) {
                $docComment = $method->getDocComment();
                if (preg_match_all("~@route ([A-Z]+) (.*)~", $docComment, $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $match) {
                        $requestMethod = strtoupper($match[1]);
                        $requestUri = '/'.trim(trim($match[2]), '/');
                        $route = $reflectionClass->getName()."::".$method->getName();
                        $this->routes[$requestMethod][$requestUri] = $route;
                    }
                }
            }
        }

        // posortuj ścieżki, aby najdłuższe sprawdzać pierwsze
        foreach ($this->routes as &$paths) {
            uksort($paths, function ($a, $b) {
                return strlen($a) < strlen($b);
            });
        }
        unset($paths);

        // zapisz plik z ścieżkami
        exportVariable2PHPFile($this->routes, $this->routesFile);
    }

    /**
     * Uruchamia konkretną akcję kontrolera
     *
     * @param $className string Nazwa klasy kontrolera, bez przestrzeni nazw
     * @param $method string Nazwa metody kontrolera do uruchomienia
     * @param array $arguments Argumenty jakie przekazać do metody kontrollera
     * @return mixed Dobrany kontroller
     * @throws ControllerNotFoundException Kiedy nie znaleziono klasy kontrolera
     * @throws IncorrectControllerException Kiedy controller nie rozszerza klasy bazowej
     */
    private function runControllerAction($className, $method, array $arguments = [])
    {
        try {
            $reflectionClass = new ReflectionClass($className);
        } catch (ReflectionException $exception) {
            throw new ControllerNotFoundException(sprintf(
                "Controller (%s) does not exists",
                $className
            ), 0, $exception);
        }

        if ($reflectionClass->isSubclassOf(AbstractController::class) === false) {
            throw new IncorrectControllerException(sprintf(
                "Controller (%s) should extends (%s)",
                $className, AbstractController::class
            ));
        }

        $controller = $reflectionClass->newInstance($this->container);

        try {
            $reflectionMethod = $reflectionClass->getMethod($method);
        } catch (ReflectionException $exception) {
            throw new IncorrectControllerException(sprintf(
                "Method (%s) does not exists in (%s) Controller",
                $method, $className
            ), 0, $exception);
        }

        $result = $reflectionMethod->invokeArgs($controller, $arguments);

        return $result;
    }
}