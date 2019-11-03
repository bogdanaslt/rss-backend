<?php

namespace App\Denormalizer;

use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use App\Model\Entry;

class EntryArrayDenormalizer extends ArrayDenormalizer
{
    
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return Entry::class.'[]' === $type;
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return parent::denormalize(
            $data['entry'],
            $class, $format, $context
        );
    }
    
}
