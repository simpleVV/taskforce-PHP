<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserSettings]].
 *
 * @see UserSettings
 */
class UserSettingsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserSettings[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserSettings|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
