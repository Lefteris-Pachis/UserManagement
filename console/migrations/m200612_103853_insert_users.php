<?php

use yii\db\Migration;

/**
 * Class m200612_103853_insert_users
 */
class m200612_103853_insert_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user',array(
            'username'=>'admin',
            'auth_key' =>'iJcI5XU7b_H1Q8v_-1tre8bh-A2XTkxw',
            'password_hash' => '$2y$13$Q/06sPH8RH1UXkfbwOSA7OQTM6PeFiu5TIwTi7hu9vBwlh2GGKVhW',
            'email' => 'admin@example.com',
            'status' => 10,
            'created_at' => 1591962578,
            'updated_at' => 1591962578,
            'verification_token' => 'hjKNe44HjaAd47mptcepZSGNQqfWr2yT_1591959661',
            'role' => 20
        ));

        $this->insert('user',array(
            'username'=>'user1',
            'auth_key' =>'f6c9EkLoSCfbY2c6FVfqIM4dWLjRB15-',
            'password_hash' => '$2y$13$LdLUf3c.MalHblPTaIzb6eblZqfRWcyQQ0y35/2cYR413kUQ/2G/m',
            'email' => 'user1@example.com',
            'status' => 10,
            'created_at' => 1591962578,
            'updated_at' => 1591962578,
            'verification_token' => 'rmIsanLCEr3fH-X3MSH1EZBIbYduMD9Y_1591959215',
            'role' => 10
        ));

        $this->insert('user',array(
            'username'=>'user2',
            'auth_key' =>'43MKyTHUVAqqBx1Bj49un9i7xBTRgLm0',
            'password_hash' => '$2y$13$M.yHp6W04OSUJMuTpgqhiuorvv/yogTQRK2PTlvzbP5MIcuLYn6Y.',
            'email' => 'user2@example.com',
            'status' => 10,
            'created_at' => 1591962578,
            'updated_at' => 1591962578,
            'verification_token' => 'iqFUFBk08XtRviMTe_4xMUmjbR7leFZP_1591959238',
            'role' => 10
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200612_103853_insert_users cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200612_103853_insert_users cannot be reverted.\n";

        return false;
    }
    */
}
