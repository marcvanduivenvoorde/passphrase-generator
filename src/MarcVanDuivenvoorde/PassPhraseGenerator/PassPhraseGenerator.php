<?php
declare(strict_types=1);

namespace MarcVanDuivenvoorde\PassPhraseGenerator;

class PassPhraseGenerator {

  /**
   * The word file.
   *
   * @var string
   */
  private $wordFile;

  /**
   * PassPhraseGenerator constructor.
   *
   * @param string $wordFile
   */
  public function __construct(string $wordFile) {
    $this->wordFile = $wordFile;
  }

  /**
   * Generate a passphrase by a number of words.
   *
   * @param int $numberOfWords
   * @param int $minimumWordLength
   * @param int $maximumWordLength
   *
   * @return string
   * @throws \Exception
   */
  public function generateByNumberOfWords(
    int $numberOfWords,
    int $minimumWordLength = 4,
    int $maximumWordLength = 10
  ): string {
    $wordList = $this->getWordList($minimumWordLength, $maximumWordLength);
    $passPhrase = [];

    $max = \count($wordList) - 1;
    for ($i = 0; $i < $numberOfWords; $i++) {
      $passPhrase[] = $wordList[\random_int(1, $max)];
    }

    return implode(' ', $passPhrase);
  }

  /**
   * Generate a passphrase by a maximum length.
   *
   * @param int $maximumLenght
   * @param int $minimumWordLength
   * @param int $maximumWordLength
   *
   *
   * @return string
   */
  public function generateByMaximumLength(
    int $maximumLenght,
    int $minimumWordLength = 4,
    int $maximumWordLength = 10
  ): string {

  }

  /**
   * Get the wordlist based on minimum and maximum word lengths.
   *
   * @param int $minimumWordLength
   * @param int $maximumWordlength
   *
   * @return array
   */
  protected function getWordList(
    int $minimumWordLength,
    int $maximumWordlength
  ) {
    return array_values(
      array_filter(
        $this->getUnfilteredWordList(),
        function ($word) use ($minimumWordLength, $maximumWordlength) {
          return (strlen($word) >= $minimumWordLength && strlen($word) <= $maximumWordlength);
        }
      )
    );
  }

  protected function getUnfilteredWordList(): array {
    static $wordList = null;

    if ($wordList === NULL) {
      if (!is_file($this->wordFile)) {
        throw new \RuntimeException(
          sprintf('Could not load file: %s', $this->wordFile)
        );
      }

      $wordList = array_map('trim', file($this->wordFile));
    }

    return $wordList;
  }


}
