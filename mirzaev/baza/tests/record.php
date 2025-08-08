<?php

declare(strict_types=1);

use mirzaev\baza\database,
	mirzaev\baza\record,
	mirzaev\baza\column,
	mirzaev\baza\enumerations\encoding,
	mirzaev\baza\enumerations\architecture,
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
$database = new database();

if ($database->architecture === architecture::x86_64) {
	$database
		->encoding(encoding::utf8)
		->columns(
			new column('name', type::string, ['length' => 32]),
			new column('second_name', type::string, ['length' => 64]),
			new column('age', type::integer),
			new column('height', type::float),
			// 64-bit values test
			new column('neuron_count', type::integer_unsigned),
			new column('motivation', type::double),
			new column('reputation', type::integer),
			new column('active', type::char)
		)
		->connect($file);
} else {
	$database
		->encoding(encoding::utf8)
		->columns(
			new column('name', type::string, ['length' => 32]),
			new column('second_name', type::string, ['length' => 64]),
			new column('age', type::integer),
			new column('height', type::float),
			new column('active', type::char)
		)
		->connect($file);
}

echo '[' . ++$action . "] Initialized the database\n";

// Initializing the record
if ($database->architecture === architecture::x86_64) {
	$record = $database->record(
		'Arsen',
		'Mirzaev',
		24,
		165.5,
		9100,
		1.7976931348623E+238,
		7355608,
		1
	);
} else {
	$record = $database->record(
		'Arsen',
		'Mirzaev',
		24,
		165.5,
		1
	);
}

echo '[' . ++$action . "] Initialized the record\n";

// Initializing the counter of tests
$test = 0;

echo '[' . ++$action . '][' . ++$test . '][' . ($record->name === 'Arsen' ? 'SUCCESS' : 'FAIL') . "][\"name\"] Expected: \"Arsen\" (string). Actual: \"$record->name\" (" . gettype($record->name) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . "][\"second_name\"] Expected: \"Mirzaev\" (string). Actual: \"$record->second_name\" (" . gettype($record->second_name) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->age === 24 ? 'SUCCESS' : 'FAIL') . "][\"age\"] Expected: \"24\" (integer). Actual: \"$record->age\" (" . gettype($record->age) . ")\n";
echo '[' . $action . '][' . ++$test . '][' . ($record->height === 165.5 ? 'SUCCESS' : 'FAIL') . "][\"height\"] Expected: \"165.5\" (double). Actual: \"$record->height\" (" . gettype($record->height) . ")\n";
if ($database->architecture === architecture::x86_64) {
	echo '[' . $action . '][' . ++$test . '][' . ($record->neuron_count === 9100 ? 'SUCCESS' : 'FAIL') . "][\"neuron_count\"] Expected: \"9100\" (integer). Actual: \"$record->neuron_count\" (" . gettype($record->neuron_count) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record->motivation === 1.7976931348623E+238 ? 'SUCCESS' : 'FAIL') . "][\"motivation\"] Expected: \"1.7976931348623E+238\" (double). Actual: \"$record->motivation\" (" . gettype($record->motivation) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record->reputation === 7355608 ? 'SUCCESS' : 'FAIL') . "][\"reputation\"] Expected: \"7355608\" (integer). Actual: \"$record->reputation\" (" . gettype($record->reputation) . ")\n";
}
echo '[' . $action . '][' . ++$test . '][' . ($record->active === 1 ? 'SUCCESS' : 'FAIL') . "][\"active\"] Expected: \"1\" (integer). Actual: \"$record->active\" (" . gettype($record->active) . ")\n";

echo '[' . $action . "] The record parameters checks have been completed\n";

// Reinitializing the counter of tests
$test = 0;

// Writing the record into the database
$database->write($record);

echo '[' . ++$action . "] Wrote the record into the database\n";

// Initializing the second record
if ($database->architecture === architecture::x86_64) {
	$record_ivan = $database->record(
		'Ivan',
		'Ivanov',
		24,
		(float) 210,
		PHP_INT_MAX,
		PHP_FLOAT_MIN,
		PHP_INT_MIN,
		0
	);
} else {
	$record_ivan = $database->record(
		'Ivan',
		'Ivanov',
		24,
		(float) 210,
		0
	);
}

echo '[' . ++$action . "] Initialized the record\n";

// Writing the second record into the databasse
$database->write($record_ivan);

echo '[' . ++$action . "] Wrote the record into the database\n";

// Initializing the second record
if ($database->architecture === architecture::x86_64) {
	$record_margarita = $database->record(
		'Margarita',
		'Esenina',
		19,
		(float) 165,
		89000000000,
		(float) 163,
		PHP_INT_MAX,
		1
	);
} else {
	$record_margarita = $database->record(
		'Margarita',
		'Esenina',
		19,
		(float) 165,
		1
	);
}

