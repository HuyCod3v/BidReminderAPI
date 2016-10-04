<?php

namespace App\Repositories;

use App\Models\Repository;
use InfyOm\Generator\Common\BaseRepository;

class RepositoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'link'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Repository::class;
    }
}
