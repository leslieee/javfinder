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
        $infos = Avinfo::where('id', '<', '1000')
            ->orderByRaw('RAND()')
            ->take(48)
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
        return $this->view()
            ->assign('infos', $infos)
            ->assign('index', $index)
            ->display('hot.tpl');
    }

    public function moviePage($request, $response, $args)
    {
        $pageNum = $args['id'];
        $infos = Avinfo::paginate(48, ['*'], 'page', $pageNum);
        $index = false;
        return $this->view()
            ->assign('infos', $infos)
            ->assign('index', $index)
            ->display('hot.tpl');
    }

    public function watch($request, $response, $args)
    {

        // return $this->redirect($response, '/movie/page/10');
        $id = $args['id'];
        $info = Avinfo::where('data_id', $id)->first();
        if ($info->embed == "") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://javfinder.is/stream/sw0/" . $id,
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
                        echo $res["data"];
                        return;
                    }
                    $last = end($res["data"]);
                    $url  = $last["file"];
                    $array = explode('token', $url);
                    $str = str_replace('com/','com',$array[0]);
                    $url = 'http://proxy.mekelove.ml/' . 'token' . $array[1] . '?' . $str;  
                    return $this->redirect($response, $url);
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
                    echo $res["data"];
                    return;
                }
                $last = end($res["data"]);
                $url  = $last["file"];
                $array = explode('token', $url);
                $str = str_replace('com/','com',$array[0]);
                $url = 'http://proxy.mekelove.ml/' . 'token' . $array[1] . '?' . $str;  
                return $this->redirect($response, $url);
            }
            curl_close($curl);
        }
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
