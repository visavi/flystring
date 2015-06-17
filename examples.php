<?php

include 'FlyString.php';

// Pass the file name and separator, default |
$fly = new Visavi\FlyString('test.txt');

// Checks the existence of the line returns true or false
var_dump($fly->exists());
// Returns the number of lines in the file, if the file does not exist returns 0
var_dump($fly->count());

// Adding lines to the file, if the file does not exist it will be created, line is added to the file
for ($i = 1; $i <= 10; $i++) {
	var_dump($string = $fly->insert([$i, 'hello', 'world', 'test', 555]));
}

// Add a line to the beginning of the file
var_dump($string = $fly->insert([0, 'The line at the beginning of the', 'something'], false));

// Reading the first line of the file, Default shows the last line in the file
var_dump($fly->read(0));

// Search the data in cell number 2, it returns an array of the entire row and line number
var_dump($fly->search(3, 'test'));

// Change the value in the line number 8 and the cell number 2
var_dump($fly->cell(8, 2, 'new value'));

// Writes a string number 5 new data
var_dump($fly->update(5, [5, 'hello', 'world', 'test', 555, 0xd34]));

// Line breaks down the file number 3, if no number is transferred to a null string
var_dump($fly->down(3));

// Shift 7 line 1 position up, then there would be 7 line 6 and vice versa
var_dump($fly->shift(7, -1));

// Deleting rows from a file, instead of an array can be passed an integer
var_dump($fly->delete([1,2]));

// Displays formatted file size, such as 543B, 1.43kB
var_dump($fly->filesize());

// Clears file
var_dump($fly->clear());
