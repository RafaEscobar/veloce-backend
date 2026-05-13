<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingIssueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'priority' => new IssuePriorityResource($this->whenLoaded('issuePriority')),
            'issueStatus' => new IssueStatusResource($this->whenLoaded('issueStatus')),
        ];
    }
}
