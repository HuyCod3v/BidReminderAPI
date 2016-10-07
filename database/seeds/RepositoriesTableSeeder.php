<?php

use Illuminate\Database\Seeder;

class RepositoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('repositories')->insert([
            'name' => 'Ebay',
            'link' => 'http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsByKeywords&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=HuyHaQua-BidRemin-PRD-02f4f54aa-9267511c&GLOBAL-ID=EBAY-US&keywords={@item_name}&paginationInput.entriesPerPage={@limit}',
        ]);
    }
}
