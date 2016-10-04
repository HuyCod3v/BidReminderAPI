<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BiddingApiTest extends TestCase
{
    use MakeBiddingTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateBidding()
    {
        $bidding = $this->fakeBiddingData();
        $this->json('POST', '/api/v1/biddings', $bidding);

        $this->assertApiResponse($bidding);
    }

    /**
     * @test
     */
    public function testReadBidding()
    {
        $bidding = $this->makeBidding();
        $this->json('GET', '/api/v1/biddings/'.$bidding->id);

        $this->assertApiResponse($bidding->toArray());
    }

    /**
     * @test
     */
    public function testUpdateBidding()
    {
        $bidding = $this->makeBidding();
        $editedBidding = $this->fakeBiddingData();

        $this->json('PUT', '/api/v1/biddings/'.$bidding->id, $editedBidding);

        $this->assertApiResponse($editedBidding);
    }

    /**
     * @test
     */
    public function testDeleteBidding()
    {
        $bidding = $this->makeBidding();
        $this->json('DELETE', '/api/v1/biddings/'.$bidding->iidd);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/biddings/'.$bidding->id);

        $this->assertResponseStatus(404);
    }
}
