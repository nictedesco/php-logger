<?php

/**
 * Basic log class
 */
class Logger
{
  /**
   * File used to log messages
   */
  private static $logFile = null;

  /**
   * Actual Date and time
   */
  private static $date = null;

  /**
   * Getters and setters
   */
  public static function getLogFile()
  {
  	return self::$logFile;
  }

  public function setLogFile($logFile)
  {
  	self::$logFile = $logFile;
  }

  public static function getDate()
  {
    return self::$date;
  }

  private static function setDate()
  {
    self::$date = date_create(null, timezone_open('UTC'));
  }

  /**
   * Constructor
   */
  private function __construct() {}

  /**
   * Deconstructor
   */
  private function __destruct() {}

  /**
   * Get date in format: Year.Month.Day Hour:Minute:Second Diff to GMT
   */
  public static function getFormattedDate()
  {
  	return date_format(date_create(null, timezone_open('UTC')), 'Y.m.d H:i:s P');
  }

  private static function buildLogMessage($message)
  {
  	if (null !== self::getDate()) {
  		self::setDate();
  	}

  	return self::getFormattedDate() . ' - ' . $message;
  }

  public static function log($message)
  {
  	$logger = fopen(self::getLogFile(), 'a');
  	$result = (bool)fwrite($logger, self::buildLogMessage($message) . PHP_EOL);
  	fclose($logger);
  	return $result;
  }
}
