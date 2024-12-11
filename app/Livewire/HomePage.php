<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Ecommerce Site')]

class HomePage extends Component
{
    public function render()


    {
        $brands=Brand::where('is_active',1)->get();
        $categories =Category::where('is_active',1)->get();
        $products=Product::all();


        return view('livewire.home-page',[
            'brands'=> $brands,
            'categories'=>$categories,
            'products'=>$products,

        ]);
    }
}
