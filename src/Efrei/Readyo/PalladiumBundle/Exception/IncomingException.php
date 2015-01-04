<?php
	
namespace Efrei\Readyo\PalladiumBundle\Exception;

class IncomingException extends \Exception
{

	private $data;

	public function __construct($data, $code = 0, Exception $previous = null) {

		$this->data = $data;
		$message = "Bad format for incoming message.";
		parent::__construct($message, $code, $previous);

	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}