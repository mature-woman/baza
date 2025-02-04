<?php

declare(strict_types=1);

use mirzaev\baza\database,
	mirzaev\baza\record,
	mirzaev\baza\column,
	mirzaev\baza\enumerations\encoding,
	mirzaev\baza\enumerations\type;

// Initializing path to the composer loader file (main project)
$autoload = 
	__DIR__ . DIRECTORY_SEPARATOR . 
	'..' . DIRECTORY_SEPARATOR . 
	'..' . DIRECTORY_SEPARATOR . 
	'..' . DIRECTORY_SEPARATOR . 
	'vendor' . DIRECTORY_SEPARATOR . 
	'autoload.php';

// Reinitializing path to the composer loaded file (depencendy project)
if (!file_exists($autoload)) 
	$autoload = 
		__DIR__ . DIRECTORY_SEPARATOR . 
		'..' . DIRECTORY_SEPARATOR . 
		'..' . DIRECTORY_SEPARATOR . 
		'..' . DIRECTORY_SEPARATOR . 
		'..' . DIRECTORY_SEPARATOR . 
		'..' . DIRECTORY_SEPARATOR . 
		'autoload.php';

// Importing files of thr project and dependencies
require($autoload);

// Initializing path to the database file
$file = __DIR__ . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'database.baza';

echo "Started testing\n\n\n";

// Initializing the counter of actions
$action = 0;

if (!file_exists($file) || unlink($file)) {
	// Deleted deprecated database

	echo '[' . ++$action . '] ' . "Deleted deprecated database\n";
} else {
	// Not deleted deprecated database

	echo '[' . ++$action . '][FAIL] ' . "Failed to delete deprecated database\n";

	die;
}

// Initializing the test database
/* $database = new database() */
$database = (new database())
	->encoding(encoding::utf8)
	->columns(
		new column('name', type::string, ['length' => 32]),
		new column('second_name', type::string, ['length' => 64]),
		new column('age', type::integer),
		new column('height', type::float),
		new column('active', type::char)
	)
	->connect($file);

echo '[' . ++$action . "] Initialized the database\n";

// Initializing the record
$record = $database->record(
	'Arsen',
	'Mirzaev',
	24,
	165.5,
	1
);

echo '[' . ++$action . "] Initialized the record\n";

// Initializing the counter of tests
$test = 0;

echo '[' . ++$action . '][' . ++$test . '][' . ($record->name === 'Arsen' ? 'SUCCESS' : 'FAIL') . "][\"name\"] Expected: \"Arsen\" (string). Actual: \"$record->name\" (" . gettype($record->name) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . "][\"second_name\"] Expected: \"Mirzaev\" (string). Actual: \"$record->second_name\" (" . gettype($record->second_name) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->age === 24 ? 'SUCCESS' : 'FAIL') . "][\"age\"] Expected: \"24\" (integer). Actual: \"$record->age\" (" . gettype($record->age) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->height === 165.5 ? 'SUCCESS' : 'FAIL') . "][\"height\"] Expected: \"165.5\" (double). Actual: \"$record->height\" (" . gettype($record->height) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->active === 1 ? 'SUCCESS' : 'FAIL') . "][\"active\"] Expected: \"1\" (integer). Actual: \"$record->active\" (" . gettype($record->active) . ")\n";

echo '[' . $action . "] The record parameters checks have been completed\n";

// Reinitializing the counter of tests
$test = 0;

// Writing the record into the database
$database->write($record);

echo '[' . ++$action . "] Writed the record into the database\n";

// Initializing the second record
$record_ivan = $database->record(
	'Ivan',
	'Ivanov',
	24,
	(float) 210,
	0
);

echo '[' . ++$action . "] Initialized the record\n";

// Writing the second record into the databasse
$database->write($record_ivan);

echo '[' . ++$action . "] Writed the record into the database\n";

// Initializing the second record
$record_ivan = $database->record(
	'Margarita',
	'Esenina',
	19,
	(float) 165,
	1
);

