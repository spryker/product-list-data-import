<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\ProductListDataImport\Helper;

use Codeception\Module;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use SprykerTest\Shared\Testify\Helper\TableRelationsCleanupHelper;

class ProductListDataImportHelper extends Module
{
    /**
     * @var array
     */
    protected $productListMockData = [
        [
            'key' => 'test-01',
            'type' => 'whitelist',
            'title' => 'Test Product White List',
        ],
        [
            'key' => 'test-02',
            'type' => 'blacklist',
            'title' => 'Test Product Black List',
        ],
    ];

    public function haveProductLists(): void
    {
        foreach ($this->productListMockData as $productListData) {
            $productListEntity = SpyProductListQuery::create()
                ->filterByKey($productListData['key'])
                ->findOneOrCreate();
            $productListEntity->setKey($productListData['key'])
                ->setTitle($productListData['title'])
                ->setType($productListData['type'])
                ->save();
        }
    }

    public function ensureProductListTableIsEmpty(): void
    {
        $this->getTableRelationCleanupHelper()->ensureDatabaseTableIsEmpty($this->createProductListQuery());
    }

    public function ensureProductListCategoryTableIsEmpty(): void
    {
        $this->getTableRelationCleanupHelper()->ensureDatabaseTableIsEmpty($this->createProductListCategoryQuery());
    }

    public function ensureProductListProductConcreteTableIsEmpty(): void
    {
        $this->getTableRelationCleanupHelper()->ensureDatabaseTableIsEmpty($this->createProductListProductConcreteQuery());
    }

    public function assertProductListTableContainsRecords(): void
    {
        $query = $this->createProductListQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    public function assertProductListCategoryTableContainsRecords(): void
    {
        $query = $this->createProductListCategoryQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    public function assertProductListConcreteProductTableContainsRecords(): void
    {
        $query = $this->createProductListProductConcreteQuery();
        $this->assertTrue($query->count() > 0, 'Expected at least one entry in the database table but database table is empty.');
    }

    protected function createProductListQuery(): SpyProductListQuery
    {
        return SpyProductListQuery::create();
    }

    protected function createProductListCategoryQuery(): SpyProductListCategoryQuery
    {
        return SpyProductListCategoryQuery::create();
    }

    protected function createProductListProductConcreteQuery(): SpyProductListProductConcreteQuery
    {
        return SpyProductListProductConcreteQuery::create();
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Testify\Helper\TableRelationsCleanupHelper
     */
    protected function getTableRelationCleanupHelper()
    {
        return $this->getModule('\\' . TableRelationsCleanupHelper::class);
    }
}
