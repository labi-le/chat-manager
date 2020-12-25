<?php

namespace labile\bot;

class Utils extends ChatManager
{
    public static $text;

    public static function translit($str): string
    {
        $tr = array(
            "А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
            "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
            "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
            "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
            "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
            "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
            "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
            "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
            "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
            "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
            "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
            "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y",
            "ы" => "yi", "ь" => "'", "э" => "e", "ю" => "yu", "я" => "ya"
        );
        return strtr($str, $tr);
    }

    /**
     * Похоже на.
     * @param string $text
     * @return int
     */
    public static function similarTo(string $text): int
    {
        similar_text($text, self::getText(), $percent);
        return floor($percent);
    }

    /**
     * Начинается с
     * @param string $text
     * @return bool
     */
    public static function startAs(string $text): bool
    {
        $word = explode(' ', $text)[0];
        $wordFromBot = explode(' ', self::getText())[0];
        return mb_substr($word, 2) == $wordFromBot;
    }

    /**
     * Заканчивается на
     * @param string $text
     * @return bool
     */
    public static function endAs(string $text)
    {
        $firstWord = explode(' ', $text);
        $firstWordFromBot = explode(' ', self::getText());

        return mb_substr($firstWord[count($firstWord) - 1], 0, -1) == $firstWordFromBot[count($firstWordFromBot) - 1];
    }

    /**
     * Содержит
     * @param string $text
     * @return bool
     */
    public static function contains(string $text): bool
    {
        return mb_stripos(self::getText(), $text) !== false;
    }

    public static function textWithoutPrefix($text)
    {
        return strstr($text, " ");
    }

    public static function multiexplode($delimiters, $string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $ready);
    }

    public static function turingTest()
    {
        $firstNum = rand(1, 10);
        $secondNum = rand(1, 10);
        $thirdNum = rand(1, 10);
        $fourthNum = rand(1, 5);

        $text = $firstNum . ' + ' . $secondNum . ' - ' . $thirdNum . ' * ' . $fourthNum;
        $sum = $firstNum + $secondNum - $thirdNum * $fourthNum;

        $invalidSum[] = $sum - \rand(1, 10);
        $invalidSum[] = $sum + \rand(1, 10);
        $invalidSum[] = $sum - \rand(1, 10);
        $invalidSum[] = $sum;
        // $invalidSum[] = $sum + \rand(1, 10);

        shuffle($invalidSum);

        return ['array' => $invalidSum, 'result' => $sum, 'text' => $text];
    }

    /**
     * @param mixed $text
     */
    public static function setText($text): void
    {
        self::$text = $text;
    }

    /**
     * @return mixed
     */
    public static function getText()
    {
        return self::$text;
    }
}