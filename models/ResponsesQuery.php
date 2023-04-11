<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Responses]].
 *
 * @see Responses
 */
class ResponsesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Responses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Responses|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
