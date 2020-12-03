<?php

namespace App\Models;

use App\Models\Base\AbstractAPIModel;
use Database\Factories\PMCandidatesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PMCandidate extends AbstractAPIModel
{
    use HasFactory;


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return PMCandidatesFactory::new();
    }

    protected $table = 'pm_candidates';

    protected $fillable = ['name'];

    public function type()
    {
        return 'pm_candidates';
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function parties()
    {
        return $this->party();
    }

    public function constituency()
    {
        return $this->belongsTo(Constituency::class);
    }

    public function constituencies()
    {
        return $this->constituency();
    }

    public function allowedAttributes($type = null)
    {
        $attributes = parent::allowedAttributes($type);
        $candidate = $this->load('parties', 'constituencies');
        $party = $candidate->parties;
        $constituency = $candidate->constituencies;
        $attributes->prepend($party->name, 'party_name');
        $attributes->prepend($party->short_name, 'party_short_name');
        $attributes->prepend($constituency->name, 'constituency_name');
        return $attributes;
    }
}
