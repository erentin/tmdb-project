<?php

namespace App\Repositories;

use App\Models\Series;

class SeriesRepository
{
    protected $series;

    public function __construct(Series $series)
    {
        $this->series = $series;
    }

    public function all()
    {
        return $this->series->all();
    }

    public function create(array $data)
    {
        return $this->series->create($data);
    }

    public function find($id)
    {
        return $this->series->find($id);
    }

    public function update($id, array $data)
    {
        return $this->series->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->series->destroy($id);
    }
}
