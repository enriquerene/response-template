<?php

namespace RESTfulTemplate;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/ResponseTemplate.php';

$_SERVER['HTTP_HOST'] = 'myserver';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['PATH_INFO'] = '/';
$_SERVER['QUERY_STRING'] = null;
final class ResponseTemplateBasicTest extends TestCase
{
    /**
     * ResponseTemplate requires an valid HTTP Status code.
     */
	public function testValidHTTPStatusCodeIsRequired(): void
	{
        $message = '';
        try {
            $rest = new ResponseTemplate(1);
        } catch(\Exception $e) {
            $message = $e->getMessage();
        }
        $this->assertSame(ResponseTemplate::STATUS_CODE_EXCEPTION_MESSAGE, $message);
    }

    /**
     * Build method without arguments must return null data property, status property including code and message, and self key inside links property.
     */
	public function testBuildDataNullStatusOk(): void
	{
        $expectedResponse = [
            'status' => [
                'code' => 200,
                'message' => 'Ok.'
            ],
            'data' => null,
            'links' => [
                'self' => [
                    'url' => 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'],
                    'method' => $_SERVER['REQUEST_METHOD']
                ]
            ]
        ];
        $rest = new ResponseTemplate(200);
        $actualResponse = $rest->build();
        $this->assertSame($expectedResponse, $actualResponse);
    }

    /**
     * Build method accepts array as data property.
     */
	public function testBuildDataArrayStatusOk(): void
	{
        $data = [['foo' => 'bar'], ['foo' => 'baz']];
        $expectedResponse = [
            'status' => [
                'code' => 200,
                'message' => 'Ok.'
            ],
            'data' => $data,
            'links' => [
                'self' => [
                    'url' => 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'],
                    'method' => $_SERVER['REQUEST_METHOD']
                ]
            ]
        ];
        $rest = new ResponseTemplate(200);
        $actualResponse = $rest->build($data);
        $this->assertSame($expectedResponse, $actualResponse);
    }

    /**
     * It can set link property custom key append only query string (functionality inspired from pagination in links).
     */
	public function testSetLinkCustomKey(): void
	{
        $expectedResponse = [
            'status' => [
                'code' => 200,
                'message' => 'Ok.'
            ],
            'data' => null,
            'links' => [
                'self' => [
                    'url' => 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'],
                    'method' => $_SERVER['REQUEST_METHOD']
                ],
                'next' => [
                    'url' => 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'] . '?page=2',
                    'method' => 'GET'
                ]
            ]
        ];
        $rest = new ResponseTemplate(200);
        $rest = $rest->setLink('next', 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'] . '?page=2', 'GET');
        $actualResponse = $rest->build();
        $this->assertSame($expectedResponse, $actualResponse);
    }

    /**
     * It can verify if a link key is already defined.
     */
	public function testLinkKeyIsDefined(): void
	{
        $expected = true;
        $rest = new ResponseTemplate(200);
        $rest = $rest->setLink('next', 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['PATH_INFO'] . '?page=2', 'GET');
        $actual = $rest->isLinkDefined('next');
        $this->assertSame($expected, $actual);
    }

    /**
     * It can verify if a link key is not defined yet.
     */
	public function testLinkKeyIsNotDefined(): void
	{
        $expected = false;
        $rest = new ResponseTemplate(200);
        $actual = $rest->isLinkDefined('next');
        $this->assertSame($expected, $actual);
    }
}
