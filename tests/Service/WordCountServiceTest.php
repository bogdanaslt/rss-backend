<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\WordCountService;

class WordCountServiceTest extends KernelTestCase
{
    
    /** @var WordCountService */
    private $wordCountService;
    
    public function setUp()
    {
        self::bootKernel();
        $container = self::$container;

        $this->wordCountService = $container->get(WordCountService::class);
    }
    
    public function testToWords()
    {
        $result = $this->wordCountService->toWords('Hello, My name is Bogdan');
        
        $this->assertEquals(['hello', 'my', 'name', 'is', 'bogdan'], $result);
    }
    
    
    public function testToWordsWithHtml()
    {
        $result = $this->wordCountService->toWords('This is a <a href="#">link to my</a> profile');
        
        $this->assertEquals(['this', 'is', 'a', 'link', 'to', 'my', 'profile'], $result);
    }
    
    public function testCount()
    {
        $result = $this->wordCountService->count(['this', 'word', 'single', 'this', 'word', 'not']);
        
        $this->assertEquals([
            'this' => 2,
            'word' => 2,
            'single' => 1,
            'not' => 1
        ], $result);
    }
    
    public function testFilterCommon()
    {
        $result = $this->wordCountService->filterCommon(['none', 'these', 'are', 'common']);
        
        $this->assertEquals(['none', 'these', 'are', 'common'], $result);
    }
    
    public function testFilterCommonWithSomeCommon()
    {
        $result = $this->wordCountService->filterCommon(['some', 'of', 'these', 'are', 'common', 'words', 'and', 'will', 'be', 'removed']);
        
        $this->assertEquals(['some', 'these', 'are', 'common', 'words', 'removed'], $result);
    }
    
    public function testFilterCommonWithAllCommon()
    {
        $result = $this->wordCountService->filterCommon([
            'the', 'be', 'to', 'of', 'and',
            'a', 'in', 'that', 'have', 'i',
            'it', 'for', 'not', 'on', 'with',
            'he', 'as', 'you', 'do', 'at', 
            'this', 'but', 'his', 'by', 'from',
            'they', 'we', 'say', 'her', 'she', 
            'or', 'an', 'will', 'my', 'one',
            'all', 'would', 'there', 'their', 'what', 
            'so', 'up', 'out', 'if', 'about',
            'who', 'get', 'which', 'go', 'me'
        ]);
        
        $this->assertEmpty($result);
    }
    
    public function testSort()
    {
        $result = $this->wordCountService->sort([
            'hello' => 5,
            'testing' => 2,
            'sorting' => 6,
            'phpunit' => 1
        ]);
        
        $this->assertEquals(serialize([
            'sorting' => 6,
            'hello' => 5,
            'testing' => 2,
            'phpunit' => 1
        ]), serialize($result));
    }
    
    public function testTopTen()
    {
        $result = $this->wordCountService->topTen([
            'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur',
            'adipiscing', 'elit', 'curabitur', 'vel', 'hendrerit', 'libero',
            'eleifend', 'blandit', 'nunc', 'ornare', 'odio', 'ut',
            'orci', 'gravida', 'imperdiet', 'nullam', 'purus', 'lacinia',
            'a', 'pretium', 'quis', 'congue', 'praesent', 'sagittis', 
            'laoreet', 'auctor', 'mauris', 'non', 'velit', 'eros',
            'dictum', 'proin', 'accumsan', 'sapien', 'nec', 'massa',
            'volutpat', 'venenatis', 'sed', 'eu', 'molestie', 'lacus',
            'quisque', 'porttitor', 'ligula', 'dui', 'mollis', 'tempus'
        ]);
        
        $this->assertEquals(['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'curabitur', 'vel'], $result);
    }
    
    public function testSortAndTopTen()
    {
        $sorted = $this->wordCountService->sort([
            'lorem' => 2, 'ipsum' => 7, 'dolor' => 8, 'sit' => 14, 'amet' => 89, 'consectetur' => 77,
            'adipiscing' => 98, 'elit' => 79, 'curabitur' => 1, 'vel' => 4, 'hendrerit' => 41, 'libero' => 42,
            'eleifend' => 30, 'blandit' => 37, 'nunc' => 39, 'ornare' => 100, 'odio' => 32, 'ut' => 55,
            'orci' => 24, 'gravida' => 59, 'imperdiet' => 66, 'nullam' => 56, 'purus' => -1, 'lacinia' => 1000
        ]);
        
        $result = $this->wordCountService->topTen($sorted);
        
        $this->assertEquals([
            'lacinia' => 1000,
            'ornare' => 100,
            'adipiscing' => 98,
            'amet' => 89,
            'elit' => 79,
            'consectetur' => 77,
            'imperdiet' => 66,
            'gravida' => 59,
            'nullam' => 56,
            'ut' => 55
        ], $result);
    }
    
}
