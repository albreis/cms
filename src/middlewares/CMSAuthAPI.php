<?php

namespace albreis\cms\middlewares;

use Closure;
use albreis\cms\helpers\CMS;

class CMSAuthAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        CMS::authAPI();

        return $next($request);
    }
}
