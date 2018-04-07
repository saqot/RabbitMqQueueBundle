<?php

namespace Saq\RabbitMqQueueBundle\Handler;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * class:  MqLockHandler
 * -----------------------------------------------------
 * @author  Saqot (Mihail Shirnin) <saqott@gmail.com>
 * @package  Saq\RabbitMqQueueBundle\Service
 * -----------------------------------------------------
 * 07.04.2018
 */
class MqLockHandler
{
	private $file;
	private $handle;

	public function __construct($name, $lockPath = null)
	{
		$lockPath = $lockPath ?: sys_get_temp_dir();

		if (!is_dir($lockPath)) {
			$fs = new Filesystem();
			$fs->mkdir($lockPath);
		}

		if (!is_writable($lockPath)) {
			throw new IOException(sprintf('The directory "%s" is not writable.', $lockPath), 0, null, $lockPath);
		}

		$this->file = sprintf('%s/sf.%s.%s.lock', $lockPath, preg_replace('/[^a-z0-9\._-]+/i', '-', $name), hash('sha256', $name));
	}

	public function lock($blocking = false)
	{
		if ($this->handle) {
			return true;
		}

		$error = null;

		set_error_handler(function ($errno, $msg) use (&$error) {
			$error = $errno . ':' . $msg;
		});

		if (!$this->handle = fopen($this->file, 'r')) {
			if ($this->handle = fopen($this->file, 'x')) {
				chmod($this->file, 0444);
			} elseif (!$this->handle = fopen($this->file, 'r')) {
				usleep(100);
				$this->handle = fopen($this->file, 'r');
			}
		}
		restore_error_handler();

		if (!$this->handle) {
			throw new IOException($error, 0, null, $this->file);
		}

		if (!flock($this->handle, LOCK_EX | ($blocking ? 0 : LOCK_NB))) {
			fclose($this->handle);
			$this->handle = null;

			return false;
		}

		return true;
	}

	public function release()
	{
		if ($this->handle) {
			flock($this->handle, LOCK_UN | LOCK_NB);
			fclose($this->handle);
			$this->handle = null;
		}
	}
}
