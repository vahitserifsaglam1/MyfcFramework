<?php



/**
 * Class Route
 * @package Flame\Router
 */
class Route
{

    private static $_singleton;
    private $_prefix;
    private $_routeCollection = array();
    private $_error;
    private $_default;


    /**
     * @return Route
     */
    public static function this()
    {
        if (self::$_singleton === null) {
            self::$_singleton = false;
            self::$_singleton = new self;
        }

        return self::$_singleton;
    }


    /**
     * @param $pattern
     * @param $action
     *
     * @return Route
     */
    public static function get($pattern, $action)
    {

        return self::this()
            ->setGet($pattern, $action);
    }


    /**
     * @param $pattern
     * @param $action
     *
     * @return Route
     */
    public static function any($pattern,$action)
    {
        return self::this()
            ->setAny($pattern,$action);
    }


    /**
     * @param $pattern
     * @param $action
     *
     * @return Route
     */
    public static function post($pattern, $action)
    {
        return self::this()
            ->setPost($pattern, $action);
    }


    /**
     * @param $code
     * @param $action
     *
     * @return Route
     */
    public static function error($code, $action)
    {
        return self::this()
            ->setError($code, $action);
    }


    /**
     * @param $action
     *
     * @return Route
     */
    public static function notFound($action)
    {

        return self::this()
            ->setNotFound($action);
    }


    /**
     * @param $prefix
     * @param $routes
     *
     * @return Route
     */
    public static function prefix($prefix, $routes)
    {
        return self::this()
            ->addPrefix($prefix, $routes);
    }


    /**
     * @param $name
     *
     * @return $this
     * @throws \Exception
     */
    public function setName($name)
    {
        if (!array_key_exists($name, $this->_routeCollection)) {
            end($this->_routeCollection);

            list(, $value) = each($this->_routeCollection);

            array_pop($this->_routeCollection);

            $this->_routeCollection[$name] = $value;

            reset($this->_routeCollection);

            return $this;
        }

        throw new \Exception('Route Key name already exists');
    }


    /**
     * @param $pre
     * @param $routes
     *
     * @return $this
     */
    public function addPrefix($pre, $routes)
    {
        $this->_prefix = $pre;
        call_user_func($routes);
        $this->_prefix = null;
        return $this;
    }


    /**
     * @param $name
     */
    public function filter($name)
    {
        end($this->_routeCollection);

        list($key) = each($this->_routeCollection);

        $this->_routeCollection[$key]['filter'] = func_get_args();

        reset($this->_routeCollection);
    }


    /**
     * @param $pattern
     * @param $action
     *
     * @return Route
     */
    public function setGet($pattern, $action)
    {
        $_parse = $this->parseParamPattern($pattern);
        return $this->setCollection(array(
            'pattern' => $_parse['pattern'],
            'callMake' => $action,
            'method' => array('GET','HEAD'),
            'param' => $_parse['param']
        ));

    }


    /**
     * @param $pattern
     * @param $action
     *
     * @return Route
     */
    public function setAny($pattern,$action)
    {
        $_parse = $this->parseParamPattern($pattern);
        return $this->setCollection(array(
            'pattern' => $_parse['pattern'],
            'callMake' => $action,
            'method' => array('GET','HEAD','POST','DELETE','PUT'),
            'param' => $_parse['param']
        ));
    }


    /**
     * @param $pattern
     * @param $action
     *
     * @return Route
     */
    public function setPost($pattern, $action)
    {
        $_parse = $this->parseParamPattern($pattern);

        return $this->setCollection(array(
            'pattern' => $_parse['pattern'],
            'callMake' => $action,
            'method' => 'POST',
            'param' => $_parse['param']
        ));
    }


    /**
     * @param $collection
     *
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->_routeCollection[] = array(
            'pattern' => $collection['pattern'],
            'callMake' => $collection['callMake'],
            'method' => $collection['method'],
            'param' => $collection['param']
        );

        return $this;
    }


    /**
     * @param int $code
     * @param     $action
     *
     * @return $this
     */
    public function setError($code = 404, $action)
    {
        $this->_error[$code] = array(
            'callMake' => $action
        );

        return $this;
    }


    /**
     * @param $action
     *
     * @return $this
     */
    public function setNotFound($action)
    {
        $this->_error['notFound'] = array(
            'callMake' => $action
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteCollection()
    {
        return $this->_routeCollection;
    }


    /**
     * @return array
     */
    public function getError()
    {
        return $this->_error;
    }


    /**
     * @param $route
     *
     * @return mixed|string
     */
    private function cleanPattern($route)
    {
        $_pattern = array(
            '#^\/*(.+?)\/*$#',
            '#(.+?)\/+(.+?)#'
        );
        $_replace = array('$1', '$1/$2');
        if (mb_strlen($route) === 0) {
            return ($this->_default === null) ? '/' : $this->_default;
        }
        return preg_replace($_pattern, $_replace, $route);

    }


    /**
     * @param $pattern
     *
     * @return array
     */
    private function parseParamPattern($pattern)
    {
        if (preg_match_all('/\{(.+?):(.+?)\}/', $pattern, $param)) {
            $_pattern = str_replace($param[0], $param[2], $pattern);
            $_param = array_combine($param[1], $param[2]);
            return array(
                'pattern' => $this->cleanPattern($this->_prefix . $_pattern),
                'param' => $_param
            );
        }

        return array(
            'pattern' => $this->cleanPattern($this->_prefix . $pattern),
            'param' => array()
        );
    }


}