<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LogRequest;
use App\DTOs\LogsRequestDTO;

class LogRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Сначала получаем ответ, чтобы потом его проанализировать
        $response = $next($request);

        // Получаем данные из запроса и ответа
        $address = $request->fullUrl();
        $method = $request->method();

        $routeAction = $request->route()?->getActionName() ?? 'unknown@unknown';
        [$controllerPath, $controllerMethod] = explode('@', $routeAction . '@');

        $bodyOfRequest = json_encode($request->all());
        $requestHeaders = json_encode($request->headers->all());

        $identifier = auth()->check() ? auth()->id() : 'guest';

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        $status = $response->getStatusCode();
        $bodyOfResponse = $response->getContent() ?? '';
        $responseHeaders = json_encode($response->headers->all());

        // Создаем DTO
        $dto = new LogsRequestDTO(
            address: $address,
            method: $method,
            controller_path: $controllerPath,
            controller_method: $controllerMethod,
            body_of_request: $bodyOfRequest,
            request_headers: $requestHeaders,
            identifier: (string) $identifier,
            ip_address: $ipAddress,
            user_agent: $userAgent,
            status: (string) $status,
            body_of_response: $bodyOfResponse,
            response_headers: $responseHeaders,
        );

        // Сохраняем лог в БД
        LogRequest::create([
            'address' => $dto->address,
            'method' => $dto->method,
            'controller_path' => $dto->controller_path,
            'controller_method' => $dto->controller_method,
            'body_of_request' => $dto->body_of_request,
            'request_headers' => $dto->request_headers,
            'identifier' => $dto->identifier,
            'ip_address' => $dto->ip_address,
            'user_agent' => $dto->user_agent,
            'status' => $dto->status,
            'body_of_response' => $dto->body_of_response,
            'response_headers' => $dto->response_headers,
        ]);

        // Возвращаем готовый ответ
        return $response;
    }
}