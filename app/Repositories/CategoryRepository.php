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

    public function insertBefore(category $newCat, int $beforeId): Category
    {
        $before = $this->find($beforeId);
        $newCat->lft = $before->lft;
        $newCat->rgt = ($before->rgt);
        $newCat->rootId = ($before->rootId);
        $newCat->depth = ($before->depth);
        $newCat->parentId = ($before->parentId);

        DB::transaction(function () use ($before, $newCat) {

            $this->shiftNodes(($before->lft), 2, $before->rootId);
            $newCat->save();

        }, 3);

        return $newCat;
    }

    public function insertAfter(category $newCat, int $afterId): Category
    {
        $after = $this->find($afterId);
        $newCat->lft = $after->rgt + 1;
        $newCat->rgt = ($after->rgt + 2);
        $newCat->rootId = ($after->rootId);
        $newCat->depth = ($after->depth);
        $newCat->parentId = ($after->parentId);

        DB::transaction(function () use ($after, $newCat) {

            $this->shiftNodes(($after->rgt + 1), 2, $after->rootId);
            $newCat->save();

        }, 3);

        return $newCat;
    }

    public function insertFirstChild(category $newCat): Category
    {
        $parent = $this->find($newCat->parentId);
        $newCat->lft = $parent->lft +1;
        $newCat->rgt = ($parent->lft + 2);
        $newCat->rootId = ($parent->rootId);
        $newCat->depth = ($parent->depth + 1);

        DB::transaction(function () use ($parent, $newCat) {

            $this->shiftNodes(($parent->lft + 1), 2, $parent->rootId);
            $newCat->save();

        }, 3);

        return $newCat;
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
