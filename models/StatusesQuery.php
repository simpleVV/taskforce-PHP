<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Statuses]].
 *
 * @see Statuses
 */
class StatusesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Statuses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Statuses|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
