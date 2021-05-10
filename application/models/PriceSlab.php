<?php
class PriceSlab extends MyAppModel
{
    const DB_TBL = 'tbl_pricing_slabs';
    const DB_TBL_PREFIX = 'prislab_';

    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, static::DB_TBL_PREFIX . 'id', $id);
    }

    public function saveSlab(int $min, int $max): bool
    {
        $this->assignValues([
            'prislab_min' => $min,
            'prislab_max' => $max,
            'prislab_active' => applicationConstants::YES,
        ]);
        return $this->save([], $this->getFlds());
    }


    public static function getSearchObject(bool $activeOnly = false): object
    {
        $searchBase = new SearchBase(self::DB_TBL, 'ps');

        if ($activeOnly) {
            $searchBase->addCondition('prislab_active', '=', applicationConstants::ACTIVE);
        }

        return $searchBase;
    }

    public function getAllSlabs(bool $activeOnly = true): array
    {
        $searchObject = self::getSearchObject($activeOnly);
        $searchObject->doNotLimitRecords();
        $resultSet  =  $searchObject->getResultSet();
        return  FatApp::getDb()->fetchAll($resultSet);
    }

    public function isSlapCollapse(int $min, int $max): bool
    {
        $searchObject = PriceSlab::getSearchObject();
        $searchObject->doNotCalculateRecords();
        $searchObject->addCondition('prislab_max', '>=', $min);
        $searchObject->addCondition('prislab_min', '<=', $max);
        $searchObject->addCondition('prislab_id', '!=', $this->mainTableRecordId);
        $searchObject->setPageSize(1);
        $resultSet = $searchObject->getResultSet();
        $slabData = FatApp::getDb()->fetch($resultSet);
        return (!empty($slabData));
    }
}
