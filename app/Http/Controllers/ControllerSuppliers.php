<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Supplier;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ControllerSuppliers extends Controller
{
    public function returnView()
    {
        $res_username = Session::get("res_username_session");
        $sup_username = Session::get("sup_username_session");

        if ($res_username != null) {
            $user = Restaurant::where("username", $res_username)->first();
            return view('suppliers')->with("user", $user)->with("existUserRes", true)->with("existUserSup", false);
        } elseif ($sup_username != null) {
            $user = Supplier::where("username", $sup_username)->first();
            return view('suppliers')->with("user", $user)->with("existUserRes", false)->with("existUserSup", true);
        } else
            return view('suppliers')->with("user", null)->with("existUserRes", false)->with("existUserSup", false);
    }

    public function addSuppliers()
    {
        $rows = Supplier::all();
        $json = array();
        foreach ($rows as $row) {
            if ($row->name == null) {
                continue;
            }
            $json[] = ["name" => $row->name, "address" => $row->address != null ? $row->address : "Non disponibile"];
        }
        return json_encode($json);
    }

    public function deleteProfile()
    {
        $username = Session::get("sup_username_session");
        Supplier::where("username", $username)->delete();
        Session::flush();
        return json_encode("delete");
    }

    public function logout()
    {
        Session::flush();
        return json_encode("logout");
    }

    public function update()
    {
        $username = Session::get("sup_username_session");
        $request = request();
        $name = $request->name;
        $address = $request->address;

        $supplier = Supplier::where("username", $username)->first();
        $supplier->name = $name;
        $supplier->address = $address;
        $supplier->save();
        return json_encode("update");
    }

    public function login()
    {
        $request = request();
        $username = $request->username;
        $password = $request->password;

        $row = Supplier::where("username", $username)->first();
        if ($row == null) {
            return json_encode("error_login_username");
        }
        if (!password_verify($password, $row->password)) {
            return json_encode("error_login_password");
        }

        Session::put("sup_username_session", $username);
        return json_encode("login");
    }

    public function register()
    {
        $request = request();
        $username = $request->username;
        $password = $request->password;
        $confirm_password = $request->confirm_password;
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $email = $request->email;
        $name = $request->name;
        $address = $request->address;

        $row = Supplier::where("username", $username)->first();
        if ($row != null)
            return json_encode("error_register_username_exists");
        if (!preg_match('/^[A-Za-z0-9à-ù_\-\.]{1,15}$/', $username))
            return json_encode("error_register_username");
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!#$%&()*+,.-:;<=>?@[{}ç£_§€"]).{8,15}$/', $password))
            return json_encode("error_register_password");
        if (strcmp($password, $confirm_password) != 0)
            return json_encode("error_register_password");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            return json_encode("error_register_email");

        $supplier = new Supplier;
        $supplier->username = $username;
        $supplier->password = $hash;
        $supplier->email = $email;
        $supplier->name = $name;
        $supplier->address = $address;
        $supplier->save();

        Session::put("sup_username_session", $username);
        return json_encode("register");
    }
}
