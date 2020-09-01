<?php

namespace App\Components;

use Illuminate\Http\Request;

trait ParseResponse
{
    /**
     * @param int    $code
     * @param string $status
     * @param array  $data
     * @param string $key
     * @param array  $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addResponse($code, $status, $data, $key, $meta = [])
    {
        $meta = array_merge($meta, [
            'status'    => $status,
            'code'      => $code
        ]);

        return response()->json([
            'meta' => $meta,
            $key => $data
        ], $code);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function parseResponse(Request $request)
    {
        return $request->isJson() ? $request->json()->all() : $request->all();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function compileAppHeaders(Request $request)
    {
        $headers = collect($request->headers)->filter(function ($value, $key) {
            return in_array($key, config('engagement.api.headers.app')) ? $key : null;
        })->transform(function ($value) {
            return implode($value);
        });

        return $headers->toArray();
    }
}