<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryController extends Controller
{
    protected $catRepo;

    public function __construct(CategoryRepository $category)
    {
        $this->catRepo = $category;
    }

    /**
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $categories = $this->catRepo->getAll();
        $categoryCollection = CategoryResource::collection($categories);
        return $categoryCollection;
    }

    /**
     * @param Request $request
     * @return CategoryResource
     */
    public function store(Request $request): CategoryResource
    {
        $newCategory = new Category($request->all());
        $createdCategory = $this->catRepo->insertLastChild($newCategory);
        return new CategoryResource($createdCategory);
    }

    /**
     * @param int $id
     * @return CategoryResource
     */
    public function show(int $id): CategoryResource
    {
        $category = $this->catRepo->find($id);
        return new CategoryResource($category);
    }

    /**
     * @param Request $request
     * @param Category $category
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * @param int $id
     * @return CategoryResource
     */
    public function destroy(int $id): CategoryResource
    {
        $deletedCategory = $this->catRepo->deleteCategory($id);
        return new CategoryResource($deletedCategory);
    }
}
