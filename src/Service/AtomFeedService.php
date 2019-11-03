<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AtomFeedService
{
    private $httpClient;
    
    private $parameters;
    
    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameters)
    {
        $this->httpClient = $httpClient;
        $this->parameters = $parameters;
    }
    
    public function get()
    {
        $response = $this->httpClient->request('GET', $this->parameters->get('feed_url'));
        
        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new \Exception("Couldn't refresh the feed", $response->getStatusCode());
        }
        
        return $response->getContent();
    }
    
}
