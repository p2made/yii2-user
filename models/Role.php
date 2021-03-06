<?php
/**
 * Role.php
 *
 * @copyright Copyright &copy; Pedro Plowman, 2017
 * @author Pedro Plowman
 * @link https://github.com/p2made
 * @license MIT
 *
 * @package p2made/yii2-p2y2-users
 * @class \p2m\users\models\Role
 */

namespace p2m\users\models;

use Yii;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $can_admin
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
	/**
	 * @var int Admin user role
	 */
	const ROLE_ADMIN = 1;

	/**
	 * @var int Default user role
	 */
	const ROLE_USER = 2;

	/**
	 * @var \p2m\users\modules\UsersModule
	 */
	public $module;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		if (!$this->module) {
			$this->module = Yii::$app->getModule("user");
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%role}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$rules = [
			[['name'], 'required'],
			[['name'], 'string', 'max' => 32],
			[['can_admin', 'created_by', 'updated_by'], 'integer'],
			[['created_at', 'updated_at'], 'safe'],
		];

		// add can_ rules
		foreach ($this->attributes() as $attribute) {
			if (strpos($attribute, 'can_') === 0) {
				$rules[] = [[$attribute], 'integer'];
			}
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('user', 'ID'),
			'name' => Yii::t('user', 'Name'),
			'can_admin' => Yii::t('user', 'Can Admin'),
			'created_at' => Yii::t('user', 'Created At'),
			'created_by' => 'Created By',
			'updated_at' => Yii::t('user', 'Updated At'),
			'updated_by' => 'Updated By',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => 'yii\behaviors\TimestampBehavior',
				'value' => function ($event) {
					return date("Y-m-d H:i:s");
				},
			],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		$user = $this->module->model("User");
		return $this->hasMany($user::className(), ['role_id' => 'id']);
	}

	/**
	 * Check permission
	 * @param string $permission
	 * @return bool
	 */
	public function checkPermission($permission)
	{
		$roleAttribute = "can_{$permission}";
		return (bool)$this->$roleAttribute;
	}

	/**
	 * Get list of roles for creating dropdowns
	 * @return array
	 */
	public static function dropdown()
	{
		// get all records from database and generate
		static $dropdown;
		if ($dropdown === null) {
			$models = static::find()->all();
			foreach ($models as $model) {
				$dropdown[$model->id] = $model->name;
			}
		}
		return $dropdown;
	}

	/**
	 * @inheritdoc
	 * @return RoleQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new RoleQuery(get_called_class());
	}
}
