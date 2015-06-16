<?php

include 'FlyString.php';

// Pass the file name and separator
$fly = new Visavi\FlyString('test.txt', '|');

// Checks the existence of the line returns true or false
$string = $fly->exists();
var_dump($string);

// Returns the number of lines in the file, if the file does not exist returns 0
$string = $fly->countString();
var_dump($string);

// Reading the first line of the file, Default shows the last line in the file
$string = $fly->readString(0);
var_dump($string);

// Search the data in cell number 2, it returns an array of the entire row and line number, If no number of the cell search is performed in zero cell
$string = $fly->searchString('test', 2);
var_dump($string);

// Writes a string number 5 new data
$string = $fly->replaceString(5, ['hello', 'world', 'test', 555, 0xd34]);
var_dump($string);

// Line breaks down the file number 3, if no number is transferred to a null string
$string = $fly->downString(3)
var_dump($string);

// Shift 7 line 1 position up, then there would be 7 line 6 and vice versa
$string = $fly->shiftString(7, -1);
var_dump($string);

// Adding lines to the file, if the file does not exist it will be created, line is added to the file
$string = $fly->insertString(['hello', 'world', 'test', 555, 0xd34]);
var_dump($string);

// Deleting rows from a file, instead of an array can be passed an integer
$string = $fly->dropString([1,2]);
var_dump($string);

// Clears file
var_dump($fly->clearFile());

// Displays formatted file size, such as 543V, 1.4kB
var_dump($fly->filesize(4));
