<?php
/**
 * Bem class name builder.
 *
 * @author Whizark <contact@whizark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2015 Whizark.
 * @license MIT
 */
namespace Ecailles\DomClassName\Bem;

use Nette\Http\Context;

/**
 * Class Bem
 *
 * @package Ecailles\DomClassName\Bem
 *
 * @method Bem class() An alias of `classname()`.
 * @method Bem clone() Clones the current instance of Bem.
 */
class Bem
{
    /**
     * @var string The current context.
     */
    protected $context = Contexts::NORMAL;

    /**
     * @var string|null The prefix.
     */
    protected $prefix;

    /**
     * @var string The prefix separator.
     */
    protected $prefixSeparator;

    /**
     * @var string[] The Block names.
     */
    protected $blocks = [];

    /**
     * @var string[] The Element names.
     */
    protected $elements = [];

    /**
     * @var string The Element separator.
     */
    protected $elementSeparator;

    /**
     * @var string[] The contexts and Modifier names.
     */
    protected $modifiers = [];

    /**
     * @var string The Modifier separator.
     */
    protected $modifierSeparator;

    /**
     * @var string The plain class names.
     */
    protected $classes = [];

    /**
     * Bem constructor.
     *
     * @param string $prefix The prefix.
     * @param string $prefixSeparator The prefix separator.
     * @param string $elementSeparator The Element separator.
     * @param string $modifierSeparator The Modifier separator.
     */
    public function __construct(
        $prefix = null,
        $prefixSeparator = null,
        $elementSeparator = null,
        $modifierSeparator = null
    ) {
        $this->prefix            = $prefix;
        $this->prefixSeparator   = ($prefixSeparator !== null) ? $prefixSeparator : '-';
        $this->elementSeparator  = ($elementSeparator !== null) ? $elementSeparator : '__';
        $this->modifierSeparator = ($modifierSeparator !== null) ? $modifierSeparator : '--';
        $this->modifiers         = [
            Contexts::NORMAL  => [],
            Contexts::BLOCK   => [],
            Contexts::ELEMENT => [],
        ];
    }

    /**
     * Returns the string representation of the instance.
     *
     * @return string The string representation.
     */
    public function __toString()
    {
        return $this->value();
    }

    /**
     * The magic method that is triggered when invoking an inaccessible method in an object context.
     *
     * @param string $name The name of the method being called.
     * @param mixed[] $arguments The arguments of the method being called.
     *
     * @return Bem|void The current instance of Bem when the method name is `class`.
     *                  A cloned instance of Bem when the method name is `clone`.
     */
    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'class':
                return call_user_func_array([$this, 'classname'], $arguments);

                break;

            case 'clone':
                return clone $this;

                break;

