<?php

/**
 * Log levels
 */
abstract class logLevel
{
  const INFO  = 0;
  const WARN  = 1;
  const ERROR = 2;
  const DEBUG = 3;

  const strInfo   = 'INFO';
  const strWarn   = 'WARNING';
  const strError  = 'ERROR';
  const strDebug  = 'DEBUG';
}

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
   * Log level
   */
  private static $logLevel = null;

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

  public static function getLogLevel()
  {
    return self::$logLevel;
  }

  public static function setLogLevel($logLevel)
  {
    self::$logLevel = $logLevel;
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

  private static function buildLogMessage($level, $message)
  {
  	if (null !== self::getDate()) {
  		self::setDate();
  	}

  	//return self::getFormattedDate() . ' - ' .$level . ' - ' . $message;
    return self::getFormattedDate() . " - {$level} - {$message}";
  }

  public static function info($message)
  {
    if (self::getLogLevel() >= logLevel::INFO) {
      return self::log(logLevel::strInfo, $message);
    }
  }

  public static function warn($message)
  {
    if (self::getLogLevel() >= logLevel::WARN) {
      return self::log(logLevel::strWarn, $message);
    }
  }

  public static function error($message)
  {
    if (self::getLogLevel() >= logLevel::ERROR) {
      return self::log(logLevel::strError, $message);
    }
  }

  public static function debug($message)
  {
    if (self::getLogLevel() >= logLevel::DEBUG) {
      return self::log(logLevel::strDebug, $message);
    }
  }

  private static function log($level, $message)
  {
  	$logger = fopen(self::getLogFile(), 'a');
  	$result = (bool)fwrite($logger, self::buildLogMessage($level, $message) . PHP_EOL);
  	fclose($logger);
  	return $result;
  }
}
