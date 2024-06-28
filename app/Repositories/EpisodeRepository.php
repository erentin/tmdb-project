<?php

namespace App\Repositories;

use App\Models\Episode;

class EpisodeRepository
{
    protected $episode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function all()
    {
        return $this->episode->all();
    }

    public function create(array $data)
    {
        return $this->episode->create($data);
    }

    public function find($id)
    {
        return $this->episode->find($id);
    }

    public function update($id, array $data)
    {
        return $this->episode->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->episode->destroy($id);
    }
}