echo '[' . ++$action . "] Initialized the record\n";

// Writing the second record into the databasse
$database->write($record_margarita);

echo '[' . ++$action . "] Wrote the record into the database\n";

// Reading all records from the database
$records_read_all = $database->read(amount: 99999);

echo '[' . ++$action . "] Read all records from the database\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_read_all) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_read_all) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_all) === 3 ? 'SUCCESS' : 'FAIL') . '][amount of read records] Expected: 3 records. Actual: ' . count($records_read_all) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_read_all[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($records_read_all[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_all[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $records_read_all[0]::class . "\"\n";
	if ($database->architecture === architecture::x86_64) {
		echo '[' . $action . '][' . ++$test . '][' . ($records_read_all[0]->neuron_count === 9100 ? 'SUCCESS' : 'FAIL') . ']["neuron_count"] Expected: "9100" (integer). Actual: "' . $records_read_all[0]->neuron_count . '" (' . gettype($records_read_all[0]->neuron_count) . ")\n";
	}

	echo '[' . $action . "] The read all records checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The read all records checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the first record from the database
$record_read_first = $database->read(amount: 1);

echo '[' . ++$action . "] Read the first record from the database\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($record_read_first) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($record_read_first) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($record_read_first) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of read records] Expected: 1 records. Actual: ' . count($record_read_first) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($record_read_first[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($record_read_first[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_read_first[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $record_read_first[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_read_first[0]->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Mirzaev" (string). Actual: "' . $record_read_first[0]->second_name . '" (' . gettype($record_read_first[0]->second_name) . ")\n";
	if ($database->architecture === architecture::x86_64) {
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_first[0]->neuron_count === 9100 ? 'SUCCESS' : 'FAIL') . ']["neuron_count"] Expected: "9100" (integer). Actual: "' . $record_read_first[0]->neuron_count . '" (' . gettype($record_read_first[0]->neuron_count) . ")\n";
	}

	echo '[' . $action . "] The read first record checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The read first record checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the second record from the database
$record_read_second = $database->read(amount: 1, offset: 1);

echo '[' . ++$action . "] Read the second record from the database\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($record_read_second) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($record_read_second) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($record_read_second) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of read records] Expected: 1 records. Actual: ' . count($record_read_second) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($record_read_second[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($record_read_second[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $record_read_second[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->second_name === 'Ivanov' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Ivanov" (string). Actual: "' . $record_read_second[0]->second_name . '" (' . gettype($record_read_second[0]->second_name) . ")\n";
	if ($database->architecture === architecture::x86_64) {
		/**
		 * Due to IEEE 754 double precision format double equality is problematic
		 *
		 * @see https://www.php.net/manual/en/language.types.float.php#113703
		 */
		echo '[' . $action . '][' . ++$test . '][' . (round($record_read_second[0]->motivation, 2) === round(2.2250738585072E-308, 2) ? 'SUCCESS' : 'FAIL') . ']["motivation"] Expected: "2.2250738585072E-308" (double). Actual: "' . $record_read_second[0]->motivation . '" (' . gettype($record_read_second[0]->motivation) . ")\n";
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->reputation === (int) 0 ? 'SUCCESS' : 'FAIL') . ']["reputation"] Expected: "0" (integer) (overflow). Actual: "' . $record_read_second[0]->reputation . '" (' . gettype($record_read_second[0]->reputation) . ")\n";
	}

	echo '[' . $action . "] The read second record checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The read second record checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the record from the database by filter
$record_read_filter = $database->read(filter: fn($record) => $record?->second_name === 'Ivanov', amount: 1);

echo '[' . ++$action . "] Read the record from the database by filter\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($record_read_filter) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($record_read_filter) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($record_read_filter) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of read records] Expected: 1 records. Actual: ' . count($record_read_filter) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($record_read_filter[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($record_read_filter[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_read_filter[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $record_read_filter[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($record_read_filter[0]->second_name === 'Ivanov' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Ivanov" (string). Actual: "' . $record_read_filter[0]->second_name . '" (' . gettype($record_read_filter[0]->second_name) . ")\n";
	if ($database->architecture === architecture::x86_64) {
		/**
		 * Due to IEEE 754 double precision format double equality is problematic
		 *
		 * @see https://www.php.net/manual/en/language.types.float.php#113703
		 */
		echo '[' . $action . '][' . ++$test . '][' . (round($record_read_second[0]->motivation, 2) === round(2.2250738585072E-308, 2) ? 'SUCCESS' : 'FAIL') . ']["motivation"] Expected: "2.2250738585072E-308" (double). Actual: "' . $record_read_second[0]->motivation . '" (' . gettype($record_read_second[0]->motivation) . ")\n";
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->reputation === (int) 0 ? 'SUCCESS' : 'FAIL') . ']["reputation"] Expected: "0" (integer) (overflow). Actual: "' . $record_read_second[0]->reputation . '" (' . gettype($record_read_second[0]->reputation) . ")\n";
	}

	echo '[' . $action . "] The read record by filter checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The read record by filter checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the record from the database by filter with amount limit
