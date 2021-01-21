<?php

namespace ChatManager\Models;

use function rand;

class Utils
{
    /**
     * Транслитерация кириллицы в латиницу
     * @param $str
     * @return string
     */
    public static function translit(string $str): string
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
     * Похоже на
     * @param string $text
     * @param $original
     * @return int
     */
    private static function similarTo(string $text, $original): int
    {
        similar_text($text, $original, $percent);
        return floor($percent);
    }

    /**
     * Начинается с
     * @param string $text
     * @param $original
     * @return bool
     */
    private static function startAs(string $text, $original): bool
    {
        $word = explode(' ', $text)[0];
        $wordFromBot = explode(' ', $original)[0];
        return mb_substr($word, 2) == $wordFromBot;
    }

    /**
     * Заканчивается на
     * @param string $text
     * @param string $original
     * @return bool
     */
    private static function endAs(string $text, string $original)
    {
        $word = explode(' ', $text);
        $end_word = end($word);

        $word_wp = mb_substr($end_word, 0, mb_strlen($end_word) - 2);

        $wordFromBot = explode(' ', $original);

        return $word_wp == end($wordFromBot) and ($word_wp != $wordFromBot[0]);
    }

    /**
     * Содержит
     * @param string $text
     * @param string $original
     * @return bool
     */
    private static function contains(string $text, string $original): bool
    {
        return mb_stripos($original, $text) !== false;
    }

    /**
     * Удаляет из строки самую первую подстроку
     * @param $text
     * @return string
     */
    public static function removeFirstWord($text): string
    {
        return strstr($text, " ");
    }

    /**
     * Выборка необходимой строки по ключу
     * @param string $string
     * @param int $substring
     * @return string|bool
     */
    public static function getWord(string $string, int $substring): string|bool
    {
        $substrings = explode(' ', $string);

        return $substrings[$substring] ?? false;
    }

    /**
     * Проверка подстроки по шаблону
     * @param string $textFromArray
     * @param string $original
     * @param int $similarPercent
     * @return bool
     */
    public static function formatText(string $textFromArray, string $original, $similarPercent = 75): bool
    {
        if (mb_substr($textFromArray, 0, 1) == '|') {
            return self::similarTo($textFromArray, $original) > $similarPercent;
        } elseif (mb_substr($textFromArray, 0, 2) == "[|") {
            return self::startAs($textFromArray, $original);
        } elseif (mb_substr($textFromArray, -2, 2) == "|]") {
            return (self::endAs($textFromArray, $original));
        } elseif (mb_substr($textFromArray, 0, 1) == "{" && mb_substr($textFromArray, -1, 1) == "}") {
            return (self::contains($textFromArray, $original));
        } else return $textFromArray == $original;
    }

    /**
     * Православный explode с возможностью использовать несколько символов
     * @param $delimiters
     * @param $string
     * @return array|bool
     */
    public static function multiExplode($delimiters, $string): array|bool
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        return explode($delimiters[0], $ready);
    }

    /**
     * Является ли массив ассоциативным
     * @param array $arr
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        if ([] === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Является ли массив последовательным
     * @param array $arr
     * @return bool
     */
    public static function isSeq(array $arr): bool
    {
        if ([] === $arr) return false;
        return array_keys($arr) === range(0, count($arr) - 1);
    }

    /**
     * Является ли массив многомерным
     * @param array $array
     * @return bool
     */
    public static function isMulti(array $array): bool
    {
        $rv = array_filter($array, 'is_array');
        if (count($rv) > 0) return true;
        return false;
    }

    /**
     * Регулярка чтоб выбрать все айдишники из текста
     * @param string $string
     * @return array|bool
     */
    public static function regexId(string $string): array|bool
    {
        preg_match_all('/\[(?:id|club)([0-9]*)\|.*?]/', $string, $match);

        return $match[1];
    }

    /**
     * Вот это я не особо помню что за хуёвина
     * @return array
     */
    public static function turingTest()
    {
        $firstNum = rand(1, 10);
        $secondNum = rand(1, 10);
        $thirdNum = rand(1, 10);
        $fourthNum = rand(1, 5);

        $text = $firstNum . ' + ' . $secondNum . ' - ' . $thirdNum . ' * ' . $fourthNum;
        $sum = $firstNum + $secondNum - $thirdNum * $fourthNum;

        $invalidSum[] = $sum - rand(1, 10);
        $invalidSum[] = $sum + rand(1, 10);
        $invalidSum[] = $sum - rand(1, 10);
        $invalidSum[] = $sum;
        // $invalidSum[] = $sum + \rand(1, 10);

        shuffle($invalidSum);

        return ['array' => $invalidSum, 'result' => $sum, 'text' => $text];
    }
}