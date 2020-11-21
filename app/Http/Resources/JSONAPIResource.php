<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Str;

class JSONAPIResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'type' => $this->type(),
            'attributes' => $this->allowedAttributes(),
            'relationships' => $this->prepareRelationships(),
        ];
    }

    private function prepareRelationships()
    {
        $collection = collect(config("jsonapi.resources.{$this->type()}.relationships"))->flatMap(function ($related) {
            $relatedType = $related['type'];
            $relationship = $related['method'];
            return [
                $relatedType => [
                    'links' => [
                        'self' => route(
                            "{$this->type()}.relationships.{$relatedType}",
                            [Str::singular($this->type()) => $this->id]
                        ),
                        'related' => route(
                            "{$this->type()}.{$relatedType}",
                            [Str::singular($this->type()) => $this->id]
                        ),
                    ],
                    'data' => $this->prepareRelationshipData($relatedType, $relationship),
                ],
            ];
        });

        return $collection->count() > 0 ? $collection : new MissingValue();
    }

    private function prepareRelationshipData($relatedType, $relationship)
    {
        if ($this->whenLoaded($relationship) instanceof MissingValue) {
            return new MissingValue();
        }

        if ($this->$relationship() instanceof BelongsTo || $this->$relationship() instanceof HasOne) {
            return new JSONAPIIdentifierResource($this->$relationship);
        }

        return JSONAPIIdentifierResource::collection($this->$relationship);
    }

    public function with($request)
    {
        $with = [];
        if ($this->included($request)->isNotEmpty()) {
            $with['included'] = $this->included($request);
        }

        return $with;
    }

    public function included($request)
    {
        return collect($this->relations())
            ->filter(function ($resource) {
                if ($resource === null)
                    return false;
                return $resource->collection !== null;
            })->flatMap->toArray($request);
    }

    private function relations()
    {
        return collect(config("jsonapi.resources.{$this->type()}.relationships"))->map(function ($relation) {
            $modelOrCollection = $this->whenLoaded($relation['method']);

            if ($modelOrCollection === null) {
                return null;
            }

            if ($modelOrCollection instanceof Model) {
                $modelOrCollection = collect([new JSONAPIResource($modelOrCollection)]);
            }

            return JSONAPIResource::collection($modelOrCollection);
        });
    }
}
