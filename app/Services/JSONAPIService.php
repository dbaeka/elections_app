<?php

namespace App\Services;


use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIIdentifierResource;
use App\Http\Resources\JSONAPIResource;
use App\Http\Resources\UserResource;
use App\Models\Candidate;
use App\Models\Result;
use App\Models\Station;
use App\Models\User;
use App\Observers\ResultObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use function Symfony\Component\String\s;

class JSONAPIService
{
    /**
     * For show
     * @param $model
     * @param int $id
     * @param string $type
     * @return JSONAPIResource
     */
    public function fetchResource($model, $id = 0, $type = '')
    {
        if ($model instanceof Model) {
            return new JSONAPIResource($model);
        }
        $query = QueryBuilder::for($model::where('id', $id))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->firstOrFail();
        return new JSONAPIResource($query);
    }

    public function specialMultipleResources($model, $type, $base, $relation)
    {
        $condition = $base === "pending" ? "whereDoesntHave" : "whereHas";
        $query = $model::with($relation)->{$condition}($relation, function ($query) use ($base) {
            $baseQuery = $query->orderBy('results.created_at', 'desc');
            if ($base === "new")
                $baseQuery->where('is_approved', false);
            elseif ($base === "old")
                $baseQuery->where('is_approved', true);
        });
        if ($base === "new")
            $query->where("approve_id", "=", "0");
        $query = QueryBuilder::for($query)
            ->allowedSorts(config("jsonapi.resources.{$type}.allowedSorts"))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->allowedFilters(config("jsonapi.resources.{$type}.allowedFilters"))
            ->jsonPaginate();
        return new JSONAPICollection($query);
    }

    public function fetchEngineResources($model, $type, $base)
    {
        $approved = $base === 'new' ? false : true;
        $query = '';
        if ($base === 'new')
            $query = $model::where('is_latest', 1)->where('is_approved', $approved)->orderBy('created_at', 'desc');
        elseif($base === 'old')
            $query = $model::where('is_latest', 1)->where('is_approved', $approved)->where('media_checked', false)->orderBy('created_at', 'desc');
        elseif ($base === 'pending')
            $query = Station::with($type)->whereDoesntHave($type, function ($query) use($type) {
                $query->orderBy("{$type}.created_at", "desc");
            });
        elseif ($base === 'media')
            $query = $model::where('media_checked', 1)->where('is_approved', true)->orderBy('created_at', 'desc');
        $query = QueryBuilder::for($query)
            ->allowedSorts(config("jsonapi.resources.{$type}.allowedSorts"))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->allowedFilters(config("jsonapi.resources.{$type}.allowedFilters"))
            ->jsonPaginate();
        return new JSONAPICollection($query);
    }


    public function fetchDisplayResources($model, $type)
    {
        $stations = $model::whereHas("results")->where("approve_id", ">", "0")->pluck('approve_id');
        $results = Result::whereIn('id', $stations)->pluck('records');
//        $results = $stations->load(['results' => function ($query) {
//            $query->select('records')->where('is_approved', true);
//        }])->pluck('results')->flatten(1)->pluck('records');
        $final = $results->reduce(function ($result, $item) {
            $keys = array_keys($item);
            $sum = 0;
            foreach ($keys as $key) {
                if (array_key_exists($key, $result))
                    $result[$key]["sum"] += $item[$key];
                else
                    $result[$key] = array(
                        "sum" => $item[$key]
                    );
                $sum += $result[$key]["sum"];
            }
            foreach ($result as $key => $val) {
                $result[$key]["percent"] = number_format((100 * $val["sum"] / $sum), 2);
            }
            return $result;
        }, array());
        $data = [];
        foreach ($final as $key => $value) {
            $value["id"] = $key;
            $value["sum"] = number_format($value["sum"], 0);
            $candidate = Candidate::find($key);
            $value["party_id"] = $candidate->party_id;
            $value["party_name"] = $candidate->party()->value('name');
            $value["party_short_name"] = $candidate->party()->value('short_name');
            $value["pres_name"] = $candidate->pres;
            $value["vice_name"] = $candidate->vice;
            array_push($data, $value);
        }
        return response()->json([
            'data' => collect($data)->sortBy('id')
        ]);
    }

    public
    function fetchMultipleResources($model, $id = 0, $type = '')
    {
        if ($model instanceof Model) {
            return new JSONAPICollection($model);
        }
        $query = QueryBuilder::for($model::where('user_id', $id))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->jsonPaginate();
        return new JSONAPICollection($query);
    }

