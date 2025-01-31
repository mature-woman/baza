<?php

declare(strict_types=1);

namespace mirzaev\baza\enumerations;

// Build-in libraries
use UnexpectedValueException as exception_unexpected_value;

/**
 * Encoding
 *
 * The selected encoding significantly affects the file size and the speed of working with it
 *
 * ASCII English
 * CP1250 Central Europe
 * CP1251 Cyrillic
 * CP1252 Western Europe
 * CP1253 Greek
 * CP1254 Turkish
 * CP1255 Hebrew
 * CP1256 Arabic
 * CP1257 Baltic Rim
 * CP1258 Vietnam
 *
 * UTF-8 Length 1-4 bytes, better for Europe, backwards compatible with ASCII (minimum size)
 * UTF-16 Length 2-4 bytes, better for Asia (medium size with medium performance)
 * UTF-32 Fixed length of 4 bytes (maximum performance)
 *
 * In the database, the length for all encodings is fixed at the maximum value
 *
 * @see https://www.php.net/manual/ru/mbstring.supported-encodings.php Encodings in PHP
 * @see https://www.ascii-code.com/ About ASCII encodings
 * @see https://home.unicode.org/technical-quick-start-guide/ About unicode
 * @see https://www.unicode.org/main.html Abount unicode encodings
 * @see https://www.unicode.org/faq/utf_bom.html About UTF
 *
 * @package mirzaev\baza\enumerations
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
enum encoding: string
{
	case ascii = 'ASCII';
	case cp1251 = 'CP1251'; // Windows 1251
	case cp1252 = 'CP1252'; // Windows-1252
	case cp1253 = 'CP1253'; // Windows-1253
	case cp1254 = 'CP1254'; // Windows-1254
	case cp1255 = 'CP1255'; // Windows-1255
	case cp1256 = 'CP1256'; // Windows-1256
	case cp1257 = 'CP1257'; // Windows-1257
	case cp1258 = 'CP1258'; // Windows-1258

	case utf8 = 'UTF-8';
	case utf16 = 'UTF-16';
	case utf32 = 'UTF-32';

	/**
	 * Length
	 *
	 * @return int Number of bits for a symbol
	 */
	public function maximum(): int
	{
		// Exit (success)
		return match ($this) {
			encoding::ascii => 7,
			encoding::cp1251, encoding::cp1252, encoding::cp1253, encoding::cp1254, encoding::cp1255, encoding::cp1256, encoding::cp1257, encoding::cp1258 => 8,
			encoding::utf8 => 8,
			encoding::utf16 => 16,
			encoding::utf32 => 32,
			default => throw new exception_unexpected_value('Not found the encoding')
		};
	}
}
