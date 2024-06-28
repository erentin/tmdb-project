<?php

namespace App\Repositories;

use App\Models\Season;

class SeasonRepository
{
    protected $season;

    public function __construct(Season $season)
    {
        $this->season = $season;
    }

    public function all()
    {
        return $this->season->all();
    }

    public function create(array $data)
    {
        return $this->season->create($data);
    }

    public function find($id)
    {
        return $this->season->find($id);
    }

    public function update($id, array $data)
    {
        return $this->season->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->season->destroy($id);
    }
}
