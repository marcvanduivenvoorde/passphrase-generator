<?php
declare(strict_types=1);

namespace MarcVanDuivenvoorde\PassPhraseGenerator;

class PassPhraseGenerator
{

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
    public function __construct(string $wordFile)
    {
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
        if ($minimumWordLength > $maximumWordLength) {
            throw new \RuntimeException(
                sprintf(
                    'The minimum [%s] is larger then the maximum [%s]',
                    $minimumWordLength,
                    $maximumWordLength
                )
            );
        }

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
     * @param int $lenght
     * @param int $minimumWordLength
     * @param int $maximumWordLength
     *
     *
     * @return string
     */
    public function generateByLength(
        int $lenght,
        int $minimumWordLength = 4,
        int $maximumWordLength = 10
    ): string {
        if (($minimumWordLength + $maximumWordLength) >= $lenght) {
            throw new \RuntimeException(
                sprintf(
                    'Can not create a passphrase of length [%s] using a combined minimum of [%s] and maximum of [%s]',
                    $lenght,
                    $minimumWordLength,
                    $maximumWordLength
                )
            );
        }

        if ($minimumWordLength > $maximumWordLength) {
            throw new \RuntimeException(
                sprintf(
                    'The minimum [%s] is larger then the maximum [%s]',
                    $minimumWordLength,
                    $maximumWordLength
                )
            );
        }

        $wordList = $this->getWordList($minimumWordLength, $maximumWordLength);
        $remainingLength = $lenght;
        $words = [];
        $max = \count($wordList) - 1;
        $reset = false;

        while (true) {
            $word = $wordList[\random_int(1, $max)];
            $calculatedRemainingLength = $this->calculateRemainingLenght($remainingLength, $word);

            if ($calculatedRemainingLength === -1) {
                $words[] = $word;

                break;
            }

            if ($calculatedRemainingLength < $minimumWordLength) {
                $maximumWordLength -= 1;
                $words = [];
                $remainingLength = $lenght;

                $wordList = $this->getWordList($minimumWordLength, $maximumWordLength);
                $max = \count($wordList) - 1;

                continue;
            }


            $words[] = $word;

            if ($calculatedRemainingLength >= $minimumWordLength && $calculatedRemainingLength <= $maximumWordLength) {
                $wordList = $this->getWordList($calculatedRemainingLength, $calculatedRemainingLength);
                $max = \count($wordList) - 1;
            }

            $remainingLength = $calculatedRemainingLength;
        }

        return implode(' ', $words);
    }

    /**
     * Calculate the remaining length based on current length and word length.
     *
     * @param int $currentLenght
     * @param string $word
     *
     * @return int
     */
    protected function calculateRemainingLenght(int $currentLenght, string $word): int
    {
        return $currentLenght - (\strlen($word) + 1);
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

    protected function getUnfilteredWordList(): array
    {
        static $wordList = null;

        if ($wordList === null) {
            if (!\is_readable($this->wordFile)) {
                throw new \RuntimeException(
                    sprintf('Could not load file: %s', $this->wordFile)
                );
            }

            $wordList = array_map('trim', file($this->wordFile));
        }

        return $wordList;
    }


}
