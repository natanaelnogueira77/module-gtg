<?php

namespace Src\Core\App;

use DateTime;
use DateInterval;
use Exception;
use League\Plates\Engine;
use GTG\DataLayer\Connect;
use ReflectionClass;
use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Config;
use Src\Core\Models\EmailTemplate;
use Src\Core\Models\Menu;
use Src\Core\Models\User;
use Src\Core\Models\UserMeta;
use Src\Core\Models\UserType;
use Src\Core\Support\Excel;
use Src\Core\Support\PDFConverter;
use Src\Core\Support\XML;
use Src\Core\System\DataTable;
use Src\Core\System\TableList;

class CModel 
{
    protected $view;
    protected $template;
    protected $message;
    protected $router;
    protected $error;
    protected $email;
    protected $PDFConverter;
    protected $excel;
    protected $xml;
    protected $scripts;
    protected $styles;

    public function __construct($router) 
    {
        $this->router = $router;
    }

    protected function setMessage(string $message, string $type = 'success'): void
    {
        $this->message = message($message, $type);
        if($this->message['type'] == 'error') {
            throw new Exception($this->message['message']);
        }
    }

    protected function setTheme(string $themeName, array $data = []): void
    {
        $this->view = new Engine(__DIR__ . "/../../../themes/{$themeName}", 'php');
        $this->view->addData($data);
    }

    protected function setTemplate(string $templatePath): void
    {
        $this->template = new Engine($templatePath, 'php');
    }

    protected function setStylesPath(string $path): void
    {
        $this->styles = url($path);
    }

    protected function setScriptsPath(string $path): void
    {
        $this->scripts = url($path);
    }

    protected function getMessage(): ?array 
    {
        return $this->message;
    }

    protected function getStyle(string $filename, bool $version = true): string
    {
        return $this->styles . "/{$filename}.css" . ($version ? ('?v=' . SYS_VERSION) : '');
    }

    protected function getRawStyle(string $path, bool $version = true): string 
    {
        return url($path) . '.css' . ($version ? ('?v=' . SYS_VERSION) : '');
    }

    protected function getScript(string $filename, bool $version = true): string
    {
        return $this->scripts . "/{$filename}.js" . ($version ? ('?v=' . SYS_VERSION) : '');
    }

    protected function getRawScript(string $path, bool $version = true): string 
    {
        return url($path) . '.js' . ($version ? ('?v=' . SYS_VERSION) : '');
    }

    protected function getRoute(string $route, array $params = []): ?string 
    {
        return $this->router->route($route, $params);
    }

    protected function throwException(string $msg): void 
    {
        throw new Exception($msg);
    }

    protected function loadView(string $vName = '', array $params = array()): void 
    {
        $this->view->addData($params);
        echo $this->view->render($vName);
    }

    protected function getView(string $vName = '', array $params = array())  
    {
        $view = $this->view;
        $view->addData($params);
        return $view->render($vName);
    }

    protected function getTemplateView(string $tName = '', array $params = array())
    {
        $template = $this->template;
        $template->addData($params);
        return $template->render($tName);
    }

    protected function echoCallback($callback): void 
    {
        if($this->error) {
            $callback['message'] = message($this->error->getMessage(), 'error');
            if((new ReflectionClass($this->error))->getShortName() == 'ValidationException') {
                $callback['errors'] = $this->error->getErrors();
            }
        }

        if($this->message) {
            $callback['message'] = $this->message;
        }

        echo json_encode($callback);
    }

    protected function getSessionUser(): ?User
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        return isset($_SESSION[SESS_NAME]) ? $_SESSION[SESS_NAME] : null;
    }

    protected function setSessionUser($user): void 
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        $_SESSION[SESS_NAME] = $user;
    }

    protected function getConfigMeta(string $meta) 
    {
        return Config::getMetaByName($meta);
    }

    protected function getConfigMetas(array $metas = []): array 
    {
        return Config::getMetasByName($metas);
    }

    protected function getMenus(int $type = 0, array $keys = [], array $params = []): array 
    {
        return Menu::getTemplateMenus($type, $keys, $params);
    }

    protected function getUserTypeById(int $userTypeId): ?UserType 
    {
        return UserType::getById($userTypeId);
    }

    protected function getDateTime(?string $date = null): DateTime 
    {
        return new DateTime($date);
    }

    protected function setEmailTemplate($meta): void 
    {
        $this->email = EmailTemplate::getByMeta($meta);
    }

    protected function sendEmail(string $to, string $name = '', $params = []): void 
    {
        if($this->email) {
            try {
                $this->email->sendParams($to, $name, $params);
            } catch(Exception $e) {
                
            }
        }
    }

    protected function executeSQL(string $sql) 
    {
        $connect = Connect::getInstance();
        $stmt = $connect->prepare($sql);
        $stmt->execute();
    }

    protected function renderPDF(array $data = []): void
    {
        $this->PDFConverter = new PDFConverter();
        $this->PDFConverter->loadHtml($data['html']);
        $this->PDFConverter->setPaper($data['size'], $data['orientation']);
        $this->PDFConverter->render();
    }

    protected function streamPDF(array $data = []): void
    {
        $this->PDFConverter->stream($data['filename'], $data['options']);
    }

    protected function renderExcel(array $data = []): void 
    {
        $this->excel = new Excel($data);
    }

    protected function getExcel(string $name): void 
    {
        $this->excel->get($name);
    }

    protected function sendXML(array $data = []) 
    {
        $this->xml = new XML();
        $this->xml->setData(['xml' => $data['xml']]);
        
        return $this->xml->send([
            'url' => $data['url'],
            'method' => $data['method']
        ]);
    }

    protected function getTableList(array $data = []): TableList 
    {
        $tableList = new TableList();
        
        $page = isset($data['page']) && $data['page'] ? intval($data['page']) : 1;
        $order = isset($data['order']) && $data['order'] ? $data['order'] : 'name';
        $orderType = isset($data['order_type']) && $data['order_type'] ? $data['order_type'] : 'ASC';
        $limit = isset($data['limit']) && $data['limit'] ? intval($data['limit']) : 10;
        $search = isset($data['search']) && $data['search'] ? $data['search'] : '';

        $tableList->setPage($page)
            ->setOrder($order, $orderType)
            ->setLimit($limit)
            ->setSearch($search);

        return $tableList;
    }

    protected function getError(): ?Exception 
    {
        return $this->error;
    }
}