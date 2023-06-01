<?php
namespace ShoppingCart\Component\Session\Storage;

class NativeSessionStorage implements SessionStorageInterface
{
	public function __construct()
	{
		$this->start();
	}

	public function start()
	{
		if (!$this->isStarted()) {
			if (!session_start()) {
				throw new \RuntimeException('Failed to start the session');
			}
		}
	}

	public function isStarted()
	{
		if (php_sapi_name() !== 'cli') {
		    if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
		        return (session_status() === PHP_SESSION_ACTIVE);
		    } else {
		        return (session_id() !== '');
		    }
		}

		return false;
	}

	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
	}

	public function get($name)
	{
		if (!isset($_SESSION[$name])) {
			return '';
		}

		return $_SESSION[$name];
	}

	public function remove($name)
	{
		if (!isset($_SESSION[$name])) {
			return;
		}

		unset($_SESSION[$name]);
	}

	public function clear()
	{
		unset($_SESSION);
	}
}