<?php
declare(strict_types=1);

namespace MarcVanDuivenvoorde\PassPhraseGenerator;

use const PHP_EOL;

class PassPhraseGeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }

    /**
     * @param $words
     * @param $min
     * @param $max
     *
     * @throws \Exception
     *
     * @dataProvider provideGenerateByNumberOfWords
     */
    public function testGenerateByNumberOfWords($words, $min, $max)
    {
        $generator = new PassPhraseGenerator(
            __DIR__ . '/../../../resources/word-list.txt'
        );

        $passPhrase = $generator->generateByNumberOfWords($words, $min, $max);

        $passPhraseWords = explode(' ', $passPhrase);

        self::assertEquals(
            $words,
            \count($passPhraseWords)
        );

        foreach ($passPhraseWords as $word) {
            $this->assertGreaterThanOrEqual($min, \strlen($word));
            $this->assertLessThanOrEqual($max, \strlen($word));
        }
    }

    public function provideGenerateByNumberOfWords()
    {
        return [
            [
                2,
                2,
                4,
            ],
            [
                3,
                3,
                6,
            ],
            [
                4,
                4,
                8,
            ],
            [
                5,
                5,
                10,
            ],
        ];
    }

    /**
     * @param int $length
     * @param int $minimumWordLength
     * @param int $maximumWordLength
     *
     * @dataProvider provideGenerateByLength
     */
    public function testGenerateByMaximumLength(
        int $length,
        int $minimumWordLength,
        int $maximumWordLength
    ) {
        $generator = new PassPhraseGenerator(
            __DIR__ . '/../../../resources/word-list.txt'
        );

        if ($minimumWordLength + $maximumWordLength >= $length) {
            self::setExpectedException(\RuntimeException::class);
        }

        $passPhrase = $generator->generateByLength($length, $minimumWordLength, $maximumWordLength);

        echo $passPhrase, PHP_EOL;
        self::assertEquals(
            $length,
            \strlen($passPhrase)
        );

        foreach (explode(' ', $passPhrase) as $word) {
            $this->assertGreaterThanOrEqual($minimumWordLength, \strlen($word));
            $this->assertLessThanOrEqual($maximumWordLength, \strlen($word));
        }
    }

    public function provideGenerateByLength()
    {
        $set = [];

        foreach (range(10, 100) as $length) {
            $min = \random_int(2, 8);
            $max = \random_int($min + 1, $min + 5);

            $key = sprintf('Length: %s, min: %s, max: %s', $length, $min, $max);
            $set[$key] = [
                $length,
                $min,
                $max,
            ];
        }

        return $set;
    }

}
