<?php

namespace App\Repositories;

use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{

    public function getAll()
    {
        return Category::paginate();
    }

    public function find($id)
    {
        return Category::findOrFail($id);
    }

    public function insertLastChild(category $newCat): Category
    {
        $parent = $this->find($newCat->parentId);
        $newCat->lft = $parent->rgt;
        $newCat->rgt = ($parent->rgt + 1);
        $newCat->rootId = ($parent->rootId);
        $newCat->depth = ($parent->depth + 1);

        DB::transaction(function () use ($parent, $newCat) {

            $this->shiftNodes($parent->rgt, 2, $parent->rootId);
            $newCat->save();

        }, 3);

        return $newCat;
    }

    public function deleteCategory(int $id): Category
    {
        $cat = $this->find($id);

        DB::transaction(function () use ($cat) {

            $cat->delete();
            $this->shiftNodes($cat->lft, ($cat->lft - $cat->rgt - 1), $cat->rootId);

        }, 3);
        return $cat;
    }

    protected function shiftNodes(int $lowest, int $delta, int $root): void
    {
        DB::update("update categories set lft = (lft + {$delta}) where rootId = ? and lft >= ?", [$root, $lowest]);
        DB::update("update categories set rgt = (rgt + {$delta}) where rootId = ? and rgt >= ?", [$root, $lowest]);
    }

}
