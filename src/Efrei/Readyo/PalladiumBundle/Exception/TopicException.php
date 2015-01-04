<?php
	
namespace Efrei\Readyo\PalladiumBundle\Exception;

class TopicException extends \Exception
{

	public function __construct($topicPath, $code = 0, Exception $previous = null) {

		$message = "No topic was founded for the path '".$topicPath."'.";
		parent::__construct($message, $code, $previous);

	}

	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}