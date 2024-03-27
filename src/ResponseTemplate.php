<?php

namespace RESTfulTemplate;

use \Exception;

class ResponseTemplate
{
    /**
     * @see https://developer.mozilla.org/docs/Web/HTTP/Status
     */
    const STATUS_MAP = [
        /* informational responses */
        100 => "Continue",
        101 => "Switching Protocol",
        102 => "Processing",
        103 => "Early Hints",
        /* successful responses */
        200 => "Ok",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status",
        208 => "Multi-Status",
        226 => "IM Used",
        /* redirects */
        300 => "Multiple Choice",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "unused ",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        /* client errors */
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Payload Too Large",
        414 => "URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Requested Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "I'm a teapot",
        421 => "Misdirected Request",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        425 => "Too Early",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        451 => "Unavailable For Legal Reasons",
        /* server errors */
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        510 => "Not Extended",
        511 => "Network Authentication Required"
    ];
	const SELF_LINK_EXCEPTION_MESSAGE = 'The "self" property should contain a defined URL in links.';
    const ARG_TYPE_EXCEPTION_MESSAGE = 'Given argument missmatches with required type.';
    const STATUS_CODE_EXCEPTION_MESSAGE = 'The HTTP status code should be one of the following list: ';

	/**
	 * @var int
	 */
	private $statusCode;

	/**
	 * @var array
	 */
	private $links = [];

	/**
	 * @var string
	 */
	private $basename = '';

	function __construct ( int $statusCode )
	{
        if (
            !in_array(
                $statusCode,
                array_keys( self::STATUS_MAP ),
                true
            )
        ) throw new Exception(
           self::STATUS_CODE_EXCEPTION_MESSAGE . implode(', ', array_keys(self::STATUS_MAP))
        );

		$this->statusCode = $statusCode;
		$this->basename = $this->getBasename();
        $selfUrl = $this->basename . $this->getRequestPath();
        $selfQueryString = $this->getRequestQueryString();
        if (!empty($selfQueryString)) {
            $selfUrl .= '?' . $this->getRequestQueryString();
        }
        $this->setLink('self', $selfUrl, $this->getRequestMethod());
	}

    /**
     * Builds an associative array based on ResponseTemplate configuration, status code and given data and links.
     *
     * @param Type $data Data to be attached to data property of response template.
     * @return Description
     */
	public function build ( array $data = [] ): array
	{
        if (!is_array($data)) throw new Exception(self::ARG_TYPE_EXCEPTION_MESSAGE); 
		return [
			"status" => $this->getStatus(),
			"data" => (count($data) > 0 ? $data: null),
			"links" => $this->links
		];
    }

    /**
     * Get status structure as array. Status is an structure containing "code" and "message" fields.
     *
     * @return Status associative array.
     */
	public function getStatus (): array
	{
        return [
			"code" => $this->statusCode,
			"message" => $this->getStatusMessage().'.'
		];
    }

    /**
     * Get HTTP status message accordingly given status code.
     *
     * @return HTTP Message associated to given status code.
     */
	public function getStatusMessage (): string
	{
        return self::STATUS_MAP[ $this->statusCode ];
    }

    /**
     * Set a Link to response template.
     *
     * @param string $key Link key name
     * @param string $url Link url
     * @param string $method Link method
     * @return Instance of ResponseTemplate.
     */
	public function setLink( string $key, string $url, string $method = 'GET' ): ResponseTemplate
	{
        $this->links[$key] = [
            'url' => $url,
            'method' => $method
        ];
		return $this;
	}

    /**
     * Get basename from server. Protocol is included.
     *
     * @return Basename string including protocol.
     */
	public function getBasename (): string
	{
		$protocol = ( empty( $_SERVER[ "HTTPS" ] ) ? "http" : "https" );
		$host = $_SERVER[ "HTTP_HOST" ];
		return "$protocol://$host";
	}

    /**
     * Get HTTP request query string.
     *
     * @return HTTP request query string.
     */
	public function getRequestQueryString(): string
	{
        return (empty($_SERVER['QUERY_STRING'])? '':$_SERVER['QUERY_STRING']);
    }

    /**
     * Get HTTP request method.
     *
     * @return HTTP request method.
     */
	public function getRequestMethod(): string
	{
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get HTTP request path.
     *
     * @return HTTP request path.
     */
	public function getRequestPath(): string
	{
        return $_SERVER['PATH_INFO'];
    }

    /**
     * Checks if a key link is defined in response template.
     *
     * @param string $key The key of links in response template.
     * @return True if the key is defined, false otherwise.
     */
	public function isLinkDefined ( string $key ): bool
	{
		return ! empty( $this->links[ $key ] );
	}
}

