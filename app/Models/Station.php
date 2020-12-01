<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\StationsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Resources\MissingValue;
use Spatie\QueryBuilder\QueryBuilder;
use function Symfony\Component\String\s;


class Station extends AbstractAPIModel
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return StationsFactory::new();
    }

    protected $fillable = ['name', 'code', 'num_voters', 'approve_id'];

    public function type()
    {
        return 'stations';
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function constituencies()
    {
        return $this->constituency();
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function results()
    {
        return $this->hasManyThrough('App\Models\Result', 'App\Models\User');
    }

    public function allowedAttributes($type = null)
    {
        $stations = parent::allowedAttributes();
//        $records = json_decode($results->get('records'));
//        foreach ($records as $key => $value) {
//            $ca = Candidate::find($key)->pres;
//            $p = 10;
//        }
//        $p = $this->addSelect(['last_flight' => Result::select(['id', 'records'])
//            ->whereColumn('user_id', 'users.id')
//            ->orderBy('arrived_at', 'desc')
//            ->limit(1)
//        ])->get();
        $results = $this->load(['results' => function ($query) use ($type) {
            $baseQuery = $query->orderBy('created_at', 'desc');
            if ($type === "new")
                $baseQuery->where('is_approved', false);
            elseif ($type === "old")
                $baseQuery->where('is_approved', true);
        }])->results;
        if ($type !== "pending") {
            $records = collect($results->pluck('records')->first());
            $firstRecords = $records->slice(0, 2);
            $sumLastRecords = $records->slice(2)->sum();
            $records = ($records->isNotEmpty()) ? $firstRecords->concat([3 => $sumLastRecords]) : [];
            $stations->prepend($records, 'records');
            $stations->prepend($results->pluck('id')->first(), 'recent_result');
            $stations->prepend($results->pluck('created_at')->first(), 'result_added_at');
            $stations->prepend($results->pluck('id'), 'results');
        }
        return $stations;
    }
}
