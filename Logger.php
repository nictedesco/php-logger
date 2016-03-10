<?php

/**
 * Log levels.
 */
abstract class LogLevel
{
  // Values to use to set log level
  const INFO  = 0;
  const WARN  = 1;
  const ERROR = 2;
  const DEBUG = 3;

  // Values used in log messages
  const strInfo   = 'INFO';
  const strWarn   = 'WARNING';
  const strError  = 'ERROR';
  const strDebug  = 'DEBUG';
}

/**
 * Basic log class.
 */
class Logger
{
  /**
   * File used to log messages.
   */
  private static $logFile = null;

  /**
   * Actual Date and time.
   */
  private static $date = null;

  /**
   * Log level.
   */
  private static $logLevel = null;

  /**
   * Getters and setters.
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
   * Constructor.
   */
  private function __construct() {}

  /**
   * Deconstructor.
   */
  private function __destruct() {}

  /**
   * Get UTC formatted date.
   *
   * @return string date
   *    Formatted date (Year.Month.Day Hour:Minute:Second Diff to GMT)
   */
  public static function getFormattedDate()
  {
  	return date_format(date_create(null, timezone_open('UTC')), 'Y.m.d H:i:s P');
  }

  /**
   * Build a log message with level selected, message and time.
   *
   * @param logLevel $level
   *    Log level
   * @param string $message
   *    Messaeg to log
   *
   * @return string logMessage
   *    Message to write in log file
   */
  private static function buildLogMessage($level, $message)
  {
  	if (null !== self::getDate()) {
  		self::setDate();
  	}

    return self::getFormattedDate() . " - {$level} - {$message}";
  }

  /**
   * Log message with info level.
   *
   * @param string $message
   *    Message to log
   *
   * @return bool result
   *    Log result, true if log was successfully written
   */
  public static function info($message)
  {
    if (self::getLogLevel() >= LogLevel::INFO) {
      return self::log(LogLevel::strInfo, $message);
    }
  }

  /**
   * Log message with warning level.
   *
   * @param string $message
   *    Message to log
   *
   * @return bool result
   *    Log result, true if log was successfully written
   */
  public static function warn($message)
  {
    if (self::getLogLevel() >= LogLevel::WARN) {
      return self::log(LogLevel::strWarn, $message);
    }
  }

  /**
   * Log message with error level.
   *
   * @param string $message
   *    Message to log
   *
   * @return bool result
   *    Log result, true if log was successfully written
   */
  public static function error($message)
  {
    if (self::getLogLevel() >= LogLevel::ERROR) {
      return self::log(LogLevel::strError, $message);
    }
  }

  /**
   * Log message with debug level.
   *
   * @param string $message
   *    Message to log
   *
   * @return bool result
   *    Log result, true if log was successfully written
   */
  public static function debug($message)
  {
    if (self::getLogLevel() >= LogLevel::DEBUG) {
      return self::log(LogLevel::strDebug, $message);
    }
  }

  /**
   * Write log message in log file.
   *
   * @param logLevel $level
   *    Log level
   * @param string $message
   *    Message to log in file
   *
   * @return bool result
   *    Log result, true if log was successfully written
   */
  private static function log($level, $message)
  {
  	$logger = fopen(self::getLogFile(), 'a');
  	$result = (bool)fwrite($logger, self::buildLogMessage($level, $message) . PHP_EOL);
  	fclose($logger);
  	return $result;
  }
}
