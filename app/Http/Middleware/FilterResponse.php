<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FilterResponse
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
        // get response
        $response = $next($request);

        // if response is JSON
        if ($response instanceof JsonResponse) {

            $current_data = collect($response->getData());

            $data = collect($current_data->get('data'));

            if ($data->isNotEmpty()) {
                $current_data = $current_data->toArray();
                $base = basename($request->getPathInfo());
                if ($base !== "pending")
                    $current_data["data"] = $data->sortByDesc(function ($item, $key) {
                        return $item->attributes->result_added_at;
                    });
            }
            $response->setData($current_data);
        }

        return $response;
    }
}
