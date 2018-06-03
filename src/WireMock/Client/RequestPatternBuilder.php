<?php

namespace WireMock\Client;

use WireMock\Matching\RequestPattern;
use WireMock\Matching\UrlMatchingStrategy;

class RequestPatternBuilder
{
    private $_method;
    private $_urlMatchingStrategy;
    private $_headers = array();
    private $_cookies = array();
    private $_queryParameters = array();
    private $_bodyPatterns = array();
    /** @var BasicCredentials */
    private $_basicCredentials;

    /**
     * @param string $method
     * @param UrlMatchingStrategy $urlMatchingStrategy
     */
    public function __construct($method, $urlMatchingStrategy)
    {
        $this->_method = $method;
        $this->_urlMatchingStrategy = $urlMatchingStrategy;
    }

    /**
     * @param string $headerName
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return RequestPatternBuilder
     */
    public function withHeader($headerName, ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_headers[$headerName] = $valueMatchingStrategy->toArray();
        return $this;
    }

    /**
     * @param string $cookieName
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return RequestPatternBuilder
     */
    public function withCookie($cookieName, ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_cookies[$cookieName] = $valueMatchingStrategy->toArray();
        return $this;
    }

    /**
     * @param string $headerName
     * @return RequestPatternBuilder
     */
    public function withoutHeader($headerName)
    {
        $this->_headers[$headerName] = array('absent' => true);
        return $this;
    }

    /**
     * @param string $name
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return RequestPatternBuilder
     */
    public function withQueryParam($name, ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_queryParameters[$name] = $valueMatchingStrategy->toArray();
        return $this;
    }

    /**
     * @param ValueMatchingStrategy $valueMatchingStrategy
     * @return RequestPatternBuilder
     */
    public function withRequestBody(ValueMatchingStrategy $valueMatchingStrategy)
    {
        $this->_bodyPatterns[] = $valueMatchingStrategy->toArray();
        return $this;
    }

    /**
     * @param string $username
     * @param string $password
     * @return RequestPatternBuilder
     */
    public function withBasicAuth($username, $password)
    {
        $this->_basicCredentials = new BasicCredentials($username, $password);
        return $this;
    }

    /**
     * @return RequestPattern
     */
    public function build()
    {
        return new RequestPattern(
            $this->_method,
            $this->_urlMatchingStrategy,
            $this->_headers,
            $this->_cookies,
            $this->_bodyPatterns,
            $this->_queryParameters,
            $this->_basicCredentials
        );
    }
}
