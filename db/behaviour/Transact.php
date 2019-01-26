<?php
namespace ActiveRecord\db\Behaviour;


use ActiveRecord\db\ActiveRecord;

/**
 * Class Transact
 */
trait Transact
{
    /**
     * @param $callback
     * @return mixed
     * @throws \Exception
     */
    private function _transact($callback) {
        $transaction = ActiveRecord::getDb()->beginTransaction();
        try {
            $result = $callback();
            $transaction->commit();
            return $result;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}