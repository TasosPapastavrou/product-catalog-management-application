<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Tags;
use Illuminate\Support\Facades\DB;

class CatalogController extends Controller
{

    public function getProducts(Request $request){

        $filter = $request->get('filter');

        if($filter)
            $products = Products::where('category','=',$filter)->with('tags')->get();
        else
            $products = Products::with('tags')->all();    

        return $products;

    }


    public function storeProduct(Request $request){

        $code = $request->get('code');  
        $name = $request->get('name');

        $patternName = '/[a-zA-Z_-]/';
        $patternCode = '/[a-z_-]/';      

        if ( !preg_match($patternName, $request->name ) || preg_match('~[0-9]+~', $request->name) || preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',$request->name) ){
            $err = 'Only letters, space, underscore and dash are allowed for product name.';
            return [
                "status" =>"error",
               "message"=> $err
            ];
        }


        if ( !preg_match($patternCode, $request->code ) || preg_match('~[0-9]+~', $request->code) || preg_match("/\\s/", $request->code) || preg_match('/[A-Z]/', $request->code) ){
            $err = 'Only  lowercase letters, underscore and dash are allowed for product code.';
            return [
                "status" =>"error",
               "message"=> $err
            ];
        }


        if($code)
            $existRecord = Products::where('code','=',$code)->first();
        else
            return "you forgot to send product code";
      
        if($existRecord)
            return "product with this ". $code ." code exists";
            

        $newProduct = new Products();
        $newProduct->name = $request->name;
        $newProduct->code = $request->code;
        $newProduct->category = $request->category;
        $newProduct->price = $request->price;
        $newProduct->release_date = $request->release_date;
        $newProduct->save();

        $tags = $request->get('tags');

        if($tags){

            foreach($tags as $tag){
                $newTags = new Tags();
                $newTags->tag_name = $tag;
                $newTags->products_id = $newProduct->id;
                $newTags->save();
            }

        }

        $this->sendChanges($newProduct,"store");

        return $newProduct;
    }



    public function updateProduct(Request $request,$id){

        $updateProduct = Products::find($id);

        $patternName = '/[a-zA-Z_-]/';
        $patternCode = '/[a-z_-]/';      

        if($request->name)
            if ( !preg_match($patternName, $request->name ) || preg_match('~[0-9]+~', $request->name) || preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',$request->name) ){
                $err = 'Only letters, space, underscore and dash are allowed for product name.';
                return [
                    "status" =>"error",
                   "message"=> $err
                ];
            }

        if($request->code)
            if ( !preg_match($patternCode, $request->code ) || preg_match('~[0-9]+~', $request->code) || preg_match("/\\s/", $request->code) || preg_match('/[A-Z]/', $request->code) ){
                $err = 'Only  lowercase letters, underscore and dash are allowed for product code.';
                return [
                    "status" =>"error",
                   "message"=> $err
                ];
            }
        
        if($updateProduct){

            if($request->has('name'))
            $updateProduct->name = $request->name;
            
            if($request->code)
            $updateProduct->code = $request->code;
            
            if($request->category)
            $updateProduct->category = $request->category;
            
            if($request->price)
            $updateProduct->price = $request->price;

            if($request->release_date)
            $updateProduct->release_date = $request->release_date;

            $updateProduct->save();

            $tags = $request->get('tags');

            if($tags){

                foreach($updateProduct->tags as $oldTag){
                    Tags::where('id','=',$oldTag->id)->delete();        
                }

                foreach($tags as $tag){
                    $newTags = new Tags();
                    $newTags->tag_name = $tag;
                    $newTags->products_id = $updateProduct->id;
                    $newTags->save();
                }

            }

            $this->sendChanges($updateProduct,"update");

            return $updateProduct;

        }else{
            return "product with ". $id ." id not exists";
        }
    }



    public function getTags(){
         
        $tags = Tags::all();    
        $uniqueTags = collect();
       
       foreach($tags as $tag){
            $uniqueTags->push($tag->tag_name); 
       }

       $uniqueTags  = collect($uniqueTags)->unique();

       return $uniqueTags; 
    }




    public function sendChanges($data,$type){

        $url = 'https://webhook.site/45f34b1e-df0b-414b-aaef-43de17c54b70';
         
        $data_string = json_encode($data);
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array($type." product"=>$data_string));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        curl_close($ch);

    }



}
