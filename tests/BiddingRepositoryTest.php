<?php

use App\Models\Bidding;
use App\Repositories\BiddingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BiddingRepositoryTest extends TestCase
{
    use MakeBiddingTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var BiddingRepository
     */
    protected $biddingRepo;

    public function setUp()
    {
        parent::setUp();
        $this->biddingRepo = App::make(BiddingRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBidding()
    {
        $bidding = $this->fakeBiddingData();
        $createdBidding = $this->biddingRepo->create($bidding);
        $createdBidding = $createdBidding->toArray();
        $this->assertArrayHasKey('id', $createdBidding);
        $this->assertNotNull($createdBidding['id'], 'Created Bidding must have id specified');
        $this->assertNotNull(Bidding::find($createdBidding['id']), 'Bidding with given id must be in DB');
        $this->assertModelData($bidding, $createdBidding);
    }

    /**
     * @test read
     */
    public function testReadBidding()
    {
        $bidding = $this->makeBidding();
        $dbBidding = $this->biddingRepo->find($bidding->id);
        $dbBidding = $dbBidding->toArray();
        $this->assertModelData($bidding->toArray(), $dbBidding);
    }

    /**
     * @test update
     */
    public function testUpdateBidding()
    {
        $bidding = $this->makeBidding();
        $fakeBidding = $this->fakeBiddingData();
        $updatedBidding = $this->biddingRepo->update($fakeBidding, $bidding->id);
        $this->assertModelData($fakeBidding, $updatedBidding->toArray());
        $dbBidding = $this->biddingRepo->find($bidding->id);
        $this->assertModelData($fakeBidding, $dbBidding->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBidding()
    {
        $bidding = $this->makeBidding();
        $resp = $this->biddingRepo->delete($bidding->id);
        $this->assertTrue($resp);
        $this->assertNull(Bidding::find($bidding->id), 'Bidding should not exist in DB');
    }
}
