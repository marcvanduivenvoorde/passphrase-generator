<?php
declare(strict_types=1);

namespace MarcVanDuivenvoorde\PassPhraseGenerator;

class Factory
{
    /**
     * @return PassPhraseGenerator
     */
    public function create(): PassPhraseGenerator
    {
        return new PassPhraseGenerator(
            __DIR__ . '/../../resources/word-list.txt'
        );
    }

}
