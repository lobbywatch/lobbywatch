<?php

include_once dirname(__FILE__) . '/' . 'system_utils.php';

class RequestRoute
{
    /**
     * @var string
     */
    public $functionName;

    /**
     * @var array
     */
    public $callback;

    /**
     * @var string[]
     */
    public $parameterNames;

    /**
     * @param string $functionName
     * @param array $callback
     * @param string[] $parameterNames
     */
    public function __construct($functionName, $callback, $parameterNames = array())
    {
        $this->functionName = $functionName;
        $this->callback = $callback;
        $this->parameterNames = $parameterNames;
    }

    /**
     * @param string $functionName
     * @param object $object
     * @param string $methodName
     * @param string[] $parameterNames
     * @return RequestRoute
     */
    public static function CreateFromMethod($functionName, $object, $methodName, $parameterNames = array()) {
        assert(is_object($object));
        return new RequestRoute($functionName, array($object, $methodName), $parameterNames);
    }

}

class RequestRouter
{
    /**
     * @var string
     */
    private $functionParameterName;

    /**
     * @var RequestRoute[]
     */
    private $routes;

    /**
     * @param RequestRoute[] routes
     * @param string $functionParameterName
     */
    public function __construct($routes, $functionParameterName)
    {
        $this->routes = array();
        foreach ($routes as $route)
            $this->routes[$route->functionName] = $route;
        $this->functionParameterName = $functionParameterName;
    }

    /**
     * @param array $parameters
     * @return mixed Returns value from a callback
     * @throws Exception
     */
    public function RouteRequest($parameters)
    {
        $route = $this->FindRoute($parameters);
        return call_user_func_array($route->callback, $this->GetParameterValues($parameters, $route->parameterNames));
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function HandleRequest($parameters)
    {
        try{
            $result = $this->RouteRequest($parameters);
            $status = 'OK';
        }
        catch (Exception $e)
        {
            $result = $e->getMessage();
            $status = 'error';
        }
        return SystemUtils::ToJSON(
            array(
                'status' => $status,
                'result' => $result
            ));
    }

    /**
     * @param array $parameters
     * @return RequestRoute
     * @throws Exception
     */
    private function FindRoute($parameters)
    {
        if (!isset($parameters[$this->functionParameterName]))
            throw new Exception('Function name parameter ' . $this->functionParameterName . ' is not set');

        $functionName = $parameters[$this->functionParameterName];
        if (!isset($this->routes[$functionName]))
            throw new Exception('Unknown function name');

        return $this->routes[$functionName];
    }

    /**
     * @param array $parameters
     * @param string[] $parameterNames
     * @return string[]
     * @throws Exception
     */
    private function GetParameterValues($parameters, $parameterNames)
    {
        $result = array();
        foreach ($parameterNames as $parameterName)
        {
            if (!isset($parameters[$parameterName]))
                throw new Exception('Missing parameter ' . $parameterName);
            $result[] = $parameters[$parameterName];
        }
        return $result;
    }
}
