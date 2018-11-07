<?php

namespace App\Controllers;

//use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Request;
use Slim\Http\Response;

use App\Models\InviteCode;
use App\Models\V2rayNode;
use App\Models\User;
use App\Models\Avinfo;
use App\Services\Auth;
use App\Services\Config;
use App\Services\DbConfig;
use App\Services\Logger;
use App\Utils\Check;
use App\Utils\Http;
use App\Utils\Hash;

/**
 *  HomeController
 */
class HomeController extends BaseController
{
    public function index($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $infos = Avinfo::where('id', '<', '12000')
            ->orderByRaw('RAND()')
            ->take(100)
            ->get();
        $infos = $infos->filter(function($info){
            $tmparray = explode('://',$info->embed);
            if(count($tmparray)>1){
                return false;
            } else{
                return true;
            }
        });
        $index = true;
        $key = "";
        return $this->view()
            ->assign('infos', $infos)
            ->assign('index', $index)
            ->assign('key', $key)
            ->display('hot.tpl');
    }

    public function apiIndex($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $infos = Avinfo::where('id', '<', '5000')
            ->orderByRaw('RAND()')
            ->take(48)
            ->get();
        $infos = $infos->filter(function($info){
            if (strpos($info->embed, '://') !== false) {
                return false;
            } else {
                return true;
            }
        });
        $res = [
            "ret" => 1, 
            "msg" => 'success',
            "data" => $infos 
        ];
        return $this->echoJson($response, $res);
    }

    public function moviePage($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $pageNum = $args['page'];
        $infos = Avinfo::paginate(48, ['*'], 'page', $pageNum);
        $index = false;
        $key = "";
        return $this->view()
            ->assign('infos', $infos)
            ->assign('index', $index)
            ->assign('key', $key)
            ->display('hot.tpl');
    }

    public function apiPage($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $pageNum = $args['page'];
        $infos = Avinfo::paginate(48, ['*'], 'page', $pageNum);
        $infos = $infos->filter(function($info){
            if (strpos($info->embed, '://') !== false) {
                return false;
            } else {
                return true;
            }
        });
        $res = [
            "ret" => 1, 
            "msg" => 'success',
            "data" => $infos 
        ];
        return $this->echoJson($response, $res);
    }

    public function search($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $key = $args['key'];
        $infos = Avinfo::where('alt', 'like', '%'.$key.'%')
            ->take(100)
            ->get();
        $infos = $infos->filter(function($info){
            if (strpos($info->embed, '://') !== false) {
                return false;
            } else {
                return true;
            }
        });

        $index = true;
        return $this->view()
            ->assign('infos', $infos)
            ->assign('index', $index)
            ->assign('key', $key)
            ->display('hot.tpl');
    }

    public function apiSearch($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $key = $args['key'];
        $infos = Avinfo::where('alt', 'like', '%'.$key.'%')
            ->take(100)
            ->get();
        $infos = $infos->filter(function($info){
            if (strpos($info->embed, '://') !== false) {
                return false;
            } else {
                return true;
            }
        });
        $res = [
            "ret" => 1, 
            "msg" => 'success',
            "data" => $infos 
        ];
        return $this->echoJson($response, $res);
    }

