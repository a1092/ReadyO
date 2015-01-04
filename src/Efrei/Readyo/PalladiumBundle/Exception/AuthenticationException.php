<?php
	
namespace Efrei\Readyo\PalladiumBundle\Exception;

class AuthenticationException extends \Exception
{

	public function __construct($code = 0, Exception $previous = null) {

		$message = "The client is not authenticated.";

		parent::__construct($message, $code, $previous);
	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}