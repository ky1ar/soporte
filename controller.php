<?php
require_once 'model.php';
session_start();
class Controller {
    private $model;

    public function __construct($conn) {
        $this->model = new Model($conn);
    }

    public function processRequest($requestType, $postData) {
        switch ($requestType) {
            case 'src_ord':
                return $this->model->src_ord($postData);
                break;
            case 'log_in':
                return $this->model->log_in($postData);
                break;    
                
            case 'addOrder':
                return $this->model->addOrder($postData);
                break;
            case 'checkOrder':
                return $this->model->checkOrder($postData['orders'], $postData['document']);
                break;
            case 'login':
                return $this->model->login($postData['email'], $postData['pass']);
                break;
            case 'logout':
                return $this->model->logout();
                break;
            case 'searchMachine':
                return $this->model->searchMachine($postData['machine']);
                break;
            default:
                return array("success" => false, "message" => "Solicitud no válida");
                break;
        }
    }
}
?>