echo '[' . ++$action . "] Initialized the record\n";

// Writing the second record into the databasse
$database->write($record_ivan);

echo '[' . ++$action . "] Writed the record into the database\n";

// Reading all records from the database
$records_readed_all = $database->read(amount: 99999);

echo '[' . ++$action . "] Readed all records from the database\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_readed_all) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_readed_all) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_all) === 3 ? 'SUCCESS' : 'FAIL') . '][amount of readed records] Expected: 3 records. Actual: ' . count($records_readed_all) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_readed_all[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($records_readed_all[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_all[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $records_readed_all[0]::class . "\"\n";

	echo '[' . $action . "] The readed all records checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The readed all records checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the first record from the database
$record_readed_first = $database->read(amount: 1);

echo '[' . ++$action . "] Readed the first record from the database\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($record_readed_first) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($record_readed_first) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($record_readed_first) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of readed records] Expected: 1 records. Actual: ' . count($record_readed_first) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($record_readed_first[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($record_readed_first[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_readed_first[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $record_readed_first[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_readed_first[0]->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Mirzaev" (string). Actual: "' . $record_readed_first[0]->second_name . '" (' . gettype($record_readed_first[0]->second_name) . ")\n";

	echo '[' . $action . "] The readed first record checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The readed first record checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the second record from the database
$record_readed_second = $database->read(amount: 1, offset: 1);

echo '[' . ++$action . "] Readed the second record from the database\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($record_readed_second) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($record_readed_second) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($record_readed_second) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of readed records] Expected: 1 records. Actual: ' . count($record_readed_second) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($record_readed_second[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($record_readed_second[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_readed_second[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $record_readed_second[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_readed_second[0]->second_name === 'Ivanov' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Ivanov" (string). Actual: "' . $record_readed_second[0]->second_name . '" (' . gettype($record_readed_second[0]->second_name) . ")\n";

	echo '[' . $action . "] The readed second record checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The readed second record checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the record from the database by filter
$record_readed_filter = $database->read(filter: fn($record) => $record?->second_name === 'Ivanov', amount: 1);

echo '[' . ++$action . "] Readed the record from the database by filter\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($record_readed_filter) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($record_readed_filter) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($record_readed_filter) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of readed records] Expected: 1 records. Actual: ' . count($record_readed_filter) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($record_readed_filter[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($record_readed_filter[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_readed_filter[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $record_readed_filter[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_readed_filter[0]->second_name === 'Ivanov' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Ivanov" (string). Actual: "' . $record_readed_filter[0]->second_name . '" (' . gettype($record_readed_filter[0]->second_name) . ")\n";

	echo '[' . $action . "] The readed record by filter checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The readed record by filter checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the record from the database by filter with amount limit
$records_readed_filter_amount = $database->read(filter: fn($record) => $record?->age === 24, amount: 1);

echo '[' . ++$action . "] Readed the record from the database by filter with amount limit\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_readed_filter_amount) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_readed_filter_amount) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_filter_amount) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of readed records] Expected: 1 records. Actual: ' . count($records_readed_filter_amount) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_readed_filter_amount[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($records_readed_filter_amount[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_amount[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $records_readed_filter_amount[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_amount[0]->age === 24 ? 'SUCCESS' : 'FAIL') . ']["age"] Expected: "24" (integer). Actual: "' . $records_readed_filter_amount[0]->age . '" (' . gettype($records_readed_filter_amount[0]->age) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_amount[0]->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Mirzaev" (string). Actual: "' . $records_readed_filter_amount[0]->second_name . '" (' . gettype($records_readed_filter_amount[0]->second_name) . ")\n";

	echo '[' . $action . "] The readed record by filter with amount limit checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The readed record by filter with amount limit checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the record from the database by filter with amount limit and offset
$records_readed_filter_amount_offset = $database->read(filter: fn($record) => $record?->age === 24, amount: 1, offset: 1);

echo '[' . ++$action . "] Readed the record from the database by filter with amount limit and offset\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_readed_filter_amount_offset) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_readed_filter_amount_offset) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_filter_amount_offset) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of readed records] Expected: 1 records. Actual: ' . count($records_readed_filter_amount_offset) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_readed_filter_amount_offset[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($records_readed_filter_amount_offset[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_amount_offset[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $records_readed_filter_amount_offset[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_amount_offset[0]->age === 24 ? 'SUCCESS' : 'FAIL') . ']["age"] Expected: "24" (integer). Actual: "' . $records_readed_filter_amount_offset[0]->age . '" (' . gettype($records_readed_filter_amount_offset[0]->age) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_amount_offset[0]->second_name === 'Ivanov' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Ivanov" (string). Actual: "' . $records_readed_filter_amount_offset[0]->second_name . '" (' . gettype($records_readed_filter_amount_offset[0]->second_name) . ")\n";

	echo '[' . $action . "] The readed record by filter with amount limit and offset checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The readed record by filter with amount limit and offset checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Deleting the record in the database by filter
$records_readed_filter_delete = $database->read(filter: fn($record) => $record?->name === 'Ivan', delete: true, amount: 1);

echo '[' . ++$action . "] Deleted the record from the database by filter\n";

// Reading records from the database after deleting
$records_readed_filter_delete_readed = $database->read(amount: 100);

echo '[' . ++$action . "] Readed records from the database after deleting the record\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_readed_filter_delete) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_readed_filter_delete) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_filter_delete) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of deleted records] Expected: 1 records. Actual: ' . count($records_readed_filter_delete) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_readed_filter_delete[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($records_readed_filter_delete[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_delete[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $records_readed_filter_delete[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_delete[0]->name === 'Ivan' ? 'SUCCESS' : 'FAIL') . ']["name"] Expected: "Ivan" (string). Actual: "' . $records_readed_filter_delete[0]->second_name . '" (' . gettype($records_readed_filter_delete[0]->second_name) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_filter_delete_readed) === 2 ? 'SUCCESS' : 'FAIL') . '][amount of readed records after deleting] Expected: 2 records. Actual: ' . count($records_readed_filter_delete_readed) . " records\n";

	echo '[' . $action . "] The deleted record by filter checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The deleted record by filter checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Updating the record in the database
$records_readed_filter_update = $database->read(filter: fn($record) => $record?->name === 'Margarita', update: fn(&$record) => $record->height += 0.5, amount: 1);

echo '[' . ++$action . "] Updated the record in the database by filter\n";

// Reading records from the database after updating
$records_readed_filter_update_readed = $database->read(amount: 100);

echo '[' . ++$action . "] Readed records from the database after updating the record\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_readed_filter_update) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_readed_filter_update) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_filter_update) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of updated records] Expected: 1 records. Actual: ' . count($records_readed_filter_update) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_readed_filter_update[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of readed values] Expected: "object". Actual: "' .  gettype($records_readed_filter_update[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_update[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of readed object values] Expected: "' . record::class . '". Actual: "' .  $records_readed_filter_update[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_update[0]->height === 165.5 ? 'SUCCESS' : 'FAIL') . ']["height"] Expected: "165.5" (double). Actual: "' . $records_readed_filter_update[0]->height . '" (' . gettype($records_readed_filter_update[0]->height) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_readed_filter_update_readed) === 2 ? 'SUCCESS' : 'FAIL') . '][amount of readed records after updating] Expected: 2 records. Actual: ' . count($records_readed_filter_update_readed) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_readed_filter_update_readed[1]->height === $records_readed_filter_update[0]->height ? 'SUCCESS' : 'FAIL') . "] Height from `update` process response matched height from the `read` preocess response\n";

	echo '[' . $action . "] The updated record by filter checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The updated record by filter checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

echo "\n\nCompleted testing";