$records_read_filter_amount = $database->read(filter: fn($record) => $record?->age === 24, amount: 1);

echo '[' . ++$action . "] Read the record from the database by filter with amount limit\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_read_filter_amount) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_read_filter_amount) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_filter_amount) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of read records] Expected: 1 records. Actual: ' . count($records_read_filter_amount) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_read_filter_amount[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($records_read_filter_amount[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_amount[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $records_read_filter_amount[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_amount[0]->age === 24 ? 'SUCCESS' : 'FAIL') . ']["age"] Expected: "24" (integer). Actual: "' . $records_read_filter_amount[0]->age . '" (' . gettype($records_read_filter_amount[0]->age) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_amount[0]->second_name === 'Mirzaev' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Mirzaev" (string). Actual: "' . $records_read_filter_amount[0]->second_name . '" (' . gettype($records_read_filter_amount[0]->second_name) . ")\n";
	if ($database->architecture === architecture::x86_64) {
		/**
		 * Due to IEEE 754 double precision format double equality is problematic
		 *
		 * @see https://www.php.net/manual/en/language.types.float.php#113703
		 */
		echo '[' . $action . '][' . ++$test . '][' . (round($record_read_second[0]->motivation, 2) === round(2.2250738585072E-308, 2) ? 'SUCCESS' : 'FAIL') . ']["motivation"] Expected: "2.2250738585072E-308" (double). Actual: "' . $record_read_second[0]->motivation . '" (' . gettype($record_read_second[0]->motivation) . ")\n";
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->reputation === (int) 0 ? 'SUCCESS' : 'FAIL') . ']["reputation"] Expected: "0" (integer) (overflow). Actual: "' . $record_read_second[0]->reputation . '" (' . gettype($record_read_second[0]->reputation) . ")\n";
	}

	echo '[' . $action . "] The read record by filter with amount limit checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The read record by filter with amount limit checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Reading the record from the database by filter with amount limit and offset
$records_read_filter_amount_offset = $database->read(filter: fn($record) => $record?->age === 24, amount: 1, offset: 1);

echo '[' . ++$action . "] Read the record from the database by filter with amount limit and offset\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_read_filter_amount_offset) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_read_filter_amount_offset) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_filter_amount_offset) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of read records] Expected: 1 records. Actual: ' . count($records_read_filter_amount_offset) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_read_filter_amount_offset[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($records_read_filter_amount_offset[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_amount_offset[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $records_read_filter_amount_offset[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_amount_offset[0]->age === 24 ? 'SUCCESS' : 'FAIL') . ']["age"] Expected: "24" (integer). Actual: "' . $records_read_filter_amount_offset[0]->age . '" (' . gettype($records_read_filter_amount_offset[0]->age) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_amount_offset[0]->second_name === 'Ivanov' ? 'SUCCESS' : 'FAIL') . ']["second_name"] Expected: "Ivanov" (string). Actual: "' . $records_read_filter_amount_offset[0]->second_name . '" (' . gettype($records_read_filter_amount_offset[0]->second_name) . ")\n";
	if ($database->architecture === architecture::x86_64) {
		/**
		 * Due to IEEE 754 double precision format double equality is problematic
		 *
		 * @see https://www.php.net/manual/en/language.types.float.php#113703
		 */
		echo '[' . $action . '][' . ++$test . '][' . (round($record_read_second[0]->motivation, 2) === round(2.2250738585072E-308, 2) ? 'SUCCESS' : 'FAIL') . ']["motivation"] Expected: "2.2250738585072E-308" (double). Actual: "' . $record_read_second[0]->motivation . '" (' . gettype($record_read_second[0]->motivation) . ")\n";
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->reputation === (int) 0 ? 'SUCCESS' : 'FAIL') . ']["reputation"] Expected: "0" (integer) (overflow). Actual: "' . $record_read_second[0]->reputation . '" (' . gettype($record_read_second[0]->reputation) . ")\n";
	}

	echo '[' . $action . "] The read record by filter with amount limit and offset checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The read record by filter with amount limit and offset checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Deleting the record in the database by filter
$records_read_filter_delete = $database->read(filter: fn($record) => $record?->name === 'Ivan', delete: true, amount: 1);

echo '[' . ++$action . "] Deleted the record from the database by filter\n";

// Reading records from the database after deleting
$records_read_filter_delete_read = $database->read(amount: 100);

