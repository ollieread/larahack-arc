<?php

namespace Arc\Transformers;

use Lcobucci\JWT\Token;
use League\Fractal\TransformerAbstract;

class TokenTransformer extends TransformerAbstract
{
    public function transform(Token $token): array
    {
        return [
            'expires_at' => $token->getClaim('exp'),
            'token'      => (string)$token,
        ];
    }
}