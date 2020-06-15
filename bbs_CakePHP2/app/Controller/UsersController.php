<?php
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		// ユーザー自身による一部機能を許可する
		$this->Auth->allow('add', 'edit', 'logout', 'forget', 'reset');
	}

	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			} else {
				$this->Session->setFlash('Username or password is incorrect');
			}
		}
	}

	public function logout() {
		$this->Auth->logout();
		return $this->redirect('/');
	}

	public function add() {
		if ($this->request->is('post')) {
			$post = $this->request->data;
			// $this->set('post', $post);
			// return;

			if ($this->User->save($post)) {
				$this->Session->setFlash(__('The user has been saved'));
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
			$post = $this->request->data;
			$this->set('post', $post);
		}
	}

	public function edit($id) {
		$post = $this->User->findById($id);
		if (!$this->request->data) {
			$this->request->data = $post;
		}
		$this->User->id = $id;
		$this->set('id', $id);
		if ($_SESSION['Auth']['User']['id'] !== $id) {
			$this->Session->setFlash('ログインユーザーではありません');
			return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
		}
		if ($this->request->is(array('post', 'put'))) {
			//パラメータよりイメージ情報を取得
			$image = $this->request->data;
			if (!empty($image['User']['user_file']['tmp_name'])) {
				$type = exif_imagetype($image['User']['user_file']['tmp_name']);
				if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
					$this->Session->setFlash('file extension error');
					return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
				}
				$image_hash = sprintf(
					'%s_%s.%s',
					time(),
					sha1(uniqid(mt_rand(), true)), // 乱数を生成
					substr($image['User']['user_file']['type'], (strpos($image['User']['user_file']['type'], '/') + 1))
				);
				$this->set('image_hash', $image_hash);
				$check_array = array(1 => 'image/jpeg', 2 => 'image/jpg', 3 => 'image/gif', 4 => 'image/png');
				if (!array_search($image['User']['user_file']['type'], $check_array)) {
					$this->Session->setFlash('file extension error');
					return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
				}
				// 画像のみ
				if (empty($image['User']['comment']) && $image['User']['comment'] !== '') {
					$input_data = array(
						'User' => array(
							'user_file' => $image_hash
						)
					);
					// 一言コメント,画像どちらも
				} else {
					$input_data = array(
						'User' => array(
							'user_file' => $image_hash,
							'comment' => $image['User']['comment']
						)
					);
				}
				//イメージ保存先パス
				$img_save_path = IMAGES;
				//イメージの保存処理
				move_uploaded_file($image['User']['user_file']['tmp_name'], $img_save_path . DS . $image_hash);
			// 一言コメントのみ
			} elseif (empty($image['User']['user_file']['tmp_name'])) {
				$input_data = array(
					'User' => array(
						'comment' => $image['User']['comment']
					)
				);
			}
			// データの保存
			if ($this->User->save($input_data, array('validate' => false))) {
				$this->Session->setFlash('編集が完了しました.');
				return $this->redirect(array('controller' => 'posts', 'action' => 'index'));
			} else {
				$this->Session->setFlash('編集に失敗しました');
			}
		}
		// ログインユーザーの情報を取得
		$user = $this->Auth->user();
		// ビューに渡す
		$this->set('user', $user);
		$this->render('edit');
	}

	public function forget() {
		// postがされたらデータの取得
		if ($this->request->is('post')) {
			$email = $this->request->data('User.email');
			$users = $this->User->find('all');
			$email_db = Set::extract('/User/email', $users);
			// DBと一致する場合にメールの送信
			if (in_array($email, $email_db, true)) {
				$password_resets_table = $this->User->PasswordReset->find('all');
				$password_reset = Set::extract('/PasswordReset/email', $password_resets_table);
				$this->set('password_resets_table', $password_resets_table);
				$this->set('password_reset', $password_reset);
				if (in_array($email, $password_reset, true)) {
					// expireを更新するために、Resetテーブルにemailが存在していれば削除
					$options = array('conditions' => array('PasswordReset.email' => $email));
					$table_post = $this->User->PasswordReset->find('all', $options);
					$this->User->PasswordReset->delete($table_post[0]['PasswordReset']['id']);
				}
				$data = [
					'email' => $email,
					'selector' => bin2hex(random_bytes(8)),
					'token' => bin2hex(random_bytes(32)),
					'expire' => date("Y-m-d H:i:s", strtotime("30min"))
				];
				$hash_token = password_hash($data['token'], PASSWORD_DEFAULT);
				$url = Router::url([
					'controller' => 'Users',
					'action' => 'reset',
					'?' => ['selector' => $data['selector'], 'token' => $hash_token],
				], true);
				$this->User->PasswordReset->save($data);
				$email = new CakeEmail('default');
				$email->to($data['email']);
				$messages = $email->send($url);
				$this->set('messeages', $messages);
			}
			$this->Session->setFlash('再発行用URLを送信しました');
			$this->redirect('forget');
		}
		$this->render('forget');
	}

	public function reset() {
		$post = $this->request->data;
		if ($post) {
			// URLのselectorで絞る
			$options = array(
				'conditions' => array(
					'PasswordReset.selector' => $this->request->query('selector')
				)
			);
			$password_resets_table = $this->User->PasswordReset->find('all', $options);
			// expireで有効期限内か確認
			if (date("Y-m-d H:i:s") > $password_resets_table[0]['PasswordReset']['expire']) {
				$this->Session->setFlash(__('不正なアクセスです。再度お試しください'));
				return $this->redirect(array('controller' => 'users', 'action' => 'forget'));
			}
			$token_db = $password_resets_table[0]['PasswordReset']['token'];
			// 認証
			if (!password_verify($token_db, $this->request->query('token'))) {
				throw new NotFoundException();
			}
			$options = array(
				'conditions' => array(
					'User.email' => $password_resets_table[0]['PasswordReset']['email']
				)
			);
			$user = $this->User->find('first', $options);
			$id = $user['User']['id'];
			$data = array(
				'User' => array(
					'id' => $id,
					'password' => $post['User']['password']
				)
			);
			if ($this->User->save($data)) {
				// saveが出来たら、URLの再利用を不可にする
				$this->User->PasswordReset->delete($password_resets_table[0]['PasswordReset']['id']);
				$this->Session->setFlash(__('パスワードを変更しました'));
				$this->redirect(['action' => 'login']);
			} else {
				$this->Session->setFlash('パスワードの変更に失敗しました');
				$this->redirect($this->request->referer());
			}
		}
	}

}
