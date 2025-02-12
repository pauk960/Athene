<?php

//namespace Athene;
use Sirius\Ext\Request as ExtRequest;
use Sirius\Ext\Router;
use Sirius\Autoload;
use Sirius\Storage\Storage;

ini_set('display_errors', 1);
error_reporting(E_ALL);

include('config.php');
include('lib/Sirius/Autoload.php');

session_start();

function onError($code, $message, $file, $line) {
    $extResponse = Router::getInstance()->getResponse();
    //$extResponse->setData(false, 'success');
    //$extResponse->setData($message, 'message');
    $extResponse->type = 'exception';
    $extResponse->message = $message;
    $extResponse->file = $file;
    $extResponse->line = $line;
    $extResponse->trace = debug_backtrace();
    Router::getInstance()->sendResponse($extResponse);
    die();
}

function onException(\Exception $exception) {
    $extResponse = Router::getInstance()->getResponse();
    $extResponse->type = 'exception';
    $extResponse->message = $exception->getMessage();
    $extResponse->file = $exception->getFile();
    $extResponse->line = $exception->getLine();
    //$extResponse->trace = debug_backtrace();
    Router::getInstance()->sendResponse($extResponse);
    die();
}

set_error_handler('onError');
set_exception_handler('onException');

$autoload = new Autoload(APP_PATH . '/lib');
$database = Storage::create('database', 'Database\\Mysql', array(
    'host'  => DB_HOST,
    'user'  => DB_USER,
    'password'  => DB_PASS,
    'database'  => DB_NAME
));

/*$database = new \Sirius\Storage\Database\Mysql(array(
    'host'  => DB_HOST,
    'user'  => DB_USER,
    'password'  => DB_PASS,
    'database'  => DB_NAME
));*/

