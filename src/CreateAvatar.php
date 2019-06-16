<?php
namespace Hracik\CreateAvatar;

use Exception;
use Hracik\ColorConverter\ColorConverter;

class CreateAvatar
{

	//returns base64 encoded image
	const RETURN_BASE64 = 0;
	//return html <img> tag with base64 encoded image
	const RETURN_BASE64_IMG = 1;
	//return image Resource
	const RETURN_RESOURCE = 2;
	const RETURN_SVG = 3;

	/**
	 * @param string $text
	 * @param int    $size
	 * @param array  $options
	 * @param int    $returnType
	 * @return false|string
	 * @throws Exception
	 */
	public static function getAvatar(string $text, int $size = 100, array $options = [], int $returnType = self::RETURN_SVG)
	{
		if (empty($options['font-size'])) {
			$options['font-size'] = round($size / 2.5);
		}
		if (empty($options['font-weight'])) {
			//todo test what is allowed here
			$options['font-weight'] = 'bold';
		}
		if (empty($options['text-length'])) {
			//todo use text-length
			$options['text-length'] = 2;
		}

		$cleanText = preg_replace('/[^A-Za-z0-9]/', '', $text);
        $textLength = strlen($cleanText);
        if ($textLength/2 + $options['text-length'] > $textLength) {
            $write = substr($text, 0, $options['text-length']);
        }
        else {
	        $write = substr($text, $textLength/2, $options['text-length']);
        }
		$write = strtoupper($write);

		$hex = '#'.substr(md5($text), 0, 6);
		$hsl = ColorConverter::hex2hsl($hex);
		if (empty($options['background-color'])) {
		    $hsl[2] = 0.3;
			$options['background-color'] = ColorConverter::hsl2hex($hsl);
		}
		if (empty($options['text-color'])) {
			$hsl[2] = 0.7;
			$options['text-color'] = ColorConverter::hsl2hex($hsl);
		}

		//todo create return response for other possible return types
		//if ($returnType == self::RETURN_SVG) {
			ob_start();
			?>
			<svg width="<?= $size; ?>" height="<?= $size; ?>" xmlns="http://www.w3.org/2000/svg">
				<rect width="100%" height="100%" fill="<?= $options['background-color']; ?>"></rect>
				<text x="50%" y="50%" dominant-baseline="mathematical" text-anchor="middle" fill="<?= $options['text-color']; ?>" font-weight="<?= $options['font-weight']; ?>" font-size="<?= $options['font-size']; ?>"><?= $write; ?></text>
			</svg>
			<?php

            $svg = ob_get_contents();
		    ob_end_clean();
		    return $svg;
		//}

		//throw new Exception('Unknown return type.');
	}
}