            default:
                break;
        }
    }

    /**
     * Changes the prefix.
     *
     * @param string $name The prefix to change.
     *
     * @return Bem The current instance of Bem.
     */
    public function prefix($name)
    {
        $this->prefix = $name;

        return $this;
    }

    /**
     * Changes the prefix separator.
     *
     * @param string $separator The prefix separator to change.
     *
     * @return Bem The current instance of Bem.
     */
    public function prefixSeparator($separator)
    {
        $this->prefixSeparator = $separator;

        return $this;
    }

    /**
     * Adds Block name(s).
     *
     * @param string|string[] $names The name(s) to add.
     *
     * @return Bem The current instance of Bem.
     */
    public function block($names)
    {
        $this->context = Contexts::BLOCK;
        $names         = is_array($names) ? $names : [$names];
        $this->blocks  = array_unique(array_merge($this->blocks, $names));

        return $this;
    }

    /**
     * Adds Block Modifier name(s).
     *
     * @param string|string[] $names The Block Modifier name(s) to add.
     *
     * @return Bem The current instance of Bem.
     */
    public function blockModifier($names)
    {
        $names                            = is_array($names) ? $names : [$names];
        $this->modifiers[Contexts::BLOCK] = array_unique(array_merge($this->modifiers[Contexts::BLOCK], $names));

        return $this;
    }

    /**
     * Adds Element name(s).
     *
     * @param string|string[] $names The Element name(s) to add.
     *
     * @return Bem The current instance of Bem.
     */
    public function element($names)
    {
        $this->context  = Contexts::ELEMENT;
        $names          = is_array($names) ? $names : [$names];
        $this->elements = array_unique(array_merge($this->elements, $names));

        return $this;
    }

    /**
     * Changes the Element separator.
     *
     * @param string $separator The Element separator to change.
     *
     * @return Bem The current instance of Bem.
     */
    public function elementSeparator($separator)
    {
        $this->elementSeparator = $separator;

        return $this;
    }

    /**
     * Adds Element Modifier name(s).
     *
     * @param string|string[] $names The Element Modifier name(s) to add.
     *
     * @return Bem The current instance of Bem.
     */
    public function elementModifier($names)
    {
        $names                              = is_array($names) ? $names : [$names];
        $this->modifiers[Contexts::ELEMENT] = array_unique(array_merge($this->modifiers[Contexts::ELEMENT], $names));

        return $this;
    }

    /**
     * Adds contextual Modifier name(s).
     *
     * @param string|string[] $names The contextual Modifier name(s) to add.
     *
     * @return Bem The current instance of Bem.
     */
    public function modifier($names)
    {
        $names                           = is_array($names) ? $names : [$names];
        $this->modifiers[$this->context] = array_unique(array_merge($this->modifiers[$this->context], $names));

        return $this;
    }

    /**
     * Changes the Modifier separator.
     *
     * @param string $separator The Modifier separator to change.
     *
     * @return Bem The current instance of Bem.
     */
    public function modifierSeparator($separator)
    {
        $this->modifierSeparator = $separator;

        return $this;
    }

    /**
     * Adds plain class name(s).
     *
     * @param string|string[] $classes The class name(s) to add.
     *
     * @return Bem The current instance of Bem.
     */
    public function classname($classes)
    {
        $classes       = is_array($classes) ? $classes : [$classes];
        $this->classes = array_unique(array_merge($this->classes, $classes));

        return $this;
    }

    /**
     * Builds and gets the class name(s) as an array.
     *
     * @return string[] An array of the class name(s).
     */
    public function get()
    {
        $this->mergeContextualModifiers();

        $classes = [];
        $sets    = $this->createSets();

        if (count($sets) > 0) {
            $product = $this->createCartesianProduct($sets);
            $classes = array_map(function ($value) {
                return implode('', $value);
            }, $product);
        }

        $classes = array_merge($classes, $this->classes);

        return array_unique($classes);
    }

    /**
     * Builds and gets the class name(s) as a string.
     *
     * @return string The class name(s) string.
     */
    public function value()
    {
        return implode(' ', $this->get());
    }

    /**
     * Returns whether Block name(s) has/have been added.
     *
     * @return bool True if Block name(s) has/have been added, false otherwise.
     */
    protected function hasBlock()
    {
        return (count($this->blocks) > 0);
    }

    /**
     * Gets the Block Modifier name(s) that has/have been added.
     *
     * @return string[] The Block Modifier name(s).
     */
    protected function getBlockModifiers()
    {
        return $this->modifiers[Contexts::BLOCK];
    }

    /**
     * Returns whether Block Modifier name(s) has/have been added.
     *
     * @return bool True if Block Modifier name(s) has/have been added, false otherwise.
     */
    protected function hasBlockModifier()
    {
        return (count($this->getBlockModifiers()) > 0);
    }

    /**
     * Returns whether Element name(s) has/have been added.
     *
     * @return bool True if Element name(s) has/have been added, false otherwise.
     */
    protected function hasElement()
    {
        return (count($this->elements) > 0);
    }

    /**
     * Gets the Block Modifier name(s) that has/have been added.
     *
     * @return string[] The Block Modifier name(s).
     */
    protected function getElementModifiers()
    {
        return $this->modifiers[Contexts::ELEMENT];
    }

    /**
     * Returns whether Element Modifier name(s) has/have been added.
     *
     * @return bool True if Element Modifier name(s) has/have been added, false otherwise.
     */
    protected function hasElementModifier()
    {
        return (count($this->getElementModifiers()) > 0);
    }

    /**
     * Gets the contextual Modifier name(s).
     *
     * @return string[] The contextual Modifier name(s).
     */
    protected function getContextualModifiers()
    {
        return $this->modifiers[Contexts::NORMAL];
    }

    /**
     * Returns whether contextual Modifier name(s) has/have been added.
     *
     * @return bool True if contextual Modifier name(s) has/have been added, false otherwise.
     */
    protected function hasContextualModifier()
    {
        return (count($this->getContextualModifiers()) > 0);
    }

    /**
     * Merges contextual Modifier name(s) with Block/Element Modifier name(s) based on the current context.
     */
    protected function mergeContextualModifiers()
    {
        if ($this->context === Contexts::NORMAL || !$this->hasContextualModifier()) {
            return;
        }

        if ($this->context === Contexts::BLOCK) {
            $this->blockModifier($this->getContextualModifiers());
        } elseif ($this->context === Contexts::ELEMENT) {
            $this->elementModifier($this->getContextualModifiers());
        }

        $this->modifiers[Contexts::NORMAL] = [];
    }

    /**
     * Creates a set of the Block name(s).
     *
     * @return string[] The created set of the Block name(s).
     */
    protected function createBlockSet()
    {
        $prefix = ($this->prefix !== null && $this->prefix !== '') ? ($this->prefix . $this->prefixSeparator) : '';
        $set    = array_map(function ($blockName) use ($prefix) {
            return $prefix . $blockName;
        }, $this->blocks);

        return $set;
    }

    /**
     * Creates a set of the Block Modifier name(s).
     *
     * @return string[] The created set of the Block Modifier name(s).
     */
    protected function createBlockModifierSet()
    {
        $set = array_map(function ($modifiers) {
            return $this->modifierSeparator . $modifiers;
        }, $this->getBlockModifiers());

        return $set;
    }

    /**
     * Creates a set of the Element name(s).
     *
     * @return string[] The created set of the Element name(s).
     */
    protected function createElementSet()
    {
        $prefix = ($this->prefix !== null && $this->prefix !== '') ? ($this->prefix . $this->prefixSeparator) : '';
        $prefix = $this->hasBlock() ? $this->elementSeparator : $prefix;

        $set = array_map(function ($elementName) use ($prefix) {
            return $prefix . $elementName;
        }, $this->elements);

        return $set;
    }

    /**
     * Creates a set of the Element Modifier name(s).
     *
     * @return string[] The created set of the Element Modifier name(s).
     */
    protected function createElementModifierSet()
    {
        $set = array_map(function ($modifiers) {
            return $this->modifierSeparator . $modifiers;
        }, $this->getElementModifiers());

        return $set;
    }

    /**
     * Creates sets of the Block, Element, Modifier name(s) based on the state.
     *
     * @return string[] The sets of the items.
     */
    protected function createSets()
    {
        $sets = [];

        if ($this->hasBlock()) {
            $sets[] = $this->createBlockSet();

            if ($this->hasBlockModifier()) {
                $sets[] = $this->createBlockModifierSet();
            }
        }

        if (!$this->hasElement()) {
            return $sets;
        }

        $sets[] = $this->createElementSet();

        if ($this->hasElementModifier()) {
            $sets[] = $this->createElementModifierSet();
        }

        return $sets;
    }

    /**
     * Creates Cartesian product with given sets of name(s).
     *
     * @param array[] $sets The sets of the name(s).
     *
     * @return array[] The created Cartesian product.
     */
    protected function createCartesianProduct(array $sets = [])
    {
        if (count($sets) === 0) {
            return [[]];
        }

        $root     = array_shift($sets);
        $subPairs = $this->createCartesianProduct($sets);
        $product  = [];

        foreach ($root as $rootValue) {
            foreach ($subPairs as $subPairValue) {
                array_unshift($subPairValue, $rootValue);

                $product[] = $subPairValue;
            }
        }

        return $product;
    }
}
