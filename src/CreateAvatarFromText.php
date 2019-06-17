<?php
namespace Hracik\CreateAvatarFromText;

use Hracik\ColorConverter\ColorConverter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateAvatarFromText
{

	//returns base64 encoded image
	//const RETURN_BASE64 = 0;
	//return html <img> tag with base64 encoded image
	//const RETURN_BASE64_IMG = 1;
	//return image Resource
	//const RETURN_RESOURCE = 2;
	const RETURN_SVG = 3;

	/**
	 * @param string $text
	 * @param array  $options
	 * @param int    $returnType
	 * @return string
	 */
	public static function getAvatar(string $text, array $options = [], int $returnType = self::RETURN_SVG)
	{
		$resolver = new OptionsResolver();
		$resolver->setDefaults([
			'size' => 100,
			'text-display' => true,     //if false text will be used to count background color but won't be written in image
			'text-length' => 2,
			'text-case' => null,        //means keep as it is
			'text-modification' => null,//means take first chars based on length
			'color-scheme' => null,     //light or dark
			'text-color' => null,       //HEX value
			'background-color' => null, //HEX value or false
			'font-size' => null,        //default is relative to size
			'font-weight' => 'normal',  //bold,
		]);
		$options = $resolver->resolve($options);

		if (null === $options['background-color']) {
			$options['background-color'] = self::backgroundColor($text, $options['color-scheme']);
		}

		if (true === $options['text-display']) {
			if (null === $options['text-color']) {
				$options['text-color'] = self::textColor($text, $options['color-scheme']);
			}

			if (null === $options['font-size']) {
				$options['font-size'] = round($options['size'] / 2.5);
			}

			$modifiedText = self::textModification($text, $options['text-length'], $options['text-modification'], $options['text-case']);
		}

		ob_start();
		?>
        <svg width="<?= $options['size']; ?>" height="<?= $options['size']; ?>" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="<?= $options['background-color']; ?>"></rect>
			<?php if (true === $options['text-display'] && !empty($modifiedText)) { ?>
                <text x="50%" y="50%" dominant-baseline="mathematical" text-anchor="middle" fill="<?= $options['text-color']; ?>" font-weight="<?= $options['font-weight']; ?>" font-size="<?= $options['font-size']; ?>"><?= $modifiedText; ?></text>
			<?php } ?>
        </svg>
		<?php

		$svg = ob_get_contents();
		ob_end_clean();
		return $svg;

		//todo create return response for other possible return types
		//if ($returnType == self::RETURN_SVG) {
		//}
		//throw new Exception('Unknown return type.');
	}

	private static function textColor(string $text, $colorScheme)
	{
		$hex = '#'.substr(md5($text), 0, 6);
		$hsl = ColorConverter::hex2hsl($hex);
		if ($colorScheme == 'light') {
			$hsl[1] = 0.3;
			$hsl[2] = 0.3;
		}
        elseif ($colorScheme == 'dark') {
			$hsl[1] = 0.4;
			$hsl[2] = 0.85;
		}

		return ColorConverter::hsl2hex($hsl);
	}

	private static function backgroundColor(string $text, $colorScheme)
	{
		$hex = '#'.substr(md5($text), 0, 6);
		$hsl = ColorConverter::hex2hsl($hex);
		if ($colorScheme == 'light') {
			$hsl[1] = 0.66;
			$hsl[2] = 0.85;
		}
        elseif ($colorScheme == 'dark') {
			$hsl[1] = 0.5;
			$hsl[2] = 0.25;
		}

		return ColorConverter::hsl2hex($hsl);
	}

	private static function textModification($text, $length, $modification, $case)
	{
		switch ($modification) {
            case 'initials': $text = self::writeInitials($text, $length); break;
			case 'pseudo': $text = self::writePseudo($text, $length); break;
            default: $text = self::writeFirst($text, $length);
        }
		$text = self::textCase($text, $case);

		return $text;
	}

	private static function writePseudo(string $text, int $length): string
	{
		$text = preg_replace('/[^A-Za-z]/', '', $text);
		$textLength = strlen($text);
		if ($textLength/2 + $length > $textLength) {
			$write = substr($text, 0, $length);
		}
		else {
			$write = substr($text, $textLength/2, $length);
		}
		$write = strrev($write);
		return $write;
	}

	private static function writeFirst(string $text, int $length): string
	{
		$text = preg_replace('/[^A-Za-z]/', '', $text);
		return substr($text, 0, $length);
	}

	private static function writeInitials(string $text, int $length): string
	{
		$words = explode(' ', $text);
		$initials = '';
		for ($i = 0; $i < $length; $i++) {
			$initials .= substr($words[$i], 0, 1);
		}

		return $initials;
	}

	private static function textCase(string $text, string $case): string
	{
	    switch ($case) {
            case 'upper': return strtoupper($text); break;
		    case 'upper-first': return ucfirst($text); break;
		    case 'lower': return strtolower($text); break;
		    case 'lower-first': return lcfirst($text); break;
            default: return $text;
        }
	}
}