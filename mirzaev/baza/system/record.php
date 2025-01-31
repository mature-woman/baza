<?php

declare(strict_types=1);

namespace mirzaev\baza;

/**
 * Record
 *
 * @package mirzaev\baza
 *
 * @var array $values The record values
 *
 * @method void __construct(string|int|float $values) Constructor
 * @method array values() Read all values of the record
 * @method void __set(string $name, mixed $value) Write the record value
 * @method mized __get(string $name) Read the record value
 * @method void  __unset(string $name) Delete the record value
 * @method bool __isset(string $name) Check for initializing of the record value
 *
 * @license http://www.wtfpl.net Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class record
{
	/**
	 * Values
	 *
	 * @var array $values The record values
	 */
	protected array $values = [];

	/**
	 * Constructor
	 *
	 * @param string[]|int[]|float[] $values Values of the record
	 *
	 * @return void
	 */
	public function __construct(string|int|float ...$values)
	{
		// Initializing values
		if (!empty($values)) $this->values = $values;
	}

	/**
	 * Values
	 *
	 * Read all values of the record 
	 *
	 * @return array All values of the record
	 */
	public function values(): array
	{
		return $this->values ?? [];
	}

	/**
	 * Write
	 *
	 * Write the value
	 *
	 * @param string $name Name of the parameter
	 * @param mixed $value Content of the parameter
	 *
	 * @return void
	 */
	public function __set(string $name, mixed $value = null): void
	{
		// Writing the value and exit
		$this->values[$name] = $value;
	}

	/**
	 * Read
	 * 
	 * Read the value
	 *
	 * @param string $name Name of the value
	 *
	 * @return mixed Content of the value
	 */
	public function __get(string $name): mixed
	{
		// Reading the value and exit (success)
		return $this->values[$name] ?? null;
	}

	/**
	 * Delete
	 *
	 * Delete the value
	 *
	 * @param string $name Name of the value
	 *
	 * @return void
	 */
	public function __unset(string $name): void
	{
		// Deleting the value
		unset($this->values[$name]);
	}

	/**
	 * Check for initializing
	 *
	 * Check for initializing the value
	 * 
	 * @param string $name Name of the value
	 *
	 * @return bool Is the value initialized?
	 */
	public function __isset(string $name): bool
	{
		// Checking for initializing the value and exit (success)
		return isset($this->values[$name]);
	}
}
