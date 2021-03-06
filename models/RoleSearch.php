<?php
/**
 * RoleSearch.php
 *
 * @copyright Copyright &copy; Pedro Plowman, 2017
 * @author Pedro Plowman
 * @link https://github.com/p2made
 * @license MIT
 *
 * @package p2made/yii2-p2y2-users
 * @class \p2m\users\models\RoleSearch
 */

namespace p2m\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * RoleSearch represents the model behind the search form about `p2m\users\models\Role`.
 *
 * @see Role
 */
class RoleSearch extends \p2m\users\models\Role
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'can_admin', 'created_by', 'updated_by'], 'integer'],
			[['name', 'created_at', 'updated_at'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Role::find();

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			'id' => $this->id,
			'can_admin' => $this->can_admin,
			'created_at' => $this->created_at,
			'created_by' => $this->created_by,
			'updated_at' => $this->updated_at,
			'updated_by' => $this->updated_by,
		]);

		$query->andFilterWhere(['like', 'name', $this->name]);

		return $dataProvider;
	}
}
