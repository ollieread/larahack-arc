<?php

namespace Arc\Services;

use Arc\Models\User;
use Arc\Operations\Users\GetUser;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class Auth
{
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    private $hasher;

    /**
     * @var \Illuminate\Contracts\Auth\PasswordBroker
     */
    private $broker;

    /**
     * @var \Arc\Models\User|null
     */
    private $user;

    public function __construct(Hasher $hasher, PasswordBroker $broker)
    {
        $this->hasher = $hasher;
        $this->broker = $broker;
    }

    public function auth(string $tokenString): bool
    {
        $token = (new Parser)->parse($tokenString);

        if ($this->verifyToken($token) && $this->validateToken($token)) {
            $uuid = $token->getClaim('uid');
            $user = (new GetUser)
                ->setUuid($uuid)
                ->perform();

            if ($user) {
                $this->user = $user;
                return true;
            }
        }

        return false;
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    private function createToken(User $user): Token
    {
        $issued = Carbon::now();

        return (new Builder)
            ->identifiedBy(Str::random())
            ->issuedBy(config('app.url'))
            ->issuedAt($issued->timestamp)
            ->withClaim('uid', $user->uuid)
            ->expiresAt($issued->copy()->addDays(30)->timestamp)
            ->getToken(new Sha256, new Key(config('auth.jwt_key')));
    }

    public function login(string $login, string $password): ?Token
    {
        $user = (new GetUser)
            ->setUsername($login)
            ->setEmail($login)
            ->perform();

        if ($user && $this->hasher->check($password, $user->password)) {
            return $this->createToken($user);
        }

        return null;
    }

    public function user(): ?User
    {
        return $this->user;
    }

    /**
     * @param \Lcobucci\JWT\Token $token
     *
     * @return bool
     */
    private function validateToken(Token $token): bool
    {
        $validation = new ValidationData(Carbon::now()->timestamp, 60);
        $validation->setIssuer(config('app.url'));
        $validation->setCurrentTime(Carbon::now()->timestamp);

        return $token->validate($validation);
    }

    private function verifyToken(Token $token): bool
    {
        return $token->verify(new Sha256, new Key(config('auth.jwt_key')));
    }
}