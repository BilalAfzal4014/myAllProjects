<?php

namespace App\Http\Controllers\Api;

use App\Components\AppStatusCodes;
use App\Components\ParseResponse;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException as HttpRequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    use ParseResponse;

    public function __construct()
    {
        $this->middleware(['api.version', 'web']);
    }

    public function store(Request $request)
    {
        $errors = $this->validateRequest($request);
        if (!empty($errors)) {
            return $this->addResponse(
                AppStatusCodes::HTTP_UNPROCESSABLE_ENTITY,
                'error',
                $errors,
                'error'
            );
        }

        $request_type = 'post';
        $data = $request->get('data');
        $action = $request->get('action');

        switch ($action) {
            case 'get':
                $request_type = 'get';
                break;
            case 'create':
                $request_type = 'post';
                break;
            case 'update':
                $request_type = 'put';
                break;
            case 'remove':
            case 'delete':
                $request_type = 'delete';
                break;
        }

        $endpoint = [];
        $endpoint[] = config('engagement.url.backend') . $request->segment(2);
        $endpoint[] = $request->get('resource');

        if (!empty($request->get('method'))) {
            $endpoint[] = $request->get('method');
        }

        if (!empty($request->get('id'))) {
            $endpoint[] = $request->get('id');
        }

        $url = implode('/', $endpoint);
        $user = $request->user();

        if ($user == null) {
            return response()->json([
                "data" => [],
                "meta" => [
                    "status" => "error",
                    "code" => 401
                ]
            ]);
            die;
        }

        try {

            if($user->is_active == '0'){
                return response()->json([
                    "data" => ['Company is not active.'],
                    "error" => 'Company is not active.',
                    "meta" => [
                        "status" => "error",
                        "code" => 401
                    ]
                ]);
                die;
            }
            if($user->deleted_at != ''){
                return response()->json([
                    "data" => ['Company is deleted.'],
                    "error" => 'Company is deleted.',
                    "meta" => [
                        "status" => "error",
                        "code" => 401
                    ]
                ]);
                die;
            }

            $client = new HttpClient();
            $payload = [
                'headers' => array_merge([
                    'Authorization' => 'Bearer ' . stripslashes($user->api_token),
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ], $this->compileAppHeaders($request))
            ];

            if (!empty($data)) {
                $payload['json'] = $data;
            }

            $response = $client->{$request_type}($url, $payload);

            return response(
                $response->getBody()->getContents()
            )->header('Content-Type', 'application/json');

        } catch (HttpRequestException $exception) {

            $response = \GuzzleHttp\json_decode(
                $exception->getResponse()->getBody()->getContents(),
                true
            );
            $errors = $response;
        } catch (\Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        return $errors;
    }

    /**
     * Validate incoming request data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        $rules = [
            'resource' => 'required'
        ];

        if (!empty($request->get('method'))) {
            $rules['method'] = 'required';
        }

        if (!empty($request->get('action'))) {
            $rules['action'] = 'required';
        }

        if (!empty($request->get('data'))) {
            $rules['data'] = 'required|array';
        }

        if (!empty($request->get('id'))) {
            $rules['id'] = 'required|integer|min:1';
        }

        $validator = \Validator::make($request->all(), $rules);

        return ($validator->fails()) ? $validator->errors()->all() : [];
    }
}