    public
    function fetchUserBasedResources($model, $id = 0, $type = '')
    {
        $user = request()->user();
        $constituency_id = $user->station()->value('constituency_id');
        $query = QueryBuilder::for($model::where('constituency_id', $constituency_id))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->jsonPaginate();
        return new JSONAPICollection($query);
    }

    /**
     * For index
     * @param string $modelClass
     * @param string $type
     * @return JSONAPICollection
     */
    public
    function fetchResources(string $modelClass, string $type)
    {
        $models = QueryBuilder::for($modelClass)
            ->allowedSorts(config("jsonapi.resources.{$type}.allowedSorts"))
            ->allowedIncludes(config("jsonapi.resources.{$type}.allowedIncludes"))
            ->allowedFilters(config("jsonapi.resources.{$type}.allowedFilters"))
            ->jsonPaginate();
        return new JSONAPICollection($models);
    }

    protected
    function handleRelationship(array $relationships, $model)
    {
        foreach ($relationships as $relationshipName => $contents) {
            if ($model->$relationshipName() instanceof BelongsTo) {
                $this->updateToOneRelationship($model, $relationshipName, $contents['data']['id']);
            }
            if ($model->$relationshipName() instanceof BelongsToMany) {
                $this->updateManyToManyRelationships($model,
                    $relationshipName, collect($contents['data'])->pluck('id'));
            }
        }
        $model->load(array_keys($relationships));
    }

    /**
     * For store
     * @param string $modelClass
     * @param array $attributes
     * @param array|null $relationships
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function createResource(string $modelClass, array $attributes, array $relationships = null)
    {
        $model = $modelClass::create($attributes);
        if ($relationships) {
            $this->handleRelationship($relationships, $model);
        }
        return (new JSONAPIResource($model))
            ->response()
            ->header('Location', route(
                "{$model->type()}.show",
                [Str::singular($model->type()) => $model,]
            ));
    }

    /**
     * For update
     * @param $model
     * @param $attributes
     * @param null $relationships
     * @param null $id
     * @param null $type
     * @return JSONAPIResource
     */
    public
    function updateResource($model, $attributes, $relationships = null, $id = null, $type = null)
    {
        $modelID = $model->id;
        if ($modelID === null) {
            $model = $model::findOrFail($id);
        }
        $status = $model->update($attributes);
        if ($status && $type === "results")
            event('eloquent.updated: App\Models\Result', [$model, true]);
        if ($status && $type === "pm_results")
            event('eloquent.updated: App\Models\PMResult', [$model, true]);
        if ($relationships) {
            $this->handleRelationship($relationships, $model);
        }
        return new JSONAPIResource($model);
    }


    /**
     * For delete
     * @param $model
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public
    function deleteResource($model)
    {
        $model->delete();
        return response(null, 204);
    }

    /**
     * For relationships controller index
     * @param $model
     * @param string $relationship
     * @return JSONAPIIdentifierResource|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public
    function fetchRelationship($model, string $relationship)
    {
        if ($model->$relationship instanceof Model) {
            return new JSONAPIIdentifierResource($model->$relationship);
        }
        return JSONAPIIdentifierResource::collection($model->$relationship);
    }

    /**
     * For relationships controller update [many-many]
     * @param $model
     * @param $relationship
     * @param $ids
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public
    function updateManyToManyRelationships($model, $relationship, $ids)
    {
        $model->$relationship()->sync($ids);
        return response(null, 204);
    }

    public
    function updateToOneRelationship($model, $relationship, $id)
    {
        $relatedModel = $model->$relationship()->getRelated();
        $model->$relationship()->dissociate();
        if ($id) {
            $newModel = $relatedModel->newQuery()->findOrFail($id);
            $model->$relationship()->associate($newModel);
        }
        $model->save();
        return response(null, 204);
    }

    public
    function updateToManyRelationships($model, $relationship, $ids)
    {
        $foreignKey = $model->$relationship()->getForeignKeyName();
        $relatedModel = $model->$relationship()->getRelated();

        $relatedModel->newQuery()->findOrFail($ids);

        $relatedModel->newQuery()->where($foreignKey, $model->id)->
        update([
            $foreignKey => null,
        ]);
        $relatedModel->newQuery()->whereIn('id', $ids)->update([
            $foreignKey => $model->id,
        ]);
        return response(null, 204);
    }

    /**
     * For related controller index
     * @param $model
     * @param $relationship
     * @return JSONAPICollection|JSONAPIResource
     */
    public
    function fetchRelated($model, $relationship)
    {
        if ($model->$relationship instanceof Model) {
            return new JSONAPIResource($model->$relationship);
        }
        return new JSONAPICollection($model->$relationship);
    }
}
