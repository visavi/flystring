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
	protected $_file;

	/**
	 * @var string a symbol of the division of cells
	 */
	protected $_separator;

	/**
	 * @var boolean separator line endings
	 */
	protected $_end;

	public function __construct($file, $separator = '|', $end = false)
	{
		$this->_file = $file;
		$this->_separator = $separator;
		$this->_end = $end;
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
	 * Adding a string to a file, the file is created if it does not exist
	 * @param  array   $data   data array
	 * @param  boolean $append add to the end or the beginning of the
	 * @return boolean         execution result
	 */
	public function insert(array $data, $append = true)
	{
		$string = implode($this->_separator, $data).$this->_separator.PHP_EOL;

		if ($append) {
			$res = file_put_contents($this->_file, $string, FILE_APPEND | LOCK_EX);
		} else {
			$string .= file_get_contents($this->_file);
			$res = file_put_contents($this->_file, $string, LOCK_EX);
		}

		return $res === false ? false : true;
	}

	/**
	 * Reading a line from a file
	 * @param  integer $line line number (If no value is passed, the last line)
	 * @return array         an array of string data or false
	 */
	public function read($line = null)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		if (is_null($line)) {
			$string = end($file);

		} elseif (isset($file[$line])) {
			$string = $file[$line];
		}

		if (isset($string)) {
			return explode($this->_separator, $this->prepare($string));
		}

		return false;
	}

	/**
	 * Search value in the cell in the file
	 * @param  integer $ceil   cell number
	 * @param  string  $search search expression
	 * @return array           data and the line number
	 */
	public function search($ceil, $search)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		foreach($file as $line => $value) {
			$string = explode($this->_separator, $value);
			if (isset($string[$ceil]) && $string[$ceil] === $search) {

				$data['line'] = $line;
				$data['data'] = explode($this->_separator, $this->prepare($file[$line]));

				return $data;
			}
		}

		return false;
	}

	/**
	 * Change the value in cell
	 * @param  integer $line  line number
	 * @param  integer $cell  cell number
	 * @param  string  $value new value
	 * @return boolean        record result
	 */
	public function cell($line, $cell, $value)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		if (isset($file[$line])) {

			$string = explode($this->_separator, $this->prepare($file[$line]));

			if (isset($string[$cell])) {
				$string[$cell] = $value;

				return $this->update($line, $string);
			}
		}

		return false;
	}

	/**
	 * Updating the line in the file
	 * @param  integer $line line number
	 * @param  array   $data dimensional array of data
	 * @return boolean       record result
	 */
	public function update($line = 0, array $data)
	{
		if ( ! $this->exists()) return false;

		$file = file($this->_file);

		if (isset($file[$line])) {
			$file[$line] = implode($this->_separator, $data).$this->_separator.PHP_EOL;

			return $this->write($file);
		}

		return false;
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

		if (isset($file[$line]) && count($file) > 1) {

			$string = $file[$line];
			unset($file[$line]);

			$file[] = $string;

			return $this->write($file);
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

			$string = $file[$line];

			$file[$line] = $file[$line2];
			$file[$line2] = $string;

			return $this->write($file);
		}

		return false;
	}

	/**
	 * Deleting rows from a file
	 * @param  mixed  $lines line number or an array of strings
	 * @return boolean       execution result
	 */
	public function delete($lines)
	{
		if ( ! $this->exists()) return false;

		if (is_array($lines)) {
			$file = file($this->_file);
			foreach($lines as $line) {
				if (isset($file[$line])) {
					unset($file[$line]);
				}
			}
		} else {

			$file = file($this->_file);
			if (isset($file[$lines])) {
				unset($file[$lines]);
			}
		}

		return $this->write($file);
	}

	/**
	 * Formatted file size
	 * @param  integer $decimals number of characters after the decimal point
	 * @return string            formatted file size
	 */
	function filesize($decimals = 2) {

		if ( ! $this->exists()) return '0B';

		$bytes = filesize($this->_file);
		$mod = 1000;

		$size = ['B','kB','MB','GB','TB','PB'];
		for ($i = 0; $bytes > $mod; $i++) {
			$bytes /= $mod;
		}

		return round($bytes, $decimals).$size[$i];
	}

	/**
	 * Clean file
	 * @return boolean execution result
	 */
	public function clear()
	{
		if ( ! $this->exists()) return false;

		return $this->write([]);
	}

	/**
	 * handles line removes extra characters and separator
	 * @param  string $string raw string
	 * @return string         processed string
	 */
	private function prepare($string)
	{
		return trim(trim($string), $this->_separator);
	}

	/**
	 * Writing data to a file
	 * @param  array $data an array of strings
	 * @return boolean     processed string
	 */
	private function write(array $data)
	{
		$res = file_put_contents($this->_file, $data, LOCK_EX);
		return $res === false ? false : true;
	}
}
