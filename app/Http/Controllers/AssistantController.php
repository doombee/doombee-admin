<?php

namespace App\Http\Controllers;

use App\Domain\AI\Agents\Execution\AgentRunner;
use App\Http\Requests\AssistantRequest;
use App\Integrations\Router\Contracts\RouterInterface;
use Illuminate\Http\JsonResponse;

class AssistantController extends Controller
{
    public function __construct(
        protected AgentRunner $agentRunner
    ) {}

    public function __invoke(
        AssistantRequest $request,
    ): JsonResponse {

        $result = $this->agentRunner->run(
            $request->string('message')->toString(),
            $request->integer('store_id'),
        );

        return response()->json([
            'success' => $result->success,
            'data' => $result->data,
            'error' => $result->error,
        ]);
    }
}
