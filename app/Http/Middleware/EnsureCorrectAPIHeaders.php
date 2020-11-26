<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

class EnsureCorrectAPIHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->headers->get('accept') !== 'application/json') {
//            return new Response('', 406);
            return response()->json([
                'errors' => [
                    [
                        'title' => 'Header missing value or wrong value',
                        'details' => 'Header missing accept:application/json',
                    ]
                ]
            ], 406, ['content-type' => 'application/json']);
        }


        if ($request->isMethod('POST') || $request->isMethod('PATCH')) {
            if ($request->header('content-type') !== 'application/json') {
//                return new Response('', 415);
                return response()->json([
                    'errors' => [
                        [
                            'title' => 'Header missing value or wrong value',
                            'details' => 'Header missing content-type:application/json',
                        ]
                    ]
                ], 415,['content-type' => 'application/json']);
            }
        }
        return $this->addCorrectContentType($next($request));
    }

    private function addCorrectContentType(BaseResponse $response)
    {
        $response->headers->set('content-type', 'application/json');
        return $response;
    }
}
