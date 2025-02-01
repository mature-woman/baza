<?php

declare(strict_types=1);

namespace mirzaev\baza;

// Files of the project
use mirzaev\baza\enumerations\encoding,
	mirzaev\baza\enumerations\type;

// Built-in libraries
use LogicException as exception_logic,
	InvalidArgumentException as exception_invalid_argument,
	RuntimeException as exception_runtime;

/**
 * Database
 *
 * @package mirzaev\baza
 *
 * @var string $database Path to the database file
 * @var string $backups Path to the backups files directory
 * @var encoding $encoding Encoding of records in the database file
 * @var array $columns The database columns
 * @var int $length Binary size of every record in the database file
 *
 * @method self encoding(encoding $encoding) Write encoding into the database instance property (fluent interface)
 * @method self columns(column ...$columns) Write columns into the database instance property (fluent interface)
 * @method self connect(string $database) Initialize the database files (fluent interface)
 * @method record|null record(...$values) Initialize the record by the database columns
 * @method string pack(record $record) Pack the record values
 * @method record unpack(array $binaries) Unpack binary values and implement them as a `record` instance
 * @method bool write(record $record) Write the record into the database file
 * @method array read(?callable $filter, ?callable $update, bool $delete, int $amount, int $offset) Read records from the database file
 * @method bool backups() Initialize the backups files directory
 * @method int|false save() Create an unique backup file from the database file
 * @method bool load(int $identifier) Restore the database file from the backup file
 *
 * @license http://www.wtfpl.net Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class database
{
	/**
	 * Database
	 *
	 * Path to the database file
	 *
	 * @var string $database Path to the database file
	 */
	public protected(set) string $database = __DIR__ . DIRECTORY_SEPARATOR . 'database.baza';

	/**
	 * Backups
	 *
	 * Path to the backups files directory
	 *
	 * @var string $backups Path to the backups files directory
	 */
	public protected(set) string $backups = __DIR__ . DIRECTORY_SEPARATOR . 'backups';

	/**
	 * Encoding 
	 *
	 * @var encoding $encoding Encoding of records in the database file
	 */
	public protected(set) encoding $encoding;

	/**
	 * Columns
	 *
	 * @var record[] $columns The database columns
	 */
	public protected(set) array $columns;

	/**
	 * Length
	 *
	 * @var int $length Binary size of every record in the database file
	 */
	public protected(set) int $length;

	/**
	 * Encoding
	 *
	 * Write encoding into the database instance property
	 *
	 * @see https://en.wikipedia.org/wiki/Fluent_interface#PHP Fluent Interface
	 *
	 * @param encoding $encoding The database file encoding
	 *
	 * @return self The database instance (fluent interface)
	 */
	public function encoding(encoding $encoding): self
	{
		// Writing into the database instance property
		$this->encoding = $encoding;

		// Exit (success)
		return $this;
	}

	/**
	 * Columns
	 *
	 * Write columns into the database instance property
	 *
	 * @see https://en.wikipedia.org/wiki/Fluent_interface#PHP Fluent Interface
	 *
	 * @param column[] ...$columns The database columns
	 *
	 * @return self The database instance (fluent interface)
	 */
	public function columns(column ...$columns): self
	{
		// Writing into the database instance property
		$this->columns = $columns;

		// Initializing the database instance property
		$this->length ??= 0;

		foreach ($this->columns as $column) {
			// Iterating over columns

			if ($column->type === type::string) {
				// String

				// Adding the column string maximum length to the database instance property
				$this->length += $column->length;
			} else {
				// Other types

				// Adding the column type size to the database instance property
				$this->length += $column->type->size();
			}
		}

		// Exit (success)
		return $this;
	}

	/**
	 * Connect 
	 *
	 * Initialize the database files
	 *
	 * @see https://en.wikipedia.org/wiki/Fluent_interface#PHP Fluent Interface
	 *
	 * @param string $database Path to the database file
	 *
	 * @return self The database instance (fluent interface)
	 */
	public function connect(string $database): self
	{
		// Writing into the database instance property
		$this->database = $database;

		// Exit (success)
		return $this;
	}

	/**
	 * Record
	 *
	 * Initialize the record by the database columns
	 *
	 * @param mixed[] $values Values of the record
	 *
	 * @throws exceptiin_invalid_argument if the balue type not matches the column values types
	 * @throws exception_logic if amount of columns not matches the amount of values
	 *
	 * @return record|null The record instance
	 */
	public function record(string|int|float ...$values): ?record
	{
		if (count($values) === count($this->columns)) {
			// Amount of values matches amount of columns

			// Declaring the buffer of combined values
			$combined = [];

			foreach ($this->columns as $index => $column) {
				// Iterating over columns

				if (gettype($values[$index]) === $column->type->type()) {
					// The value type matches the column values type

					// Writing named index value into the buffer of combined values
					$combined[$column->name] = $values[$index];
				} else {
					// The value type not matches the column values type

					// Exit (fail)
					throw new exception_invalid_argument('The value type not matches the column values type');
				}
			}

			// Initializing the record by the buffer of combined values
			$record = new record(...$combined);

			// Exit (success)
			return $record;
		} else {
			// Amount of values not matches amount of columns

			// Exit (fail)
			throw new exception_logic('Amount of values not matches amount of columns');
		}

		// Exit (fail)
		return null;
	}

	/**
	 * Pack
	 *
	 * Pack the record values
	 *
	 * @param record $record The record
	 *
	 * @return string Packed values
	 */
	public function pack(record $record): string
	{
		// Declaring buffer of packed values
		$packed = '';

		foreach ($this->columns as $column) {
			// Iterating over columns

			if ($column->type === type::string) {
				// String

				// Converting to the database encoding
				$value = mb_convert_encoding($record->values()[$column->name], $this->encoding->value);

				// Packung the value and writing into the buffer of packed values
				$packed .= pack($column->type->value . $column->length, $value);
			} else {
				// Other types

				// Packung the value and writing into the buffer of packed values
				$packed .= pack($column->type->value, $record->values()[$column->name]);
			}
		}

		// Exit (success)
		return $packed;
	}

	/**
	 * Unpack
	 * 
	 * Unpack binary values and implement them as a `record` instance
	 *
	 * @param array $binaries Binary values in the same order as the columns
	 *
	 * @return record The unpacked record from binary values
	 */
	public function unpack(array $binaries): record
	{
			// Declaring the buffer of unpacked values
			$unpacked = [];

			foreach ($this->columns as $index => $column) {
				// Iterating over columns
				
				// Initializing link to the binary value
				$binary = $binaries[$index] ?? null;

				if ($column->type === type::string) {
					// String

					// Unpacking the value
					$value = unpack($column->type->value . $column->length, $binary ?? str_repeat("\0", $column->length))[1];

					// Deleting NULL-characters
					$unnulled = str_replace("\0", '', $value);

					// Encoding the unpacked value
					$encoded = mb_convert_encoding($unnulled, $this->encoding->value);

					// Writing into the buffer of readed values
					$unpacked[] = $encoded;
				} else {
					// Other types

					// Writing into the buffer of readed values
					$unpacked[] = unpack($column->type->value, $binary ?? "\0")[1];
				}
			}

			// Implementing the record
			$record = $this->record(...$unpacked);

			// Exit (success)
			return $record;
	}

	/**
	 * Write
	 *
	 * Write the record into the database file
	 *
	 * @param record $record The record
	 *
	 * @throws exception_runtime If failed to lock the file
	 * @throws exception_runtime If failed to unlock the file
	 *
	 * @return bool Is the record was writed into the end of the database file
	 */
	public function write(record $record): bool
	{
		try {
			// Opening the database file
			$file = fopen($this->database, 'ab');

			if (flock($file, LOCK_EX)) {
				// The file was locked

				// Packing the record values
				$packed = $this->pack($record);

				// Writing the packed values to the database file
				fwrite($file, $packed);

				// Applying changes
				fflush($file);

				if (flock($file, LOCK_UN)) {
					// The file was unlocked

					// Exit (success)
					return true;
				} else {
					// Failed to unlock the file

					// Exit (fail)
					throw new exception_runtime('Failed to unlock the file');
				}
			} else {
				// Failed to lock the file

				// Exit (fail)
				throw new exception_runtime('Failed to lock the file');
			}
		} finally {
			// Closing the database file
			fclose($file);
		}

		// Exit (fail)
		return false;
	}

	/**
	 * Read
	 *
	 * Read records from the database file
	 *
	 * Order: `$filter` -> `$offset` -> (`$delete` -> read deleted || `$update` -> read updated || read) -> `$amount`
	 *
	 * @param callable|null $filter Filtering records `function($record, $records): bool`
	 * @param callable|null $update Updating records `function(&$record): void`
	 * @param callable|null $delete Deleting records
	 * @param int $amount Amount iterator
	 * @param int $offset Offset iterator
	 *
	 * @return array|null Readed records
	 */
	public function read(?callable $filter = null, ?callable $update = null, bool $delete = false, int $amount = 1, int $offset = 0): ?array
	{
		// Opening the database file
		$file = fopen($this->database, 'c+b');

		if (flock($file, LOCK_EX)) {
			// The file was locked

			// Declaring the buffer of readed records
			$records = [];

			// Declaring the buffer of failed to reading records
			/* $failed = []; */

			while ($amount > 0) {
				// Reading records

				// Declaring the buffer of binary values
				$binaries = [];

				foreach ($this->columns as $column) {
					// Iterating over columns 

					if ($column->type === type::string) {
						// String

						// Reading the binary value from the database
						$binaries[] = fread($file, $column->length);
					} else {
						// Other types

						// Reading the binary value from the database
						$binaries[] = fread($file, $column->type->size());
					}

					// Terminate loop when end of file is reached
					/* if (feof($file)) break 2; */
				}

				// Terminate loop when end of file is reached
				if (feof($file)) break;

				try {
					// Unpacking the record
					$record = $this->unpack($binaries);

			if ((bool) array_filter($record->values())) {
				// The record contains at least one non-empty value

					if (is_null($filter) || $filter($record, $records)) {
						// Passed the filter

						if ($offset-- <= 0) {
							// Offsetted

							if ($delete) {
								// Requested deleting

								// Moving to the beginning of the row
								fseek($file, -$this->length, SEEK_CUR);

								// Writing NUL-characters instead of the record to the database file
								fwrite($file, str_repeat("\0", $this->length));

								// Moving to the end of the row
								fseek($file, $this->length, SEEK_CUR);
							} else if ($update) {
								// Requested updating

								// Updating the record
								$update($record);

								// Packing the updated record
								$packed = $this->pack($record);

								// Moving to the beginning of the row
								fseek($file, -$this->length, SEEK_CUR);

								// Writing to the database file
								fwrite($file, $packed);

								// Moving to the end of the row
								fseek($file, $this->length, SEEK_CUR);
							}

							// Writing into the buffer of records
							$records[] = $record;

							// Decreasing the amount iterator
							--$amount;
						}
					}
} else {
				// The record contains only empty values
}
				} catch (exception_logic | exception_invalid_argument | exception_domain $exception) {
					// Writing into the buffer of failed to reading records

					// Exit (fail)
					throw new exception_runtime('Failed to processing the record', previous: $exception);
				}
			}

			// Unlocking the file
			flock($file, LOCK_UN);

			// Closing the database file
			fclose($file);

			// Exit (success)
			return $records;
		}

		// Exit (fail)
		return null;
	}

	/**
	 * Backups
	 *
	 * Initialize the backups files directory
	 *
	 * @throws exception_runtime if failed to create the backups files directory
	 *
	 * @return bool Is the backups files directory created?
	 */
	public function backups(): bool
	{
		if (is_dir($this->backups) || is_writable($this->backups)) {
			// The backups files directory exists

			// Exit (success)
			return true;
		} else {
			// The backups files directory is not exists

			if (mkdir(directory: $this->backups, permissions: 0775, recursive: true)) {
				// The backups files directory created

				// Exit (success)
				return true;
			} else {
				// The backups files directory is still not exists

				// Exit (fail)
				throw new exception_runtime('Failed to create the backups files directory: "' . $this->backups . '"');
			}
		}

		// Exit (fail)
		return false;
	}

	/**
	 * Save
	 *
	 * Create an unique backup file from the database file
	 *
	 * @throws exception_runtime if failed to copying the database file to the backup file
	 * @throws exception_runtime if failed to initialize the backups files directory
	 *
	 * @return int|false Unique identifier of the created backup file
	 */
	public function save(): int|false
	{
		if ($this->backups()) {
			// Initialized the backups files directory

			// Generation of unique identifier
			generate:

			// Generating unique identifier for the backup file
			$identifier = uniqid();

			// Initializing path to the backup file with generated identifier
			$file = $this->backups . DIRECTORY_SEPARATOR . $identifier;

			if (file_exists($file)) {
				// File with this identifier is already exists

				// Repeating generation (entering into recursion)
				goto generate;
			} else {
				// Generated unique identifier for the backup file

				if (copy($this->database, $file)) {
					// Copied the database file to the backup file

					// Exit (success)
					return $identifier;
				} else {
					// Not copied the database file to the backup file

					// Exit (fail)
					throw new exception_runtime('Failed to copying the database file to the backup file');
				}
			}
		} else {
			// Not initialized the backups files directory

			// Exit (fail)
			throw new exception_runtime('Failed to initialize the backups files directory');
		}

		// Exit (fail)
		return false;
	}

	/**
	 * Load
	 *
	 * Restore the database file from the backup file
	 *
	 * @throws exception_runtime if not found the backup file
	 * @throws exception_runtime if failed to initialize the backups files directory
	 *
	 * @return int|false Unique identifier of the created backup file
	 */
	public function load(int $identifier): bool
	{
		if ($this->backups()) {
			// Initialized the backups files directory

			// Initializing path to the backup file
			$file = $this->backups . DIRECTORY_SEPARATOR . $identifier;

			if (file_exists($file)) {
				// Initialized the backup file

				if (rename($file, $this->database)) {
					// Loaded the database file from the backup file

					// Exit (success)
					return true;
				}
			} else {
				// Not initialized the backup file

				// Exit (fail)
				throw new exception_runtime('Not found the backup file');
			}
		} else {
			// Not initialized the backups files directory

			// Exit (fail)
			throw new exception_runtime('Failed to initialize the backups files directory');
		}

		// Exit (fail)
		return false;
	}
}
