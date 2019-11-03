<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use App\Model\Feed;
use App\Model\Entry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\WordCountService;
use Symfony\Component\HttpFoundation\Response;
use App\Service\AtomFeedService;

class FeedController extends AbstractController
{
    
    /**
     * @Route("/feed")
     *  
     * @param SerializerInterface $serializer
     * @param WordCountService $wordCount
     * @param AtomFeedService $atomFeedService
     * @return JsonResponse
     */
    public function feed(SerializerInterface $serializer, WordCountService $wordCount, AtomFeedService $atomFeedService)
    {
        $xml = $atomFeedService->get();
        $deserialized = $serializer->deserialize($xml, Entry::class.'[]', 'xml');
        
        $words = [];
        foreach ($deserialized as $entry) {
            $words = array_merge($words, $wordCount->filterCommon($entry->getWords()));
        }
        
        $sorted = $wordCount->sort($wordCount->count($words));
       
        $json = $serializer->serialize([
            'top_words' => $wordCount->topTen($sorted),
            'entries' => $deserialized
        ], 'json', [
            'groups' => ['default']
        ]);
        
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    
}