echo '[' . ++$action . "] Read records from the database after deleting the record\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_read_filter_delete) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_read_filter_delete) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_filter_delete) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of deleted records] Expected: 1 records. Actual: ' . count($records_read_filter_delete) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_read_filter_delete[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($records_read_filter_delete[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_delete[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $records_read_filter_delete[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_delete[0]->name === 'Ivan' ? 'SUCCESS' : 'FAIL') . ']["name"] Expected: "Ivan" (string). Actual: "' . $records_read_filter_delete[0]->second_name . '" (' . gettype($records_read_filter_delete[0]->second_name) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_filter_delete_read) === 2 ? 'SUCCESS' : 'FAIL') . '][amount of read records after deleting] Expected: 2 records. Actual: ' . count($records_read_filter_delete_read) . " records\n";
	if ($database->architecture === architecture::x86_64) {
		/**
		 * Due to IEEE 754 double precision format double equality is problematic
		 *
		 * @see https://www.php.net/manual/en/language.types.float.php#113703
		 */
		echo '[' . $action . '][' . ++$test . '][' . (round($records_read_filter_delete_read[1]->motivation, 2) === round(163, 2) ? 'SUCCESS' : 'FAIL') . ']["motivation"] Expected: "163" (double). Actual: "' . $records_read_filter_delete_read[1]->motivation . '" (' . gettype($records_read_filter_delete_read[1]->motivation) . ")\n";
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->reputation === (int) 0 ? 'SUCCESS' : 'FAIL') . ']["reputation"] Expected: "0" (integer) (overflow). Actual: "' . $record_read_second[0]->reputation . '" (' . gettype($record_read_second[0]->reputation) . ")\n";
	}

	echo '[' . $action . "] The deleted record by filter checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The deleted record by filter checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

// Updating the record in the database
$records_read_filter_update = $database->read(filter: fn($record) => $record?->name === 'Margarita', update: fn(&$record) => $record->height += 0.5, amount: 1);

echo '[' . ++$action . "] Updated the record in the database by filter\n";

// Reading records from the database after updating
$records_read_filter_update_read = $database->read(amount: 100);

echo '[' . ++$action . "] Read records from the database after updating the record\n";

try {
	echo '[' . ++$action . '][' . ++$test . '][' . (gettype($records_read_filter_update) === 'array' ? 'SUCCESS' : 'FAIL') . '][type of returned value] Expected: "array". Actual: "' . gettype($records_read_filter_update) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_filter_update) === 1 ? 'SUCCESS' : 'FAIL') . '][amount of updated records] Expected: 1 records. Actual: ' . count($records_read_filter_update) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . (gettype($records_read_filter_update[0]) === 'object' ? 'SUCCESS' : 'FAIL') . '][type of read values] Expected: "object". Actual: "' .  gettype($records_read_filter_update[0]) . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_update[0] instanceof record ? 'SUCCESS' : 'FAIL') . '][class of read object values] Expected: "' . record::class . '". Actual: "' .  $records_read_filter_update[0]::class . "\"\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_update[0]->height === 165.5 ? 'SUCCESS' : 'FAIL') . ']["height"] Expected: "165.5" (double). Actual: "' . $records_read_filter_update[0]->height . '" (' . gettype($records_read_filter_update[0]->height) . ")\n";
	echo '[' . $action . '][' . ++$test . '][' . (count($records_read_filter_update_read) === 2 ? 'SUCCESS' : 'FAIL') . '][amount of read records after updating] Expected: 2 records. Actual: ' . count($records_read_filter_update_read) . " records\n";
	echo '[' . $action . '][' . ++$test . '][' . ($records_read_filter_update_read[1]->height === $records_read_filter_update[0]->height ? 'SUCCESS' : 'FAIL') . "] Height from `update` process response matched height from the `read` preocess response\n";
	if ($database->architecture === architecture::x86_64) {
		/**
		 * Due to IEEE 754 double precision format double equality is problematic
		 *
		 * @see https://www.php.net/manual/en/language.types.float.php#113703
		 */
		echo '[' . $action . '][' . ++$test . '][' . (round($record_read_second[0]->motivation, 2) === round(2.2250738585072E-308, 2) ? 'SUCCESS' : 'FAIL') . ']["motivation"] Expected: "2.2250738585072E-308" (double). Actual: "' . $record_read_second[0]->motivation . '" (' . gettype($record_read_second[0]->motivation) . ")\n";
		echo '[' . $action . '][' . ++$test . '][' . ($record_read_second[0]->reputation === (int) 0 ? 'SUCCESS' : 'FAIL') . ']["reputation"] Expected: "0" (integer) (overflow). Actual: "' . $record_read_second[0]->reputation . '" (' . gettype($record_read_second[0]->reputation) . ")\n";
	}

	echo '[' . $action . "] The updated record by filter checks have been completed\n";
} catch (exception $e) {
	echo '[' . $action . "][WARNING] The updated record by filter checks have been completed with errors\n";
}

// Reinitializing the counter of tests
$test = 0;

echo "\n\nCompleted testing";
