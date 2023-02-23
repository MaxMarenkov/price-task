<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RequestConverter
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function convertRequest(Request $request, string $targetClass): object
    {
        try {
            $object = $this->serializer->deserialize(
                $request->getContent(),
                $targetClass,
                'json',
                [AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false]
            );
        } catch (SerializerException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return $object;
    }
}
