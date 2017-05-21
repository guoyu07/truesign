<?php
/**
 * User: iamsee
 * Date: 14/11/6
 * Time: PM2:31
 */

namespace Royal\Misc;


class TreeNode {
    /**
     * @var mixed
     */
    private $value;
    /**
     * parent
     *
     * @var TreeNode
     * @access private
     */
    private $parent;
    /**
     * @var array[TreeNode]
     */
    private $children = [];

    /**
     * @param mixed $value
     * @param array [TreeNode] $children
     */
    public function __construct($value = null, array $children = []) {
        $this->setValue($value);
        $this->setChildren($children);
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(TreeNode $child) {
        $child->setParent($this);
        $this->children[] = $child;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeChild(TreeNode $child) {
        foreach ($this->children as $key => $myChild) {
            if ($child == $myChild) {
                unset($this->children[$key]);
            }
        }
        $this->children = array_values($this->children);
        $child->setParent(null);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllChildren() {
        $this->setChildren([]);
        return $this;
    }

    /**
     * @return array[self]
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    public function setChildren(array $children) {
        $this->removeParentFromChildren();
        $this->children = [];
        foreach ($children as $child) {
            $this->addChild($child);
        }
        return $this;
    }

    public function removeParentFromChildren() {
        foreach ($this->children as $child) {
            $child->setParent(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(TreeNode $parent = null) {
        $this->parent = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getAncestors() {
        $parents = [];
        $node = $this;
        while ($parent = $node->getParent()) {
            array_unshift($parents, $parent);
            $node = $parent;
        }
        return $parents;
    }

    /**
     * {@inheritDoc}
     */
    public function getAncestorsAndSelf() {
        return array_merge($this->getAncestors(), [$this]);
    }

    /**
     * {@inheritdoc}
     */
    public function getNeighbors() {
        $neighbors = $this->getParent()->getChildren();
        $current = $this;
        // Uses array_values to reset indexes after filter.
        return array_values(
            array_filter(
                $neighbors,
                function ($item) use ($current) {
                    return $item != $current;
                }
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getNeighborsAndSelf() {
        return $this->getParent()->getChildren();
    }

    /**
     * {@inheritDoc}
     */
    public function isLeaf() {
        return count($this->children) === 0;
    }

    /**
     * @return bool
     */
    public function isRoot() {
        return $this->getParent() === null;
    }

    /**
     * {@inheritDoc}
     */
    public function isChild() {
        return $this->getParent() !== null;
    }

    /**
     * Find the root of the node
     *
     * @return TreeNode
     */
    public function root() {
        $node = $this;
        while ($parent = $node->getParent())
            $node = $parent;
        return $node;
    }

    static function treeToString(TreeNode &$root, $callable) {
        $str = '';
        static::printTree($root, $callable, $str);
        return $str;
    }

    static function printTree(TreeNode &$root, $callable, &$str) {
        static $spaces = array();
        array_push($spaces, "--");
        $str .= sprintf("|%s%s\n", implode('', $spaces), $callable($root->getValue()));
        foreach ($root->getChildren() as $child) {
            static::printTree($child, $callable, $str);
        }
        array_pop($spaces);
    }
} 