<?php
App::uses('CakeEmail', 'Network/Email');

class MailsController extends AppController {

	public function index() {

		// 引数にはEmailConfigの変数名を指定
		$email = new CakeEmail('default');
		$email->to('able.was.i.ere.i.saw.elba.1814@gmail.com');
		$messages = $email->send('testes!');

		$this->set('messages', $messages);

		// $email = new CakeEmail(array('charset' => 'UTF-8'));
		// $email->transport('Debug');

		// $email->from('able.was.i.ere.i.saw.elba.1814@gmail.com', 'つのだ');
		// $email->to('able.was.i.ere.i.saw.elba.1814@gmail.com');
		// $email->subject('テストメールの件名です');
	
		// $email->template('thank_you', 'sample_layout');
		// $email->emailFormat('html');
	
		// $email->viewVars(array('user' => 'つのだ'));
	
		// $messages = $email->send();
	
		// $this->set('messages', $messages);
	}
}
