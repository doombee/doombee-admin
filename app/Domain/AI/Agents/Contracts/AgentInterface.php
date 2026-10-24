<?php

namespace App\Domain\AI\Agents\Contracts;

use App\Domain\AI\Agents\DTO\AgentDefinition;

interface AgentInterface
{
    public function definition(): AgentDefinition;

    /**
     * @return array<class-string>
     */
    public function tools(): array;
}
