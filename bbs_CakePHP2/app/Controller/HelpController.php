// app/Controller/HelpController.php
class HelpController extends AppController {

    public function index() {
        $this->ext = 'html';
        $this->render('/HelpCenter/toppage');
    }
    
}