<?php

declare(strict_types=1);

namespace mirzaev\baza\enumerations;

// Build-in libraries
use UnexpectedValueException as exception_unexpected_value;

/**
 * Type
 *
 * @see https://www.php.net/pack Types
 *
 * @package mirzaev\baza\enumerations
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
enum type: string
{
	case string = 'a';
	case char = 'c';
	case char_unsigned = 'C';
	case short = 's';
	case short_unsigned = 'S';
	case integer = 'i';
	case integer_unsigned = 'I';
	case long = 'l';
	case long_unsigned = 'L';
	case long_long = 'q';
	case long_long_unsigned = 'Q';
	case float = 'f';
	case double = 'd';
	case null = 'x';

	/**
	 * Type
	 *
	 * @see https://www.php.net/manual/en/function.gettype.php (here is why "double" instead of "float" and "NULL" instead of "null")
	 *
	 * @return string Type
	 */
	public function type(): string
	{
		// Exit (success)
		return match ($this) {
			type::string, type::short => 'string',
			type::char, type::char_unsigned, type::short_unsigned, type::integer, type::integer_unsigned, type::long, type::long_unsigned, type::long_long, type::long_long_unsigned => 'integer',
			type::float, type::double => 'double',
			type::null => 'NULL',
			default => throw new exception_unexpected_value('Not found the type')
		};
	}

	/**
	 * Size
	 *
	 * @return int Size in bytes
	 */
	public function size(): int
	{
		// Exit (success)
		return strlen(pack($this->value, 0));
	}
}
