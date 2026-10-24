<?php

namespace App\Console\Commands;

use App\Integrations\Shopware\Import\ShopwareCategoryImporter;
use App\Integrations\Shopware\Import\ShopwareProductImporter;
use App\Models\Store;
use Illuminate\Console\Command;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;

class TestShopwareConnection extends Command
{
    protected $signature = 'shopware:test {store}';

    protected $description = 'Import products from Shopware';

    public function __construct(
        protected ShopwareProductImporter $productImporter,
        protected ShopwareCategoryImporter $categoryImporter,
    ) {
        parent::__construct();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws \JsonException
     */
    public function handle()
    {
        //$connection = StoreConnection::query()
        //    ->where('store_id', $this->argument('store'))
        //    ->firstOrFail();

        $store = Store::query()
            ->findOrFail(
                $this->argument('store')
            );


        $this->info(
            "Importing categories for {$store->name}"
        );

        $this->categoryImporter->import($store);

        $this->info(
            "Importing products for {$store->name}"
        );

        $this->info(
            "Import categories completed"
        );

        $this->productImporter->import($store);

        $this->info(
            "Import products completed"
        );


        return self::SUCCESS;
    }
}
