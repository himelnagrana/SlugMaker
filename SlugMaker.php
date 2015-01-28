<?php

/**
 * Collection: https://github.com/himelnagrana/SlugMaker
 * Collected and Compiled By: Himel Nag Rana <hnrana@gmail.com>
 *
 * Collection and Compilation of two great works.
 * This solution is extremely helpful for generating "slug" or "search engine friendly url"
 * with help of username (or any text) and id (or any number).
 * The hash creator is at: http://web.archive.org/web/20130727034425/http://blog.kevburnsjr.com/php-unique-hash
 * and the slug creator solution is at: https://code.google.com/p/php-slugs/
 */

/**
 * Class PseudoCrypt
 * @copyright: http://web.archive.org/web/20130727034425/http://blog.kevburnsjr.com/php-unique-hash
 */
class PseudoCrypt
{

	private static $golden_primes = array (
		'1'                  => '1',
		'41'                 => '59',
		'2377'               => '1677',
		'147299'             => '187507',
		'9132313'            => '5952585',
		'566201239'          => '643566407',
		'35104476161'        => '22071637057',
		'2176477521929'      => '294289236153',
		'134941606358731'    => '88879354792675',
		'8366379594239857'   => '7275288500431249',
		'518715534842869223' => '280042546585394647'
	);

	private static $chars62 = array(
		0 => 48,
		1 => 49, 2 => 50, 3 => 51, 4 => 52, 5 => 53, 6 => 54, 7 => 55, 8 => 56, 9 => 57, 10 => 65,
		11 => 66, 12 => 67, 13 => 68, 14 => 69, 15 => 70, 16 => 71, 17 => 72, 18 => 73, 19 => 74, 20 => 75,
		21 => 76, 22 => 77, 23 => 78, 24 => 79, 25 => 80, 26 => 81, 27 => 82, 28 => 83, 29 => 84, 30 => 85,
		31 => 86, 32 => 87, 33 => 88, 34 => 89, 35 => 90, 36 => 97, 37 => 98, 38 => 99, 39 => 100, 40 => 101,
		41 => 102, 42 => 103, 43 => 104, 44 => 105, 45 => 106, 46 => 107, 47 => 108, 48 => 109, 49 => 110, 50 => 111,
		51 => 112, 52 => 113, 53 => 114, 54 => 115, 55 => 116, 56 => 117, 57 => 118, 58 => 119, 59 => 120, 60 => 121,
		61 => 122
	);

	public static function base62($int)
	{
		$key = "";
		while(bccomp($int, 0) > 0) {
			$mod = bcmod($int, 62);
			$key .= chr(self::$chars62[$mod]);
			$int = bcdiv($int, 62);
		}

		return strrev($key);
	}

	public static function hash($num, $len = 5)
	{
		$ceil = bcpow(62, $len);
		$primes = array_keys(self::$golden_primes);
		$prime = $primes[$len];
		$dec = bcmod(bcmul($num, $prime), $ceil);
		$hash = self::base62($dec);

		return str_pad($hash, $len, "0", STR_PAD_LEFT);
	}
}

class StringConversion
{

	public function makeUserName ($firstName, $id)
	{
		$slug = $this->makeSlugs($firstName);
		$hash = PseudoCrypt::hash($id, 6);

		return $slug.'-'.$hash;
	}

	/**
	 * @param $string
	 * @param int $maxlen
	 * @return string
	 * @copyright: https://code.google.com/p/php-slugs/
	 */
	public function makeSlugs($string, $maxlen=0)
	{
		$newStringTab = array();
		$string = strtolower($this->noDiacritics($string));

		$stringTab = str_split($string);
		$numbers = array("0","1","2","3","4","5","6","7","8","9","-");

		foreach ($stringTab as $letter) {
			if(in_array($letter, range("a", "z")) || in_array($letter, $numbers)){
				$newStringTab[] = $letter;

			} elseif($letter==" ") {
				$newStringTab[]="-";
			}
		}

		if(count($newStringTab)) {
			$newString = implode($newStringTab);

			if($maxlen > 0) {
				$newString=substr($newString, 0, $maxlen);
			}

		} else {

			$newString='';
		}

		return $newString;
	}

	/**
	 * @param $string
	 * @return mixed
	 * @copyright: https://code.google.com/p/php-slugs/
	 */
	public function noDiacritics($string)
	{
		//cyrylic transcription
		$cyrylicFrom = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
		$cyrylicTo   = array('A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', '', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 'shtch', '', 'y', '', 'e', 'iu', 'ia');


		$from = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
		$to   = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");


		$from = array_merge($from, $cyrylicFrom);
		$to   = array_merge($to, $cyrylicTo);

		$newstring = str_replace($from, $to, $string);
		return $newstring;
	}
}