<?php

/**
 * Basic log class
 */
final class Logger
{
  /**
   * File used to log messages
   */
  private static $logFile = null;

  /**
   * Actual Date and time
   */
  private static $now = new DateTime(null, new DateTimeZone('UTC'));

  /**
   * Getters and setters
   */
  public static function getLogFile()
  {
  	return self::$logFile;
  }

  public static function setLogFile($logFile)
  {
  	self::$logFile = $logFile;
  }

  public static function getDate()
  {
  	return self::$date->format('Y.m.d H:i:s P');
  }

  /**
   * Constructor
   */
  private function __construct() {}

  /**
   * Deconstructor
   */
  private function __destruct() {}

  public static function log($message)
  {
  	$logger = fopen(self::getLogFile(), 'a');
  	$result = (bool)fwrite($logger, $message . PHP_EOL);
  	fclose($logger);
  	return $result;
  }
}
