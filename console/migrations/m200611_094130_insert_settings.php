<?php

use yii\db\Migration;

/**
 * Class m200611_094130_insert_settings
 */
class m200611_094130_insert_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings',array(
            'name'=>'file_size',
            'value' =>'2',
            'label' => 'Maximum file size (in megabytes)'
        ));
        $this->insert('settings',array(
            'name'=>'file_types',
            'value' =>'png,jpeg,jpg,pdf',
            'label' => 'Allowed file types (comma separated)'
        ));
        $this->insert('settings',array(
            'name'=>'file_number',
            'value' =>'10',
            'label' => 'Number of maximum uploaded files'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200611_094130_insert_settings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200611_094130_insert_settings cannot be reverted.\n";

        return false;
    }
    */
}
