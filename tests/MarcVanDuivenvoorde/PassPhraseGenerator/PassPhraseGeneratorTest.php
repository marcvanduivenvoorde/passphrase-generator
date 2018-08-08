<?php
declare(strict_types=1);

namespace MarcVanDuivenvoorde\PassPhraseGenerator;

class PassPhraseGeneratorTest extends \PHPUnit_Framework_TestCase {

  public function setUp() {

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
  public function testGenerateByNumberOfWords($words, $min, $max) {
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

  public function provideGenerateByNumberOfWords() {
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

  public function testGenerateByMaximumLength() {

  }
}
