<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Supplier;
use App\Models\Favorite;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ControllerProducts extends Controller
{
    public function returnView()
    {
        $res_username = Session::get("res_username_session");
        $sup_username = Session::get("sup_username_session");

        if ($res_username != null) {
            $user = Restaurant::where("username", $res_username)->first();
            return view('products')->with("user", $user)->with("existUserRes", true)->with("existUserSup", false);
        } elseif ($sup_username != null) {
            $user = Supplier::where("username", $sup_username)->first();
            return view('products')->with("user", $user)->with("existUserRes", false)->with("existUserSup", true);
        } else
            return view('products')->with("user", null)->with("existUserRes", false)->with("existUserSup", false);
    }

    public function logout()
    {
        Session::flush();
        return json_encode("logout");
    }

    public function addPreference()
    {
        $request = request();
        $id = $request->id;
        $name = $request->name;
        $cost = $request->price;

        $username = Session::get("res_username_session");
        $restaurant = Restaurant::where("username", $username)->first();

        if($restaurant->favorites()->where("favorite_id", $id)->first() != null) {
            return json_encode("preferenceExist");
        }

        $product = Favorite::where("id", $id)->first();
        if ($product == null) {
            $product = new Favorite;
            $product->id = $id;
            $product->name = $name;
            $product->cost = $cost;
            $product->save();
        }
        $restaurant->favorites()->attach($product->id);

        return json_encode("addPreference");
    }

    public function showPreference()
    {
        $username = Session::get("res_username_session");
        $restaurant = Restaurant::where("username", $username)->first();
        $favorites = $restaurant->favorites()->get();
        return $favorites;
    }

    public function removePreference()
    {
        $request = request();
        $id = $request->id;
        $username = Session::get("res_username_session");

        $restaurant = Restaurant::where("username", $username)->first();
        $restaurant->favorites()->detach($id);

        return json_encode("removePreference");
    }

    public function api1($type, $data = "")
    {
        $api_key = env("API_KEY_1");
        $api1URLingredient = "https://spoonacular.com/cdn/ingredients_500x500/";
        $newJson = array();

        if ($type == "products") {
            $url = "https://api.spoonacular.com/food/ingredients/search?apiKey=" . $api_key . "&query=" . $data;
            $json = $this->callAPI($url);
            for ($i = 0; $i < count($json["results"]); $i++) {
                $newJson[] = array("id" => $json["results"][$i]["id"], "name" => $json["results"][$i]["name"], "image" => $api1URLingredient . $json["results"][$i]["image"]);
            }
        } else if ($type == "informations") {
            $url = "https://api.spoonacular.com/food/ingredients/" . $data . "/information?amount=1&apiKey=" . $api_key;
            $newJson = $this->callAPI($url);
            $newJson["image"] = $api1URLingredient . $newJson["image"];
        }

        return json_encode($newJson);
    }

    public function api2()
    {
        $url = "https://foodish-api.herokuapp.com/api";
        return $this->callAPI($url);
    }

    function callAPI($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result, true);
    }
}
