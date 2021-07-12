<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Supplier;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ControllerRestaurants extends Controller
{

    public function returnView()
    {
        $res_username = Session::get("res_username_session");
        $sup_username = Session::get("sup_username_session");

        if ($res_username != null) {
            $user = Restaurant::where("username", $res_username)->first();
            return view('restaurants')->with("user", $user)->with("existUserRes", true)->with("existUserSup", false);
        } elseif ($sup_username != null) {
            $user = Supplier::where("username", $sup_username)->first();
            return view('restaurants')->with("user", $user)->with("existUserRes", false)->with("existUserSup", true);
        } else
            return view('restaurants')->with("user", null)->with("existUserRes", false)->with("existUserSup", false);
    }

    public function addRestaurants()
    {
        $PATH_FILE_NOT_FOUND = "NotFound.jpg";
        $PATH_IMAGES = "images/restaurants/";
        $rows = Restaurant::all();
        $json = array();
        foreach ($rows as $row) {
            if ($row->name == null) {
                continue;
            }
            if ($row->image == null) {
                $row->image = " ";
            }
            $json[] = ["name" => $row->name, "address" => $row->address, "description" => $row->description, "image" => file_exists($PATH_IMAGES . $row->image) ? $row->image : $PATH_FILE_NOT_FOUND];
        }
        return json_encode($json);
    }

    public function deleteProfile()
    {
        $PATH_IMAGES = "images/restaurants/";
        $username = Session::get("res_username_session");
        $restaurant = Restaurant::where("username", $username)->first();
        if ($restaurant->image != null && file_exists($PATH_IMAGES . $restaurant->image)) {
            unlink($PATH_IMAGES . $restaurant->image);
        }
        Restaurant::where("username", $username)->delete();
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
        $PATH_IMAGES = "public/images/restaurants/";
        $request = request();
        $username = Session::get("res_username_session");
        $restaurant = Restaurant::where("username", $username)->first();
        $name = $request->name;
        $address = $request->address;
        $description = $request->description;
        $file = $request->file("image");
        
        if($file != null) {
            if (file_exists($PATH_IMAGES . $restaurant->image)) {
                unlink($PATH_IMAGES . $restaurant->image);
            }
            if($file->getError() != 0) {
                return json_encode("error_during_processing_file");
            }
            $nameFile = $file->getClientOriginalName();
            $tmp = explode(".", $nameFile);
            $ext = end($tmp);
            $path_image = "restaurants" . $restaurant->id . "." . $ext;
            $restaurant->name = $name;
            $fileDestination = "../public/images/restaurants/".$path_image;
            move_uploaded_file($file->getPathname(), $fileDestination);
            $restaurant->image = $path_image;
        }
        
        $restaurant->name = $name;
        $restaurant->address = $address;
        $restaurant->description = $description;
        $restaurant->save();
        return json_encode("update");
    }

    public function login()
    {
        $request = request();
        $username = $request->username;
        $password = $request->password;

        $row = Restaurant::where("username", $username)->first();
        if ($row == null) {
            return json_encode("error_login_username");
        }
        if (!password_verify($password, $row->password)) {
            return json_encode("error_login_password");
        }

        Session::put("res_username_session", $username);
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

        $row = Restaurant::where("username", $username)->first();
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

        $restaurant = new Restaurant;
        $restaurant->username = $username;
        $restaurant->password = $hash;
        $restaurant->email = $email;
        $restaurant->name = $name;
        $restaurant->address = $address;
        $restaurant->save();

        Session::put("res_username_session", $username);
        return json_encode("register");
    }
}