    public function watch($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $id = $args['id'];
        $info = Avinfo::where('data_id', $id)->first();
        if ($info->embed == "") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www5.javfinder.is/stream/sw0/" . $id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "user-agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.2272.101 Safari/537.36"
                ),
            ));
            $respon = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($respon, true);
                $data = json_decode($res["data"], true);
                $command = "node ../javfinder.js" . " " . $data["ct"] . " " . $data["iv"] . " " . $data["s"];
                exec($command,$array);
                $out = $array[0];
                $out = str_replace("fembed://", "", $out);
                // 把fembed保存了
                $info->embed = $out;
                $info->save();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://www.fembed.com/api/sources/" . $out,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                        "user-agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.2272.101 Safari/537.36"
                    ),
                ));
                $respon = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $res = json_decode($respon, true);
                    if ($res["success"] == false) {
                        // echo $res["data"];
                        // return;
                        return $this->redirect($response, '/');
                    }
                    $last = end($res["data"]);
                    $url  = $last["file"];
                    $array = explode('token', $url);
                    $str = str_replace('com/','com',$array[0]);
                    $url = 'http://proxy.mekelove.ml/' . 'token' . $array[1] . '?' . $str;  
                    // return $this->redirect($response, $url);
                    $infos = Avinfo::where('star', 'like', '%'.$info->star.'%')
                        ->take(20)
                        ->get();
                    $infos = $infos->filter(function($info){
                        if (strpos($info->embed, '://') !== false) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    return $this->view()
                        ->assign('info', $info)
                        ->assign('infos', $infos)
                        ->assign('url', $url)
                        ->assign('index', false)
                        ->assign('key', "")
                        ->display('watch.tpl');
                }
            }
            curl_close($curl);
        } else {
            // fembed已存在
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.fembed.com/api/sources/" . $info->embed,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "user-agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.2272.101 Safari/537.36"
                ),
            ));
            $respon = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $res = json_decode($respon, true);
                if ($res["success"] == false) {
                    // echo $res["data"];
                    // return;
                    return $this->redirect($response, '/');
                }
                $last = end($res["data"]);
                $url  = $last["file"];
                $array = explode('token', $url);
                $str = str_replace('com/','com',$array[0]);
                $url = 'http://proxy.mekelove.ml/' . 'token' . $array[1] . '?' . $str;  
                // return $this->redirect($response, $url);
                $infos = Avinfo::where('star', 'like', '%'.$info->star.'%')
                    ->take(20)
                    ->get();
                $infos = $infos->filter(function($info){
                    if (strpos($info->embed, '://') !== false) {
                        return false;
                    } else {
                        return true;
                    }
                });
                return $this->view()
                        ->assign('info', $info)
                        ->assign('infos', $infos)
                        ->assign('url', $url)
                        ->assign('index', false)
                        ->assign('key', "")
                        ->display('watch.tpl');
            }
            curl_close($curl);
        }
    }

    public function apiWatch($request, $response, $args)
    {
        if ($this->checkBrowser($request) == false) {
            return;
        }

        $id = $args['id'];
        $info = Avinfo::where('data_id', $id)->first();
        if ($info->embed == "") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www5.javfinder.is/stream/sw0/" . $id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "user-agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.2272.101 Safari/537.36"
                ),
            ));
            $respon = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                $res = [
                    "ret" => 0, 
                    "msg" => "cURL Error #:" . $err
                ];
                return $this->echoJson($response, $res);
            } else {
                $res = json_decode($respon, true);
                $data = json_decode($res["data"], true);
                $command = "node ../javfinder.js" . " " . $data["ct"] . " " . $data["iv"] . " " . $data["s"];
                exec($command,$array);
                $out = $array[0];
                $out = str_replace("fembed://", "", $out);
                // 把fembed保存了
                $info->embed = $out;
                $info->save();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://www.fembed.com/api/sources/" . $out,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => array(
                        "user-agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.2272.101 Safari/537.36"
                    ),
                ));
                $respon = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    $res = [
                        "ret" => 0, 
                        "msg" => "cURL Error #:" . $err
                    ];
                    return $this->echoJson($response, $res);
                } else {
                    $res = json_decode($respon, true);
                    if ($res["success"] == false) {
                        $res = [
                            "ret" => 0, 
                            "msg" => $res["data"]
                        ];
                        return $this->echoJson($response, $res);
                    }
                    $last = end($res["data"]);
                    $url  = $last["file"];
                    $array = explode('token', $url);
                    $str = str_replace('com/','com',$array[0]);
                    $url = 'http://proxy.mekelove.ml/' . 'token' . $array[1] . '?' . $str;  
                    $res = [
                        "ret" => 1, 
                        "msg" => 'success',
                        "url" => $url
                    ];
                    return $this->echoJson($response, $res);
                }
            }
            curl_close($curl);
        } else {
            // fembed已存在
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.fembed.com/api/sources/" . $info->embed,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_HTTPHEADER => array(
                    "user-agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.2272.101 Safari/537.36"
                ),
            ));
            $respon = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                $res = [
                    "ret" => 0, 
                    "msg" => "cURL Error #:" . $err
                ];
                return $this->echoJson($response, $res);
            } else {
                $res = json_decode($respon, true);
                if ($res["success"] == false) {
                    $res = [
                        "ret" => 0, 
                        "msg" => $res["data"]
                    ];
                    return $this->echoJson($response, $res);
                }
                $last = end($res["data"]);
                $url  = $last["file"];
                $array = explode('token', $url);
                $str = str_replace('com/','com',$array[0]);
                $url = 'http://proxy.mekelove.ml/' . 'token' . $array[1] . '?' . $str;  
                $res = [
                    "ret" => 1, 
                    "msg" => 'success',
                    "url" => $url
                ];
                return $this->echoJson($response, $res);
            }
            curl_close($curl);
        }
    }

    public function checkBrowser($request) {
        $headerArray = $request->getHeader('User-Agent');
        if (strpos($headerArray[0], 'MQQBrowser') == true) {
            $this->echoNoti();
            return false;
        }
        if (strpos($headerArray[0], 'UIWebView') == true) {
            $this->echoNoti();
            return false;
        }
        return true;
    }

    public function echoNoti()
    {
        echo 
            '<!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, 
            shrink-to-fit=no, user-scalable=no" />
            <title>请点击右上角选择在浏览器中打开</title>
            <style type="text/css"> 
            body {
                text-align:center;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                font-family: \'Source Sans Pro\',\'Helvetica Neue\',Helvetica,Arial,sans-serif;
                font-weight: 400;
                overflow-x: hidden;
                overflow-y: auto;
            }
            a {
                display: inline-block;
                padding: 6px 12px;
                margin-bottom: 8px;
                margin-right: 3px;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.42857143;
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                -ms-touch-action: manipulation;
                touch-action: manipulation;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
                background-image: none;
                border: 1px solid transparent;
                border-radius: 4px;
                background-color: #3c8dbc;
                box-shadow: none;
                color: #fff;
                text-decoration: none;
                box-sizing: border-box;
            }
            </style>
            </head>
            <body>
            </br>
            </br>
            <h3>请点击右上角选择在浏览器中打开</h3>
            </body>
            </html>';
    }

    public function configclient()
    {
        return $this->view()->display('configclient.tpl');
    }

    public function getServerConfig($request, $response, $args)
    {
        $email = $request->getParam('email');
        $email = strtolower($email);
        $passwd = $request->getParam('passwd');
        $user = User::where('email', '=', $email)->first();
        if ($user == null) {
            $res['ret'] = 0;
            $res['msg'] = "邮箱或者密码错误";
            return $this->echoJson($response, $res);
        }
        if (!Hash::checkPassword($user->pass, $passwd)) {
            $res['ret'] = 0;
            $res['msg'] = "邮箱或者密码错误";
            return $this->echoJson($response, $res);
        }
        // 从v2ray_node读取配置
        $nodes = v2rayNode::all();
        $configJson = [];
        foreach ($nodes as $node) {
            $addNode["address"] = $node->address;
            $addNode["port"] = (int)$node->port;
            $addNode["id"] = $user->uuid;
            $addNode["alterId"] = (int)$node->alter_id;
            $addNode["security"] = $node->security;
            $addNode["network"] = $node->getWebsocketAlias();
            $addNode["remarks"] = $node->name;
            $addNode["headerType"] = $node->type;
            $addNode["requestHost"] = $node->path;
            $addNode["streamSecurity"] = $node->getTlsAlias();
            array_push($configJson, $addNode);
        }
        return $this->echoJson($response, $configJson);
    }

    public function getAndroidServerConfig($request, $response, $args)
    {
        $email = $request->getParam('email');
        $email = strtolower($email);
        $passwd = $request->getParam('passwd');
        $user = User::where('email', '=', $email)->first();
        if ($user == null) {
            $res['ret'] = 0;
            $res['msg'] = "邮箱或者密码错误";
            return $this->echoJson($response, $res);
        }
        if (!Hash::checkPassword($user->pass, $passwd)) {
            $res['ret'] = 0;
            $res['msg'] = "邮箱或者密码错误";
            return $this->echoJson($response, $res);
        }
        // 从v2ray_node读取配置
        $nodes = v2rayNode::all();
        $configJson = [];
        foreach ($nodes as $node) {
            $addNode["add"] = $node->address;
            $addNode["ps"] = $node->name;
            $addNode["port"] = $node->port;
            $addNode["id"] = $user->uuid;
            $addNode["aid"] = $node->alter_id;
            $addNode["net"] = $node->getWebsocketAlias();
            $addNode["host"] = $node->path;
            $addNode["tls"] = $node->getTlsAlias();
            $addNode["type"] = $node->type;
            $addNodeStr = json_encode($addNode);
            $addNodeStr = base64_encode($addNodeStr);
            $addNodeStr = "vmess://" . $addNodeStr;
            array_push($configJson, $addNodeStr);
        }
        // return $this->echoJson($response, $configJson);
        echo json_encode($configJson, JSON_UNESCAPED_SLASHES);
    }

    public function code()
    {
        $msg = DbConfig::get('home-code');
        $codes = InviteCode::where('user_id', '=', '0')->take(10)->get();
        return $this->view()->assign('codes', $codes)->assign('msg', $msg)->display('code.tpl');
    }

    public function debug($request, $response, $args)
    {
        $server = [
            "headers" => $request->getHeaders(),
            "content_type" => $request->getContentType()
        ];
        $res = [
            "server_info" => $server,
            "ip" => Http::getClientIP(),
            "version" => Config::get('version'),
            "reg_count" => Check::getIpRegCount(Http::getClientIP()),
        ];
        Logger::debug(json_encode($res));
        return $this->echoJson($response, $res);
    }

    public function tos()
    {
        return $this->view()->display('tos.tpl');
    }

    public function postDebug(Request $request,Response $response, $args)
    {
        $res = [
            "body" => $request->getBody(), 
            "params" => $request->getParams() 
        ];
        return $this->echoJson($response, $res);
    }

}
