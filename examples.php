<?php

include 'FlyString.php';

// Pass the file name and separator, default |
$fly = new Visavi\FlyString('test.txt');

// Checks the existence of the line returns true or false
$string = $fly->exists();
var_dump($string);

// Returns the number of lines in the file, if the file does not exist returns 0
$string = $fly->count();
var_dump($string);

// Reading the first line of the file, Default shows the last line in the file
$string = $fly->read(0);
var_dump($string);

// Search the data in cell number 2, it returns an array of the entire row and line number, If no number of the cell search is performed in zero cell
$string = $fly->search('test', 2);
var_dump($string);

// Writes a string number 5 new data
$string = $fly->replace(5, ['hello', 'world', 'test', 555, 0xd34]);
var_dump($string);

// Line breaks down the file number 3, if no number is transferred to a null string
$string = $fly->down(3);
var_dump($string);

// Shift 7 line 1 position up, then there would be 7 line 6 and vice versa
$string = $fly->shift(7, -1);
var_dump($string);

// Adding lines to the file, if the file does not exist it will be created, line is added to the file
$string = $fly->insert(['hello', 'world', 'test', 555, 0xd34]);
var_dump($string);

// Deleting rows from a file, instead of an array can be passed an integer
$string = $fly->drop([1,2]);
var_dump($string);

// Displays formatted file size, such as 543B, 1.43kB
var_dump($fly->filesize());

// Clears file
var_dump($fly->clear());
