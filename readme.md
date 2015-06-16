# System control data are stored in text format

Basic useful feature list:

 * Reading and retrieval of data in a text file
 * Adding and deleting rows
 * Shift, rotation, clear and moving strings
 * Verifying the existence and size of the output file
 *

```php
<?php
//Pass the file name and separator
$fly = new Visavi\FlyString('test.txt', '|');

//Checks the existence of the line returns true or false
$string = $fly->exists();

// Returns the number of lines in the file, if the file does not exist returns 0
$string = $fly->countString();

// Reading the first line of the file, Default shows the last line in the file
$string = $fly->readString(0);

// Search the data in cell number 2, it returns an array of the entire row and line number, If no number of the cell search is performed in zero cell
$string = $fly->searchString('test', 2);

// Writes a string number 5 new data
$string = $fly->replaceString(5, ['hello', 'world', 'test', 555, 0xd34]);

// Line breaks down the file number 3, if no number is transferred to a null string
$string = $fly->downString(3)

// Shift 7 line 1 position up, then there would be 7 line 6 and vice versa
$string = $fly->shiftString(7, -1);

// Adding lines to the file, if the file does not exist it will be created, line is added to the file
$string = $fly->insertString(['hello', 'world', 'test', 555, 0xd34]);

// Deleting rows from a file, instead of an array can be passed an integer
$string = $fly->dropString([1,2]);

// Clears file
$fly->clearFile();

// Displays formatted file size, such as 543V, 1.4445kB
$fly->filesize(4);
```

### License

The class is open-sourced software licensed under the [GPL-3.0 license](http://opensource.org/licenses/gpl-3.0.html)
