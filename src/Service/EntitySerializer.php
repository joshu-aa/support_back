<?php 

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class EntitySerializer {

    public function serializeEntity($entity, $attributes)
    {
        $encoders = new JsonEncoder();
        $normalizers = new ObjectNormalizer();
        $serializer = new Serializer([$normalizers], [$encoders]);

        $data = $serializer->normalize(
            $entity,
            null,
            [AbstractNormalizer::ATTRIBUTES => $attributes]
        );
        return $data;
    }
}