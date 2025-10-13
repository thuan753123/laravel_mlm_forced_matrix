<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Node extends Model
{
    use HasFactory, NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'position',
        'depth',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'depth' => 'integer',
    ];

    /**
     * Get the user associated with this node.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent node.
     */
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    /**
     * Get child nodes.
     */
    public function children()
    {
        return $this->hasMany(Node::class, 'parent_id');
    }

    /**
     * Get all descendants.
     */
    public function descendants()
    {
        return $this->hasMany(Node::class, 'parent_id')->with('descendants');
    }

    /**
     * Get all ancestors.
     */
    public function ancestors()
    {
        return $this->belongsToMany(Node::class, 'nodes', 'id', 'parent_id')
            ->where('_lft', '<', $this->_lft)
            ->where('_rgt', '>', $this->_rgt)
            ->orderBy('_lft');
    }

    /**
     * Get siblings (nodes with same parent).
     */
    public function siblings()
    {
        return $this->hasMany(Node::class, 'parent_id')
            ->where('id', '!=', $this->id);
    }

    /**
     * Get the root node.
     */
    public function root()
    {
        return $this->ancestors()->whereNull('parent_id')->first() ?? $this;
    }

    /**
     * Check if this node is a leaf (has no children).
     */
    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    /**
     * Check if this node is the root.
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Get the level in the tree (0-based).
     */
    public function getLevel(): int
    {
        return $this->depth;
    }

    /**
     * Get the path from root to this node.
     */
    public function getPath(): array
    {
        return $this->ancestors()->pluck('id')->push($this->id)->toArray();
    }

    /**
     * Get the tree structure starting from this node.
     */
    public function getTree(int $maxDepth = null): array
    {
        $query = $this->descendants();
        
        if ($maxDepth !== null) {
            $query->where('depth', '<=', $this->depth + $maxDepth);
        }
        
        return $query->with('user')->get()->toTree();
    }
}