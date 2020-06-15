<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
	// ハッシュ処理
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['password'])) {
			$passwordHasher = new BlowfishPasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return true;
	}

	public $validate = array(
		'user_file' => array(
			'uploadError' => array(
				'rule' => array('uploadError'),
				'message' => array('Error uploading file'),
				'allowEmpty' => true
			),
			'size' => array(
				'fileSize' => array(
					'rule' => array('fileSize', '<=', '10MB'),  // 10M以下
					'message' => array('画像は 10MB 以下にしてください。')
				),
			),
		),
		'username' => array(
			array(
				'rule' => 'isUnique',
				// 'required' => true,
				'message' => 'そのユーザー名は既に使われています'
			),
			array(
				'rule' => array('between', 2, 50),
				'message' => '2文字以上、50文字以下で入力してください'
			)
		),
		'email' => array(
			array(
				'rule' => 'isUnique',
				// 'required' => true,
				'message' => 'そのユーザー名は既に使われています'
			),
			array(
				'rule' => array('maxLength', 255),
				'message' => '255文字以下で入力してください'
			)
		),
		'password' => array(
			array(
				'rule' => array('between', 8, 255),
				// 'required' => true,
				'message' => '8文字以上で入力してください'
			),
			array(
				'rule' => array('is_alphabet_number_only'),
				'message' => '半角英数字で入力して下さい'
			)
		)
	);

	public function isOwnedBy($post, $user) {
		return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
	}

	// public $hasMany = 'Post';
	public $hasMany = 'PasswordReset';
}
