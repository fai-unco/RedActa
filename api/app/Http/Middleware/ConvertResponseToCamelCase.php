<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;


class ConvertResponseToCamelCase
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
        $response = $next($request);
        if ($response instanceof JsonResponse) {
            $response->setData($this->convert(json_decode($response->content(), true)));
        }
        return $response;
    }

    private function convert($data){
        $array = [];
        foreach ($data as $key => $value) {
            $array[Str::camel($key)] = is_array($value)
                ? $this->convert($value)
                : $value;
        }
        return $array;
    }
}
