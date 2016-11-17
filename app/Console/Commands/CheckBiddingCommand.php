<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Repositories\UserRepository;
use App\Repositories\BiddingRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CartRepository;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

class CheckBiddingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-bidding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** @var  BiddingRepository */
    private $biddingRepository;

    /** @var  UserRepository */
    private $userRepository;

    /** @var  ProductRepository */
    private $productRepository;

    /** @var  CartRepository */
    private $cartRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CartRepository $cartRepo, BiddingRepository $biddingRepo, UserRepository $userRepo, ProductRepository $productRepo)
    {   
        parent::__construct();
        $this->biddingRepository = $biddingRepo;
        $this->userRepository = $userRepo;
        $this->productRepository = $productRepo;
        $this->cartRepository = $cartRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $biddings = $this->biddingRepository->all();
        foreach ($biddings as $bidding) {
            $product = $this->productRepository->find($bidding['product_id']);
            $user = $this->userRepository->find($bidding['user_id']);

            Log::info(json_encode($bidding));
            Log::info(json_encode($user));

            if (!empty($product)) {
                Log::info(json_encode($product));
                if ($product['price'] != $bidding['last_price']) {  

                    $lastPrice = $bidding['last_price'];

                    $biddingInput['last_price'] = $product['price']; 
                    $this->biddingRepository->update($biddingInput, $bidding['id']);
                    
                
                    
                    if (!empty($user['firebase_token'])) {     
                        Log::info($user['firebase_token']);  

                        $data['MessageType'] = "ChangePrice";
                        $data['Content'] = "Giá sản phẩm " . $product['name'] . " thay đổi từ " . $lastPrice . " thành " . $product['price'];
                        $data['ProductID'] = $product['id'];
                        $changedAt = Carbon::now();

                        $data['ChangedAt'] = $changedAt->toDateString();
                        $data['Price'] = $product['price'];
                        $this->sendFCMMessage($user['firebase_token'], $data);
                    }
                              
                }

                if ($product['price'] <= $bidding['bid_price']) {
                    if ($bidding['is_buy_automatically'] == true) {
                        $cartInput['product_id'] = $product['id'];
                        $cartInput['buy_price'] = $product['price'];
                        $cartInput['user_id'] = $user['id'];
                        $carts = $this->cartRepository->create($cartInput);

                        $deleteBidding = $this->biddingRepository->findWithoutFail($bidding['id']);
                        $deleteBidding->delete();

                        $data['MessageType'] = "BuyProduct";
                        $data['Content'] = "Hệ thống đã tự động mua giúp bạn sản phẩm " . $product['name'] . " với giá " . $product['price'] . "Vui lòng cập nhật đẩy đủ thông tin, chúng tôi sẽ liên hệ để thực hiện giao dịch";


                        $this->sendFCMMessage($user['firebase_token'], $data);
                    } else {
                        
                        $data['MessageType'] = "ReachBidPrice";
                        $data['Content'] = "Xin chúc mừng. Giá sản phẩm " . $product['name'] . " đã chạm mức mà bạn đã đấu giá là " . $bidding['bid_price'];

                        $this->sendFCMMessage($user['firebase_token'], $data);
                    }
                }
                
            }
        
        }
    }

    private function sendFCMMessage($token, $data) {
        $optionBuiler = new OptionsBuilder();
        $optionBuiler->setTimeToLive(60*20);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);

        $option = $optionBuiler->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, null, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

    }
}
