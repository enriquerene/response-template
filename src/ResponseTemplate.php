<?php

namespace RESTfulTemplate;

use \Exception;
$statusMapFromFile = require "./StatusMap.php";

class ResponseTemplate
{
	const STATUS_MAP = $statusMapFromFile;
	const SELF_LINK_EXCEPTION_MESSAGE = "A propriedade \"self\" deve possuir um URL definida em links.";
	const STATUS_CODE_EXCEPTION_MESSAGE = "O código de estado HTTP deve ser um entre os seguintes: [" . implode( ", ", array_keys( $this->STATUS_MAP ) ) . "].";

	/**
	 * @var int
	 *
	 */
    private $statusCode;

	/**
	 * @var array
	 *
	 */
	private $links = [];


	function __construct ( int $statusCode )
	{
		if ( ! in_array( $statusCode, array_keys( $this->STATUS_MAP ), true ) )
			throw new Exception( self::STATUS_CODE_EXCEPTION_MESSAGE );

		$this->statusCode = $statusCode;
		$url = $this->getBasename();

	}

    /**
     * @param array $data Requested data
	 *
     */
	public function build ( $data = null ): array
	{
		return [
			"status" => $this->getStatus(),
			"data" => $data,
			"links" => $this->links
		];
    }

	public function getStatus (): array
	{
        return [
			"code" => $this->statusCode,
			"message" => $this->getStatusMessage()
		];
    }

	public function getStatusMessage ()
	{
        return $this->STATUS_MAP[ $this->statusCode ];
    }

	public function setLink( string $key, $value )
	{
		$this->links[ $key ] = $value;
		return $this;
	}

	public function setLinks( array $links ): ResponseTemplate
	{
		foreach ( $links as $key => $value )
		{
			$this->setLink( $key, $value );
		}
		return $this;
	}

	public function getBasename (): string
	{
		$protocol = ( empty( $_SERVER[ "HTTPS" ] ) ? "http" : "https" );
		$host = $_SERVER[ "HTTP_HOST" ];
		return "$protocol://$host";
	}

	public function getStatusMap (): array
	{
		return $this->STATUS_MAP;
	}

	public function isLinkDefined ( string $key ): bool
	{
		return ! empty( $this->links[ $key ] );
	}
}

