<?php

namespace App\Repositories;

use App\Models\Film;

class FilmRepository
{
    protected $film;

    public function __construct(Film $film)
    {
        $this->film = $film;
    }

    public function all()
    {
        return $this->film->all();
    }

    public function create(array $data)
    {
        return $this->film->create($data);
    }

    public function find($id)
    {
        return $this->film->find($id);
    }

    public function update($id, array $data)
    {
        return $this->film->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->film->destroy($id);
    }
}
