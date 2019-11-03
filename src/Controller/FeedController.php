<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
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
     * @param WordCountService $wordCountService
     * @param AtomFeedService $atomFeedService
     * @return JsonResponse
     */
    public function feed(
        SerializerInterface $serializer,
        WordCountService $wordCountService,
        AtomFeedService $atomFeedService
    ) {
        $xml = $atomFeedService->get();
        $deserialized = $serializer->deserialize($xml, Entry::class.'[]', 'xml');
        
        $words = [];
        /** @var Entry $entry */
        foreach ($deserialized as $entry) {
            $words = array_merge(
                    $words,
                    $wordCountService->filterCommon(
                        $wordCountService->toWords($entry->getTitle())
                    ),
                    $wordCountService->filterCommon(
                        $wordCountService->toWords($entry->getSummary())
                    )
            );
        }
        
        $sorted = $wordCountService->sort($wordCountService->count($words));
       
        $json = $serializer->serialize([
            'top_words' => $wordCountService->topTen($sorted),
            'entries' => $deserialized
        ], 'json', [
            'groups' => ['default']
        ]);
        
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    
}
