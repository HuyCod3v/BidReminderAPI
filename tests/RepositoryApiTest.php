<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RepositoryApiTest extends TestCase
{
    use MakeRepositoryTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateRepository()
    {
        $repository = $this->fakeRepositoryData();
        $this->json('POST', '/api/v1/repositories', $repository);

        $this->assertApiResponse($repository);
    }

    /**
     * @test
     */
    public function testReadRepository()
    {
        $repository = $this->makeRepository();
        $this->json('GET', '/api/v1/repositories/'.$repository->id);

        $this->assertApiResponse($repository->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRepository()
    {
        $repository = $this->makeRepository();
        $editedRepository = $this->fakeRepositoryData();

        $this->json('PUT', '/api/v1/repositories/'.$repository->id, $editedRepository);

        $this->assertApiResponse($editedRepository);
    }

    /**
     * @test
     */
    public function testDeleteRepository()
    {
        $repository = $this->makeRepository();
        $this->json('DELETE', '/api/v1/repositories/'.$repository->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/repositories/'.$repository->id);

        $this->assertResponseStatus(404);
    }
}
