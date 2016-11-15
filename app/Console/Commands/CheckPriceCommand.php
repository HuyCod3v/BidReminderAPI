<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;

use Illuminate\Console\Command;

use App\Repositories\ProductRepository;
use GuzzleHttp\Client;

class CheckPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    /** @var  ProductRepository */
    private $productRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ProductRepository $productRepo)
    {
        parent::__construct();
        $this->productRepository = $productRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkPrice();
        $this->sendFCMMessage('fM5Awb2k368:APA91bHyTi1VVU1Yv1kvXGwpOeqqSjk2UFpejD9AsCUwKx-2BtglSKp335SjUsSLSFhm9ImEP6h2-OL-fXpalErsKcz7lCNWC99tmhbeipZIJQ5NjByaa272Z5ukGy1dYeziU984ekE1');
    }


    private function checkPrice() {
        $client = new Client([
            'timeout'  => 10.0,
        ]);

		$products = $this->productRepository->all();

        foreach ($products as $product) {

            $itemId = $product['item_id'];

            $response = $client->request('GET', 'http://open.api.ebay.com/shopping?'
									. 'callname=GetSingleItem'
									. '&responseencoding=JSON'
									. '&appid=HuyHaQua-BidRemin-PRD-02f4f54aa-9267511c'
									. '&siteid=0'
									. '&version=967'
									. '&ItemID=' . $itemId);

										   
            $content = $response->getBody()->getContents();

            $result = json_decode($content, true);
            $item = $result['Item'];
            $convertedCurrentPrice = $item['ConvertedCurrentPrice'];
            $value = $convertedCurrentPrice['Value'];

            if ($product['price'] != $value) {
                $input['price'] = $value;
                $product = $this->productRepository->update($input, $product['id']);     
            }
        
        }	  
    }

}
