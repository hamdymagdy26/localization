<?php

namespace App\Http\Middleware;

use Closure;
use Dev\Application\Exceptions\InvalidLocalizationException;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws InvalidLocalizationException
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('localization')) {
            throw new InvalidLocalizationException('Invalid localization header attribute');
        }
        return $next($request);
    }
}