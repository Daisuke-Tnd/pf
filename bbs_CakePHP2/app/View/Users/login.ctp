<?php 
$this->Flash->render('auth');
echo __('Please enter your username and password');
echo $this->Form->create('User');
echo $this->Form->input('User.username');
echo $this->Form->input('User.password');
echo $this->Form->end('Login');
echo $this->Html->link('パスワードを忘れた方はこちら', 'forget');