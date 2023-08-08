<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ConvertRequestToSnakeCase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->replace($this->convert($request->all()));
        return $next($request);
    }

    private function convert($data){
        $array = [];
        foreach ($data as $key => $value) {
            $array[Str::snake($key)] = is_array($value)
                ? $this->convert($value)
                : $value;
        }
        return $array;
    }
}
