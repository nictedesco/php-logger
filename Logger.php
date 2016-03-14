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
   * Log level.
   */
  private static $logLevel = null;

  /**
   * Log buffer, used if log file is not set or attempt to write log fails.
   */
  private static $logBuffer = array();

  /**
   * Getters and setters.
   */
  public static function getLogFile()
  {
  	return self::$logFile;
  }

  public static function setLogFile($logFile)
  {
  	self::$logFile = $logFile;
  }

  public static function getLogLevel()
  {
    return self::$logLevel;
  }

  public static function setLogLevel($logLevel)
  {
    switch ($logLevel) {
      case LogLevel::strInfo:
        self::$logLevel = LogLevel::INFO;
        break;
      case LogLevel::strWarn:
        self::$logLevel = LogLevel::WARN;
        break;
      case LogLevel::strError:
        self::$logLevel = LogLevel::ERROR;
        break;
      case LogLevel::strDebug:
        self::$logLevel = LogLevel::DEBUG;
        break;
      default:
        self::$logLevel = $logLevel;
        break;
    }
  }

  private static function getLogBuffer()
  {
    return self::$logBuffer;
  }

  private static function addMessageToBuffer($message)
  {
     return array_push(self::$logBuffer, $message);
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
   * Get UTC timezone formatted date.
   *
   * @return string date
   *    Formatted date (Year.Month.Day Hour:Minute:Second TimeZone)
   */
  public static function getFormattedDate()
  {
  	return date_format(date_create(null, timezone_open('UTC')), 'Y.m.d H:i:s e');
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
    return self::getFormattedDate() . " - [{$level}] - {$message}";
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
    self::checkFilePermissions();

    if (self::getLogFile() == null || self::getLogFile() == '') {
      self::addMessageToBuffer(self::buildLogMessage($level, $message));
      return false;
    }

    try {
      $logger = fopen(self::getLogFile(), 'a');

      foreach (self::$logBuffer as $key => $value) {
        $result = (bool)fwrite($logger, $value . PHP_EOL);
      }

      $result = (bool)fwrite($logger, self::buildLogMessage($level, $message) . PHP_EOL);
    } catch (Exception $e) {
      $result = false;
      self::addMessageToBuffer(self::buildLogMessage($level, $message));
    } finally {
      fclose($logger);
    }
  	return $result;
  }

  /**
   * Check file permissions and try to set read and write permissions if not set
   */
  private static function checkFilePermissions()
  {
    if (!is_readable(self::getLogFile()) && file_exists(self::getLogFile())) {
      chmod(self::getLogFile(), 0600);
    }
  }
}
