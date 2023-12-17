<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Nette\Utils\Image;

class CategoryController extends Controller
{
    //
    function getAll() {
        $list = Categories::all();
        return response()->json($list, 200, ['Charset' => 'utf-8']);
    }

    function create(Request $request) {
        $input = $request->all();
        $image = $request->file("image");
        $manager = new ImageManager(new Driver());
        $imageName=uniqid().".webp";
        $folderName = "upload";
        $folderPath = public_path($folderName);
        if (!file_exists($folderPath) && !is_dir($folderPath))
            mkdir($folderPath, 0777);
        $sizes = [50, 150, 300, 600, 1200];
        foreach ($sizes as $size) {
            $imageSave = $manager->read($image);
            $imageSave->scale(width: $size);
            $imageSave->toWebp()->save($folderPath."/".$imageName);
        }
        $input["image"] = $imageName;
        $category = Categories::create($input);
        return response()->json($category, 200,["Charset"=>"utf-8"]);
    }
}
