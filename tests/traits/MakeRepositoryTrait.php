<?php

use Faker\Factory as Faker;
use App\Models\Repository;
use App\Repositories\RepositoryRepository;

trait MakeRepositoryTrait
{
    /**
     * Create fake instance of Repository and save it in database
     *
     * @param array $repositoryFields
     * @return Repository
     */
    public function makeRepository($repositoryFields = [])
    {
        /** @var RepositoryRepository $repositoryRepo */
        $repositoryRepo = App::make(RepositoryRepository::class);
        $theme = $this->fakeRepositoryData($repositoryFields);
        return $repositoryRepo->create($theme);
    }

    /**
     * Get fake instance of Repository
     *
     * @param array $repositoryFields
     * @return Repository
     */
    public function fakeRepository($repositoryFields = [])
    {
        return new Repository($this->fakeRepositoryData($repositoryFields));
    }

    /**
     * Get fake data of Repository
     *
     * @param array $postFields
     * @return array
     */
    public function fakeRepositoryData($repositoryFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'link' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $repositoryFields);
    }
}
