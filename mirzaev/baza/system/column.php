<?php

declare(strict_types=1);

namespace mirzaev\baza;

// Files of the project
use mirzaev\baza\database,
	mirzaev\baza\enumerations\type;

// Built-in libraries
use DomainException as exception_domain,
	LogicException as exception_logic,
	InvalidArgumentException as exceptiin_invalid_argument;

/**
 * Column
 *
 * @package mirzaev\baza
 *
 * @var string $name Name of the column
 * @var type $type Type of the column values
 * @var int $length Length of every binary value that will be written to the database file
 *
 * @method void __construct(string $name, type $type, array $parameters) Constructor
 *
 * @license http://www.wtfpl.net Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
class column
{
	/**
	 * Name
	 *
	 * @var string $name Name of the column
	 */
	public readonly protected(set) string $name;

	/**
	 * Type
	 *
	 * @see https://www.php.net/manual/en/function.pack.php Pack (types are shown here)
	 * @see https://www.php.net/manual/en/function.unpack.php Unpack
	 *
	 * @var type $type Type of the column values
	 */
	public readonly protected(set) type $type;

	/**
	 * Length
	 *
	 * Length of every binary value that will be written to the database file
	 *
	 * @throws exception_logic if the length property is already initialized
	 * @throws exception_logic if the type is not initialized
	 * @throws exception_domain if the type can not has length
	 *
	 * @var int $length Length of every binary values
	 */
	public protected(set) int $length {
		// Write
		set (int $value) {
			if (isset($this->length)) {
				// Already been initialized

				// Exit (fail)
				throw new exception_logic('The length property is already initialized');
			} else if (!isset($this->type)) {
				// The type is not initialized

				// Exit (fail)
				throw new exception_logic('The type of the column values is not initialized');
			} else if (match ($this->type) {
				type::string => true,
				default => false
			}) {
				// The type has length

				// Writing into the property
				$this->length = $value;
			} else {
				// The type has no length

				// Exit (fail)
				throw new exception_domain('The "' . $this->type->name . '" type can not has length');
			}
		}
	}

	/**
	 * Constructor
	 *
	 * @param string $name Name of the column
	 * @param type $type Type of the column values
	 * @param array $parameters Parameters of the column
	 *
	 * @return void
	 */
	public function __construct(string $name, type $type, array $parameters = [])
	{
		// Writing into the property
		$this->name = $name;

		// Writing into the property
		$this->type = $type;

		foreach ($parameters as $name => $value) {
			// Iterating over parameters

			if (property_exists($this, $name)) {
				// Found the property

				// Writing into the property
				$this->{$name} = $value;
			} else {
				// Not found the property

				// Exit (fail)
				throw new exceptiin_invalid_argument("Not found the property: $name");
			}
		}
	}
}
