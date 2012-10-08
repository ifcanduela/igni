<?php

class IgniLog
{
    const FATAL   = 10;
    const ERROR   = 8;
    const ALERT   = 6;
    const INFO    = 4;
    const DEBUG   = 2;
    const DISABLE = 0;

    protected $_levelNames = array(
            2  => '[DEBUG]',
            4  => '[INFO ]',
            6  => '[ALERT]',
            8  => '[ERROR]',
            10 => '[FATAL]',
        );

    /**
     * Log file name in use.
     * 
     * @var string
     */
    protected $_log_file_name;

    /**
     * Log file handle in use.
     * 
     * @var resource
     */
    protected $_log_file;

    /**
     * Operational level.
     * 
     * @var int
     */
    protected $_level;

    /**
     * Constructor.
     * 
     * @param string $log_dir Location of the log files
     * @param int $level Minimum logging level
     */
    public function __construct($log_dir, $level)
    {
        $log_dir = trim($log_dir, '/\\');

        $this->_level = $level;

        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0777, true);
        }

        $filename = $log_dir . DIRECTORY_SEPARATOR . 'log_' . date('Y_m_d') . '.txt';

        if (file_exists($filename) && !is_writable($filename)) {
            throw new Exception("Log file is not writable: $filename");
        }

        if (!$this->_log_file = fopen($filename, 'a')) {
            throw new Exception("Could not open log file to append: $filename");
        }
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        if ($this->_log_file) {
            fclose($this->_log_file);
        }
    }

    /**
     * Logs messages to a file.
     * 
     * @param string $message Message to be logged
     * @param  int $level Event level
     * @return bool Whether the event was logged or not
     */
    public function log($message, $level)
    {
        if ($level >= $this->_level) {
            $entry = $this->_levelNames[$level] . ' ' . str_pad(date('Y-m-d H:m:s'), 20) . $message . PHP_EOL;

            if (false === fwrite($this->_log_file, $entry)) {
                throw new Exception("Could not write to log file.");
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Logs debug messages.
     * 
     * @param string $message Message to log
     * @return bool Whether the event was logged or not
     */
    public function debug($message)
    {
        return $this->log($message, self::DEBUG);
    }

    /**
     * Logs informational messages.
     * 
     * @param string $message Message to log
     * @return bool Whether the event was logged or not
     */
    public function info($message)
    {
        return $this->log($message, self::INFO);
    }

    /**
     * Logs warnings and alerts.
     * 
     * @param string $message Message to log
     * @return bool Whether the event was logged or not
     */
    public function alert($message)
    {
        return $this->log($message, self::ALERT);
    }

    /**
     * Logs recoverable errors.
     * 
     * @param string $message Message to log
     * @return bool Whether the event was logged or not
     */
    public function error($message)
    {
        return $this->log($message, self::ERROR);
    }

    /**
     * Logs non-recoverable errors.
     * 
     * @param string $message Message to log
     * @return bool Whether the event was logged or not
     */
    public function fatal($message)
    {
        return $this->log($message, self::FATAL);
    }

    /**
     * Sets the logging level.
     * 
     * @param int $level Minimum level to log events
     */
    public function setLevel($level)
    {
        $this->_level = $level;
    }

    /**
     * Get the current logging level.
     * 
     * @return int The current logging level
     */
    public function getLevel()
    {
        return $this->_level;
    }
}
