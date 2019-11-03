<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WordCountService
{
    
    private $parameters;
    
    public function __construct(ParameterBagInterface $parameters)
    {
        $this->parameters = $parameters;
    }
    
    public function count($words)
    {
        return array_count_values($words);
    }
    
    public function filterCommon($words)
    {
        return array_diff($words, $this->parameters->get('common_words'));
    }
    
    public function sort($words)
    {
        arsort($words);
        
        return $words;
    }
    
    public function topTen($words)
    {
        return array_chunk($words, 10, true)[0];
    }
    
}
