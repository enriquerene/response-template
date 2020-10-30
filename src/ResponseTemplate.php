<?php

namespace App\Utils\Http;

use Exception;
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
	 * @var array|null
	 *
	 */
	// private $data = null;

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
	public function build ( $data = null )
	{
		if ( ! $this->isLinkDefined( "self" ) )
			throw new Exception( self::SELF_LINK_EXCEPTION_MESSAGE );

		return [
			"status" => $this->getStatus(),
			"data" => $data,
			"links" => $this->links
		];
    }

	public function getStatus ()
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

	public function setLinks( array $links )
	{
		foreach ( $links as $key => $value )
		{
			$this->setLink( $key, $value );
		}
		return $this;
	}

	public function getBasename ()
	{
		$protocol = ( empty( $_SERVER[ "HTTPS" ] ) ? "http" : "https" );
		$host = $_SERVER[ "HTTP_HOST" ];
		return "$protocol://$host";
	}

	public function getStatusMap ()
	{
		return $this->STATUS_MAP;
	}

	public function isLinkDefined ( string $key )
	{
		return ! empty( $this->links[ $key ] );
	}
}

