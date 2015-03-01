<?php




use \Http\Request;

/**
 * Class Router
 * @package Flame\Router
 */
class Router
{
    private static $_singleton;
    private $_route;

    private $_correct;


    public static function this()
    {
        if (self::$_singleton === null) {
            self::$_singleton = false;
            self::$_singleton = new self;
        }

        return self::$_singleton;
    }


    public function getUrl($parse = true)
    {
        $url = Request::this()
            ->getUri();
        return ($parse === true) ? array_filter(explode('/', $url)) : $url;
    }


    public function siftingCollection()
    {
        $this->_route = Route::this();
        $collection = $this->_route->getRouteCollection();
        $errorCollection = $this->_route->getError();

        $match = $this->validatePattern($collection);
        if ($match === false) {
            $this->_correct = $errorCollection['notFound'];
        } elseif (is_array($match)) {
            $this->_correct = $match;
        }

    }


    /**
     * @param $match
     *
     * @return bool
     */
    private function validatePattern($match)
    {
        $requestMethod = Request::this()->getRequestMethod();
        $url = $this->getUrl(false);
        foreach ($match as $data) {
            $key = null;
            if ($url === $data['pattern'] || preg_match('#^' . $data['pattern'] . '$\b#', $url, $key) === 1) {

                if ($requestMethod === $data['method'] || (is_array($data['method']) &&
                        array_key_exists($requestMethod, array_flip($data['method'])))
                ) {

                    if ($key !== null) {
                        unset($key[0]);
                        $data['param'] = array_combine(array_keys($data['param']), $key);
                    }
                    return $data;
                } else {
                    continue;
                }


            }


        }


        return false;
    }


    public function getCorrectMake()
    {
        if ($this->_correct === null) {
            self::siftingCollection();
        }
        return $this->_correct['callMake'];
    }


    public function getCorrectParam()
    {
        if ($this->_correct === null) {
            $this->siftingCollection();
        }

        return $this->_correct['param'];
    }


    public function getParam($name)
    {
        if ($this->_correct === null) {
            $this->siftingCollection();
        }

        return (array_key_exists($name, $this->_correct['param'])) ? $this->_correct['param'][$name] : null;
    }


}