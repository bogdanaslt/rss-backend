<?php

namespace App\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use App\Model\Entry;

class EntryDenormalizer implements DenormalizerInterface
{
    //put your code here
    public function denormalize($data, $type, $format = null, array $context = array())
    {
        return (new Entry())
            ->setLink($data['link']['@href'])
            ->setAuthor($data['author']['name'])
            ->setTitle($data['title']['#'])
            ->setUpdated(new \DateTime($data['updated']))
            ->setSummary($data['summary']['#'])
        ;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return Entry::class === $type;
    }

}
