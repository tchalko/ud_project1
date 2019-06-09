<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;

class AdminMediasController extends Controller
{
    public function index(){

        $photos = Photo::all();
        return view('admin.media.index', compact('photos'));
    }

    public function create(){
        return view('admin.media.create');
    }

    public function store(Request $request){
        $file = $request->file('file');
        $name = time().$file->getClientOriginalName();
        $file->move('images', $name);
        Photo::create(['file'=>$name]);
    }

    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        unlink(public_path().$photo->file);
        $photo->delete();
        Session::flash('deleted_photo',"The photo $photo->file has been deleted");
    }

    public function deleteMedia(Request $request)
    {
//        if(isset($request->delete_single)){
//            $this->destroy($request->photo_id);
//        }
        if(isset($request->delete_all) && !empty($request->checkBoxArray)) {
            $photos = Photo::findOrFail($request->checkBoxArray);
            foreach ($photos as $photo) {
                unlink(public_path() . $photo->file);
                $output[] = $photo->file;
                $photo->delete();
            }
            $strOutput = implode(", ", $output);
            Session::flash('deleted_photo', "The photo(s) $strOutput have been deleted");
        }
        return redirect('/admin/media');
    }
}
