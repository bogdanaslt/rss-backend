<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WordCountService
{
    /** @var ParameterBagInterface */
    private $parameters;
    
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }
    
    public function count(array $words): array
    {
        return array_count_values($words);
    }
    
    public function filterCommon(array $words): array
    {
        return array_values(array_diff($words, $this->parameters->get('common_words')));
    }
    
    public function sort(array $words): array
    {
        arsort($words);
        
        return $words;
    }
    
    public function topTen($words): array
    {
        return array_chunk($words, 10, true)[0];
    }
    
    public function toWords(string $string): array
    {
        $notags = strip_tags($string);
        $lower = strtolower($notags);
        $clean = str_replace(["'s "], " ", $lower);

        return str_word_count($clean, 1);
    }
    
}
