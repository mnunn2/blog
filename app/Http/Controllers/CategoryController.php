<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    public function index()//: ResourceCollection
    {
        $categories = $this->category->getAll();
        $categoryCollection = CategoryResource::collection($categories);
        return $categoryCollection;
    }

    public function store(Request $request): CategoryResource
    {
        $newCategory = new Category($request->all());
        $createdCategory = $this->category->insertLastChild($newCategory);
        return new CategoryResource($createdCategory);
    }

    public function show(int $id): CategoryResource
    {
        $category = $this->category->find($id);
        return new CategoryResource($category);
    }

    public function update(Request $request, Category $category)
    {
        //
    }

    public function destroy(int $id): ResourceCollection
    {

    }
}
