<?php

use App\Models\Repository;
use App\Repositories\RepositoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepositoryRepositoryTest extends TestCase
{
    use MakeRepositoryTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RepositoryRepository
     */
    protected $repositoryRepo;

    public function setUp()
    {
        parent::setUp();
        $this->repositoryRepo = App::make(RepositoryRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateRepository()
    {
        $repository = $this->fakeRepositoryData();
        $createdRepository = $this->repositoryRepo->create($repository);
        $createdRepository = $createdRepository->toArray();
        $this->assertArrayHasKey('id', $createdRepository);
        $this->assertNotNull($createdRepository['id'], 'Created Repository must have id specified');
        $this->assertNotNull(Repository::find($createdRepository['id']), 'Repository with given id must be in DB');
        $this->assertModelData($repository, $createdRepository);
    }

    /**
     * @test read
     */
    public function testReadRepository()
    {
        $repository = $this->makeRepository();
        $dbRepository = $this->repositoryRepo->find($repository->id);
        $dbRepository = $dbRepository->toArray();
        $this->assertModelData($repository->toArray(), $dbRepository);
    }

    /**
     * @test update
     */
    public function testUpdateRepository()
    {
        $repository = $this->makeRepository();
        $fakeRepository = $this->fakeRepositoryData();
        $updatedRepository = $this->repositoryRepo->update($fakeRepository, $repository->id);
        $this->assertModelData($fakeRepository, $updatedRepository->toArray());
        $dbRepository = $this->repositoryRepo->find($repository->id);
        $this->assertModelData($fakeRepository, $dbRepository->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteRepository()
    {
        $repository = $this->makeRepository();
        $resp = $this->repositoryRepo->delete($repository->id);
        $this->assertTrue($resp);
        $this->assertNull(Repository::find($repository->id), 'Repository should not exist in DB');
    }
}
