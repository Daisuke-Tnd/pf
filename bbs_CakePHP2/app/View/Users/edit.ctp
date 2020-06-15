<h1>Edit User_Profile</h1>
<?php
echo $this->Form->create('User', array('type'=>'file'));
echo $this->Form->input('User.comment', array('label' => '一言コメント'));
echo $this->Form->input('User.user_file', array('label' => 'プロフィール画像', 'type' => 'file'));
echo $this->Form->end('Save User_profile');

// echo '$type';
// debug($type); 

// echo '$image_hash';
// debug($image_hash); 

// echo '$_SESSION';
// debug($_SESSION);

// echo '$_POST';
// debug($_POST); 
// echo '$_GET';
// debug($_GET); 


// echo '$id';
// debug($id);
// echo '$post';
// debug($post);