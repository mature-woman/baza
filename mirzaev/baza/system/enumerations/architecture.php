<?php

declare(strict_types=1);

namespace mirzaev\baza\enumerations;

/**
 * Architecture
 *
 * @see http://php.net/pack#109328 `pack()` works only with 32 bit commands
 *
 * @package mirzaev\baza\enumerations
 *
 * @license http://www.wtfpl.net/ Do What The Fuck You Want To Public License
 * @author Arsen Mirzaev Tatyano-Muradovich <arsen@mirzaev.sexy>
 */
enum architecture: int
{
	case x86 = 32;
	case x86_64 = 64;

	/**
	 * Packable
	 *
	 * @return bool Is this architecture are packable without segmentating?
	 */
	public function packable(): bool
	{
		// Exit (success)
		return $this->value <= architecture::segment();
	}

	/**
	 * Segments
	 *
	 * Calculate the amount of `architecture::segment()` bit segments
	 *
	 * @return int Amount of segments
	 */
	public function segments(): int
	{
		// Calculating amount of segments
		$amount = $this->value / architecture::segment();

		// Exit (success)
		return $amount;
	}

	/**
	 * Segment
	 * 
	 * @see http://php.net/pack#109328 `pack()` works only with 32 bit commands
	 *
	 * @return int Length of a segment in bits
	 */
	public static function segment(): int
	{
		// Exit (success)
		return architecture::x86->value;
	}

	/**
	 * Segmentate
	 *
	 * Splits the value into `architecture::segment()` bit segments
	 *
	 * @param int $value The value for segmentating
	 *
	 * @return array Segments [int, int, int]
	 *
	 * @deprecated Just use `long long` type!
	 */
	public function segmentate(int $value): array
	{
		// Calculating expected amount of segments 
		$amount = $this->segments();

		// Initializing length of a segment
		$segment = architecture::segment();

		// Declaring the buffer of generated segments
		$segments = [];

		for ($i = 0; $i < $amount; ++$i) {
			// Iterating over generating segments

			// Calculating offset from the right
			$right = $amount - $i - 1;

			// Generating segments
			$segments[] = ($value & (0xffffffff << ($segment * $right))) >> ($segment * $right);
		}

		// Exit (success)
		return $segments;
	}
}