// Debugger
/*$debug = Debug::getInstance();

ob_start();


class JsonResponse {
    public $action, $method, $result, $tid, $type = 'rpc';
    
    public function __construct($action, $method, $result, $tid) {
        $this->action = $action;
        $this->method = $method;
        $this->result = $result;
        $this->tid = $tid;
    }
    
    public function __set($property, $value) {
        $this->$property = $value;
    }
    
    public function __toString() {
        return json_encode($this);
    }
}

class SubmitResponse extends JsonResponse {
    
    public $success = false;
    public $message = '';
    
    public function __toString() {
        return json_encode((object)array(
            'success' => $this->success,
            'message' => $this->message
        ));
    }
    
}

class FailureReponse {
    
    protected $success = false;
    
    public function __construct() {
        
    }
    
    public function __toString() {
        return json_encode($this);
    }
    
}

class Menu {
    
    public function side($node) {
        if($node == 'sidemenu') {
            return array(
                (object)array('text' => 'Imenik', 'id' => 'sidemenu/imenik', 'cls' => 'folder', 'expanded' => true),
                (object)array('text' => 'Dnevnik', 'id' => 'sidemenu/dnevnik', 'cls' => 'folder', 'expanded' => true),
                (object)array('text' => 'Administracija', 'id' => 'sidemenu/admin', 'cls' => 'folder', 'expanded' => false)
            );
        } else if($node == 'sidemenu/imenik') {
            return array(
                (object)array('text' => 'Učenici', 'id' => 'sidemenu/imenik/uceniklist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Razredi', 'id' => 'sidemenu/imenik/razredlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Dodaj učenika u razred', 'id' => 'sidemenu/imenik/ucenikrazredlist', 'leaf' => true, 'cls' => 'file')
            );
        } else if($node == 'sidemenu/dnevnik') {
            return array(
                (object)array('text' => 'Izostanci', 'id' => 'sidemenu/imenik/izostanaklist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Školske godine', 'id' => 'sidemenu/imenik/skgodlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Učitelji', 'id' => 'sidemenu/imenik/uciteljlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Države', 'id' => 'sidemenu/imenik/drzavalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Mjesta', 'id' => 'sidemenu/imenik/mjestolist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Nacionalna manjina', 'id' => 'sidemenu/imenik/nacionalnamanjinalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Nastavni Plan', 'id' => 'sidemenu/imenik/nastavniplanlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Događaji', 'id' => 'sidemenu/imenik/dogadjajlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Narodnost', 'id' => 'sidemenu/imenik/narodnostlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Škole', 'id' => 'sidemenu/imenik/skolalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Predmeti', 'id' => 'sidemenu/imenik/predmetlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Predmet razrednog odjeljenja', 'id' => 'sidemenu/imenik/predmetrazrednogodjeljenjalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Poteškoće', 'id' => 'sidemenu/imenik/poteskocalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Vrsta putnika', 'id' => 'sidemenu/imenik/vrstaputnikalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Zanimanja', 'id' => 'sidemenu/imenik/zanimanjelist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Titula', 'id' => 'sidemenu/imenik/titulalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Kategorija ocjena', 'id' => 'sidemenu/imenik/kategorijaocjenalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Slobodna aktivnost', 'id' => 'sidemenu/imenik/slobodnaaktivnostlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Razlog boravišta', 'id' => 'sidemenu/imenik/razlogboravistalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Predmetna cjelina', 'id' => 'sidemenu/imenik/predmetnacjelinalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Ocjene', 'id' => 'sidemenu/imenik/ocjenalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Dodijeljena Poteškoća', 'id' => 'sidemenu/imenik/dodijeljenapoteskocalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Nastavna Jedinica', 'id' => 'sidemenu/imenik/nastavnajedinicalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Metodska Jedinica', 'id' => 'sidemenu/imenik/metodskajedinicalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Nastavni sat', 'id' => 'sidemenu/imenik/nastavnisatlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Najava ispita', 'id' => 'sidemenu/imenik/najavaispitalist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Adresa', 'id' => 'sidemenu/imenik/adresalist', 'leaf' => true, 'cls' => 'file')
            );
        } else if($node == 'sidemenu/admin') {
            return array(
                (object)array('text' => 'Korisnici', 'id' => 'sidemenu/admin/userlist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Grupe', 'id' => 'sidemenu/admin/grouplist', 'leaf' => true, 'cls' => 'file'),
                (object)array('text' => 'Postavke', 'id' => 'sidemenu/admin/settings', 'leaf' => true, 'iconCls' => 'settingIcon'),
                (object)array('text' => 'Help', 'id' => 'sidemenu/admin/helplist', 'leaf' => true),
            );
        }
    }
    
}

if(isset($_GET)) {
    if(isset($_GET['node'])) {
        $menu = new Menu();
        header('Content-Type: text/javascript');
        echo json_encode($menu->side($_GET['node']));
        die();
    } else if(isset($_GET['logout'])) {
        session_destroy();
        die();
    }
}

if(isset($_POST) && !empty($_POST)) {
    //var_dump($_POST);   
    $response = new JsonResponse($_POST['extAction'], $_POST['extMethod'], null, $_POST['extTID']);
    $class = 'Athene\\Model\\' . $_POST['extAction'];
    $actionClass = new $class($_POST);
    $return = call_user_func(array($actionClass, $_POST['extMethod']));
  
    if($return === 0 || $return === false || $return instanceof Error) {
        $response->result->success = false;
        if($return instanceof Error) {
            $response->result->message = $return->__toString();
        }
    } else {
        $response->result->success = true;
    }
    
    echo $response;
} else if(isset($HTTP_RAW_POST_DATA)) {
    header('Content-Type: text/javascript; charset=utf-8');
    $jsonRequest = json_decode($HTTP_RAW_POST_DATA);
    //$firephp->log($jsonRequest, 'JSON request');
    $debug->log('JSON request', $HTTP_RAW_POST_DATA, __FILE__, __LINE__);
    
    $responses = array();
    if(is_array($jsonRequest)) {
        foreach($jsonRequest as $jr) {
            $class = 'Athene\\Model\\' . $jr->action;
            $actionClass = new $class;
            if(isset($jr->data)) {
                $return = call_user_func_array(array($actionClass, $jr->method), $jr->data);
            } else {
                $return = call_user_func(array($actionClass, $jr->method));
            }
            if(is_bool($return) || is_int($return)) {
                $rreturn = new \stdClass();
                if($return === true) {
                    $rreturn->success = true;
                } else {
                    $rreturn->success = false;
                }
                $response = new JsonResponse($jr->action, $jr->method, $rreturn, $jr->tid);
            } else {
                $xrd = (object)array(
                    'total' => $actionClass->count($jr->data),
                    'data' => $return
                );
                $response = new JsonResponse($jr->action, $jr->method, $xrd, $jr->tid);
            }
            $responses[] = $response;
        }
    } else {
        $class = 'Athene\\Model\\' . $jsonRequest->action;
        try {
            $actionClass = new $class;
        } catch(\Exception $e) {
            return $e->getMessage();
        }
        if(isset($jsonRequest->data)) {
            $return = call_user_func_array(array($actionClass, $jsonRequest->method), $jsonRequest->data);
        } else {
            $return = call_user_func(array($actionClass, $jsonRequest->method));
        }
        //var_dump($return);
        if(is_bool($return) || is_int($return)) {
            $rreturn = new \stdClass();
            if($return === true) {
                $rreturn->success = true;
            } else {
                $rreturn->success = false;
            }
            $response = new JsonResponse($jsonRequest->action, $jsonRequest->method, $rreturn, $jsonRequest->tid);
        } else {
            $xrd = (object)array(
                'total' => $actionClass->count($jsonRequest->data),
                'data' => $return
            );
            $response = new JsonResponse($jsonRequest->action, $jsonRequest->method, $xrd, $jsonRequest->tid);
            //var_dump($jsonRequest->data[0]-);
            /*if(isset($jsonRequest->data[0]->page)) {
                $response->total = $actionClass->count();
            }* /
        }
        $responses[] = $response;
        //$responses[] = new JsonResponse($jsonRequest->action, $jsonRequest->method, $return, $jsonRequest->tid);
    }
    echo implode(',', $responses);
}*/

if(isset($HTTP_RAW_POST_DATA)) {
    $extRequest = new ExtRequest($HTTP_RAW_POST_DATA);
    $r = Router::getInstance()->route($extRequest);
}

?>
