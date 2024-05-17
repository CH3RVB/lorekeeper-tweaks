<?php

namespace App\Console\Commands;

use App\Models\Shop\Shop;
use Illuminate\Console\Command;
use Carbon\Carbon;

class resetrandomstock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-random-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear and set randomized stock.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //get all shops with random stock
        $shops = Shop::whereNotNull('randomized_stock')->get();

        //here we go again!
        //there's so much randomization... i cannot be bothered. just put it all into reusable functs :endme:
        foreach ($shops as $shop) {
            $interval = $shop->shopRandomData['randomize_interval'];
            if ($interval == 1) {
                $this->randomizeStock($shop);
            } elseif ($interval == 2) {
                // check if it's start of week
                $now = Carbon::now();
                $day = $now->dayOfWeek;
                if ($day == 1) {
                    $this->randomizeStock($shop);
                }
            } elseif ($interval == 3) {
                // check if it's start of month
                $now = Carbon::now();
                $day = $now->day;
                if ($day == 1) {
                    $this->randomizeStock($shop);
                }
            }
        }
    }

    /**
     * Randomize the stock
     *
     */
    public function randomizeStock($shop)
    {
        if (isset($shop->decodedStock) && is_array($shop->decodedStock)) {
            if ($shop->stock->where('is_random_stock', 1)->count()) {
                //delete old stock
                foreach ($shop->stock->where('is_random_stock', 1) as $stocks) {
                    $stocks->delete();
                }
            }

            //if randomized qty
            if ($shop->shopRandomData['stock_range']) {
                //get new qty
                $stockqty = $this->getRandValue(1, $shop->shopRandomData['max_items']);
            } else {
                //disabled, so just get the max items
                $stockqty = $shop->shopRandomData['max_items'];
            }

            //now we can finally start randomizing :')

            //for # of qty, pull a number based on stock
            //randomize a bunch of stock based on the count

            $maxstock = sizeof($shop->decodedStock) - 1;
            $randstock = [];
            for ($i = 0; $i < $stockqty; $i++) {
                $randstock[] = $shop->decodedStock[$this->getRandValue(0, $maxstock)];
            }

            //now, finally, FINALLY, make the new stock with the values
            foreach ($randstock as $final) {

                $shop->stock()->create([
                    'shop_id' => $shop->id,
                    'item_id' => $final->item_id,
                    'currency_id' => $final->currency_id,
                    'cost' => $final->cost,
                    'stock_type' => $final->stock_type,
                    'use_user_bank' => isset($shop->shopRandomData['use_user_bank']) ? $shop->shopRandomData['use_user_bank'] : 1,
                    'use_character_bank' => isset($shop->shopRandomData['use_character_bank']) ? $shop->shopRandomData['use_character_bank'] : 1,
                    'is_fto' => isset($shop->shopRandomData['is_fto']) ? $shop->shopRandomData['is_fto'] : 0,
                    'is_limited_stock' => $shop->shopRandomData['is_limited_stock'] ?? 0,
                    'quantity' => isset($shop->shopRandomData['is_limited_stock']) ? $shop->shopRandomData['quantity'] : 0,
                    'purchase_limit' => isset($shop->shopRandomData['purchase_limit']) ? $shop->shopRandomData['purchase_limit'] : 0,
                    'purchase_limit_timeframe' => isset($shop->shopRandomData['purchase_limit_timeframe']) ? $shop->shopRandomData['purchase_limit_timeframe'] : null,
                    'is_visible' => isset($shop->shopRandomData['is_visible']) ? $shop->shopRandomData['is_visible'] : 1,
                    'restock' => isset($shop->shopRandomData['restock']) ? $shop->shopRandomData['restock'] : 0,
                    'restock_quantity' => isset($shop->shopRandomData['quantity']) ? $shop->shopRandomData['quantity'] : 0,
                    'restock_interval' => isset($shop->shopRandomData['restock_interval']) ? $shop->shopRandomData['restock_interval'] : 2,
                    'range' => isset($shop->shopRandomData['range']) ? $shop->shopRandomData['range'] : 0,
                    'is_timed_stock' => isset($shop->shopRandomData['is_timed_stock']) ? $shop->shopRandomData['is_timed_stock'] : 0,
                    'start_at' => isset($shop->shopRandomData['start_at']) ? $shop->shopRandomData['start_at'] : null,
                    'end_at' => isset($shop->shopRandomData['end_at']) ? $shop->shopRandomData['end_at'] : null,
                    'disallow_transfer' => isset($shop->shopRandomData['disallow_transfer']) ? $shop->shopRandomData['disallow_transfer'] : 0,
                    'is_random_stock' => true,
                ]);
            }
        } else {
            throw new \Exception('Shop doesn\'t have stock to randomize...');
        }

    }

    /**
     * general roller
     *
     */
    public function getRandValue($min, $max)
    {
        $roll = mt_rand($min, $max);
        return $roll;
    }

}
