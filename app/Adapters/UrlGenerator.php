<?php

namespace App\Adapters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\InteractsWithTime;
use PharIo\Manifest\InvalidUrlException;

class UrlGenerator extends \Laravel\Lumen\Routing\UrlGenerator
{
    use InteractsWithTime;

    /**
     * The encryption key resolver callable.
     *
     * @var callable
     */
    protected $keyResolver;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->setKeyResolver(function () {
            return env('APP_KEY');
        });
    }

    /**
     * Create a signed route URL for a named route.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     * @param  \DateTimeInterface|\DateInterval|int|null  $expiration
     * @param  bool  $absolute
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function signedRoute($name, $parameters = [], $expiration = null, $absolute = true)
    {
        $parameters = Arr::wrap($parameters);

        if (array_key_exists('signature', $parameters)) {
            throw new InvalidUrlException(
                '"Signature" is a reserved parameter when generating signed routes. Please rename your route parameter.'
            );
        }

        if ($expiration) {
            $parameters = $parameters + ['expires' => $this->availableAt($expiration)];
        }

        ksort($parameters);

        $key = call_user_func($this->keyResolver);

        $routeUrl = $this->route($name, $parameters + [
                'signature' => hash_hmac('sha256', sha1(env('APP_KEY')), $key),
            ], $absolute);

        if(strtolower(env('APP_ENV')) === 'local') {
            $routeUrl = str_replace( 'https://', 'http://', $routeUrl );
        }

        return $routeUrl;
    }

    /**
     * Create a temporary signed route URL for a named route.
     *
     * @param  string  $name
     * @param  \DateTimeInterface|\DateInterval|int  $expiration
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string
     */
    public function temporarySignedRoute($name, $expiration, $parameters = [], $absolute = true)
    {
        return $this->signedRoute($name, $parameters, $expiration, $absolute);
    }

    /**
     * Determine if the given request has a valid signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $absolute
     * @return bool
     */
    public function hasValidSignature(Request $request, $absolute = true)
    {
        return $this->hasCorrectSignature($request, $absolute)
            && $this->signatureHasNotExpired($request);
    }

    /**
     * Determine if the given request has a valid signature for a relative URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function hasValidRelativeSignature(Request $request)
    {
        return $this->hasValidSignature($request, false);
    }

    /**
     * Determine if the signature from the given request matches the URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $absolute
     * @return bool
     */
    public function hasCorrectSignature(Request $request, $absolute = true)
    {
        $url = $absolute ? $request->url() : '/' . $request->path();       
        
        /*
        $original = rtrim($url . '?'. Arr::query(
                Arr::except($request->query(), 'signature')
            ), '?');
 
        */
        $signature = hash_hmac('sha256', sha1(env('APP_KEY')), call_user_func($this->keyResolver));

        return hash_equals($signature, (string) $request->query('signature', ''));
    }

    /**
     * Determine if the expires timestamp from the given request is not from the past.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function signatureHasNotExpired(Request $request)
    {
        $expires = $request->query('expires');

        return ! ($expires && Carbon::now()->getTimestamp() > $expires);
    }

    /**
     * Set the encryption key resolver.
     *
     * @param  callable  $keyResolver
     * @return $this
     */
    public function setKeyResolver(callable $keyResolver)
    {
        $this->keyResolver = $keyResolver;

        return $this;
    }
}