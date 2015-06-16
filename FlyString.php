<?php
/**
 * System control data are stored in text format
 * @license Code and contributions have MIT License
 * @link    http://visavi.net
 * @author  Alexander Grigorev <visavi.net@mail.ru>
 * @version 1.0
 */

namespace Visavi;

class FlyString {

	/**
	 * @var string file name
	 */
	private $_file;

	/**
	 * @var string a symbol of the division of cells
	 */
	private $_separator = '|';

	public function __construct($file, $separator)
	{
		$this->_file = $file;
		$this->_separator = $separator;
	}

	/**
	 * Verify the existence of the file
	 * @return boolean if a file exists
	 */
	public function exists()
	{
		return file_exists($this->_file) ? true : false;
	}

	/**
	 * Count lines in the file
	 * @return integer number of lines
	 */
	public function count() {

		if ( ! $this->exists()) return 0;

		$file = file($this->_file);
		return count($file);
	}

	/**
	 * Reading a line from a file
	 * @param  integer $line line number (If no value is passed, the last line)
	 * @return array         an array of string data
	 */
	public function read($line = null)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		if (is_null($line)) {
			return explode($this->_separator, end($file));
		}

		if (isset($file[$line])) {
			return explode($this->_separator, $file[$line]);
		}

		return false;
	}

	/**
	 * Search value in the cell in the file
	 * @param  string  $search search expression
	 * @param  integer $ceil   cell number
	 * @return array           data and the line number
	 */
	public function search($search, $ceil = 0)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		foreach($file as $key => $value) {
			$data = explode($this->_separator, $value);
			if ($data[$ceil] === $search) {
				$data['line'] = $key;
				return $data;
			}
		}

		return false;
	}

	/**
	 * Replacing the line in the file
	 * @param  integer $line line number
	 * @param  array   $data dimensional array of data
	 * @return boolean       record result
	 */
	public function replace($line = 0, array $data)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);
		$fp = fopen($this->_file, "a+");
		flock ($fp, LOCK_EX);
		ftruncate ($fp, 0);
		foreach($file as $key => $value) {
			if ($line === $key) {
				fputs($fp, implode($this->_separator, $data).$this->_separator.PHP_EOL);
			} else {
				fputs($fp, $value);
			}
		}
		fflush($fp);
		flock ($fp, LOCK_UN);
		fclose($fp);

		return true;
	}

	/**
	 * Moving down the list of strings
	 * @param  integer $line number portable line
	 * @return boolean       execution result
	 */
	public function down($line = 0)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		if (count($file) > 1) {

			$value = $file[$line];
			unset($file[$line]);
			array_push($file, $value);

			file_put_contents($this->_file, $file, LOCK_EX);

			return true;
		}

		return false;
	}

	/**
	 * Shifting of lines up or down
	 * @param  integer $line  line number
	 * @param  integer $where which move up 1, -1 - down
	 * @return boolean        execution result
	 */
	public function shift($line, $where)
	{
		if ( ! $this->exists()) return false;

		$line2 = $line + $where;
		$file = file($this->_file);

		if (isset($file[$line]) && isset($file[$line2])) {
			$fp = fopen($this->_file, "a+");
			flock ($fp, LOCK_EX);
			ftruncate ($fp, 0);
			foreach($file as $key => $val) {
				if ($line == $key) {
					fputs($fp, $file[$line2]);
				} elseif ($line2 == $key) {
					fputs($fp, $file[$line]);
				} else {
					fputs($fp, $val);
				}
			}
			fflush($fp);
			flock ($fp, LOCK_UN);
			fclose($fp);

			return true;
		}

		return false;
	}

	/**
	 * Adding a string to a file, the file is created if it does not exist
	 * @param  array   $data  data array
	 * @param  boolean $clear flag clean file
	 * @return boolean        execution result
	 */
	public function insert(array $data, $clear = false)
	{
		$string = implode($this->_separator, $data).$this->_separator.PHP_EOL;

		if ($clear) {
			$res = file_put_contents($this->_file, $string, LOCK_EX);
		} else {
			$res = file_put_contents($this->_file, $string, FILE_APPEND | LOCK_EX);
		}

		return ($res === false) ? false : true;
	}

	/**
	 * Deleting rows from a file
	 * @param  mixed  $lines line number or an array of strings
	 * @return boolean       execution result
	 */
	public function drop($lines)
	{
		if ( ! $this->exists()) return false;

		if (is_array($lines)) {
			$file = file($this->_file);
			foreach($lines as $line) {
				if (isset($file[$line])) {
					unset($file[$line]);
				}
			}
			$res = file_put_contents($this->_file, implode($file), LOCK_EX);
		} else {
			$file = file($this->_file);
			if (isset($file[$lines])) {
				unset($file[$lines]);
			}
			$res = file_put_contents($this->_file, implode($file), LOCK_EX);
		}


		return ($res === false) ? false : true;
	}

	/**
	 * Clean file
	 * @return boolean execution result
	 */
	public function clear()
	{
		if ( ! $this->exists()) return false;

		$res = file_put_contents($this->_file, '');

		return ($res === false) ? false : true;
	}

	/**
	 * Formatted file size
	 * @param  integer $decimals the number of characters after the decimal point
	 * @return string            formatted file size
	 */
	public function filesize($decimals = 2)
	{
		if ( ! $this->exists()) return '0B';

		$bytes = filesize($this->_file);
		$size = ['B','kB','MB','GB','TB'];
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
	}
}
