<?php
namespace admin\models;

use Yii;
use yii\base\Model;
use admin\models\Organizer;

/**
 * Signup form
 */
class OrganizerPasswordForm extends Model
{
    public $password;
    public $repassword;
    public $org;

    /**
     * {@inheritdoc}
     */
    public function __construct($organizer) 
    {
        parent::__construct();
        $this->org=$organizer;
    }

    public function rules()
    {
        return 
        [
            [['password','repassword'], 'string', 'min' => 6],
            [['password','repassword'], 'required'],
            ['repassword','compare','compareAttribute'=>'password','message'=>'密码和重复密码不相同'],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'password'=>'密码',
            'repassword'=>'重复密码',
        ];
    }

    /**
     * Signs organizer up.
     *
     * @return organizer|null the saved model or null if saving fails
     */
    public function repassword($organizer)
    {
        if (!$this->validate()) 
        {
            return null;
        }
        $organizer = $this->org;
        $organizer->setPassword($this->password);
        return $organizer->save(false);
    }
}
