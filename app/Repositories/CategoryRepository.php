<?php

namespace App\Repositories;

use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    /**
     * @var $cat
     */
    protected $cat;

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
        $this->cat = $newCat;
        $parent = $this->find($this->cat->parentId);
        $this->cat->lft = $parent->rgt;
        $this->cat->rgt = ($parent->rgt + 1);
        $this->cat->rootId = ($parent->rootId);
        $this->cat->depth = ($parent->depth + 1);

        DB::transaction(function () use ($parent) {

            $this->shiftNodes($parent->rgt, 2, $parent->rootId);
            $this->cat->save();

        }, 3);

        return $this->cat;
    }

    protected function shiftNodes(int $lowest, int $delta, int $root): void
    {
        DB::update("update categories set lft = (lft + {$delta}) where rootId = ? and lft >= ?", [$root, $lowest]);
        DB::update("update categories set rgt = (rgt + {$delta}) where rootId = ? and rgt >= ?", [$root, $lowest]);
    }

}
