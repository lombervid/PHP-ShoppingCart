<?php
namespace Component\Session;

interface SessionStorageInterface
{
	public function start();
	public function isStarted();
	public function set($name, $value);
	public function get($name);
	public function remove($name);
	public function clear();
}