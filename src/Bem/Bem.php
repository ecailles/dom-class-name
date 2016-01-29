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
 */
class Bem
{
    protected $context = Contexts::NORMAL;

    protected $prefix;

    protected $prefixSeparator;

    protected $blocks = [];

    protected $elements = [];

    protected $elementSeparator;

    protected $modifiers = [];

    protected $modifierSeparator;

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

    public function __toString()
    {
        return $this->value();
    }

    public function prefix($name)
    {
        $this->prefix = $name;

        return $this;
    }

    public function prefixSeparator($separator)
    {
        $this->prefixSeparator = $separator;

        return $this;
    }

    public function block($names)
    {
        $this->context = Contexts::BLOCK;
        $names         = is_array($names) ? $names : [$names];
        $this->blocks  = array_unique(array_merge($this->blocks, $names));

        return $this;
    }

    public function blockModifier($names)
    {
        $names                            = is_array($names) ? $names : [$names];
        $this->modifiers[Contexts::BLOCK] = array_unique(array_merge($this->modifiers[Contexts::BLOCK], $names));

        return $this;
    }

    public function element($names)
    {
        $this->context  = Contexts::ELEMENT;
        $names          = is_array($names) ? $names : [$names];
        $this->elements = array_unique(array_merge($this->elements, $names));

        return $this;
    }

    public function elementSeparator($separator)
    {
        $this->elementSeparator = $separator;

        return $this;
    }

    public function elementModifier($names)
    {
        $names                              = is_array($names) ? $names : [$names];
        $this->modifiers[Contexts::ELEMENT] = array_unique(array_merge($this->modifiers[Contexts::ELEMENT], $names));

        return $this;
    }

    public function modifier($names)
    {
        $names                           = is_array($names) ? $names : [$names];
        $this->modifiers[$this->context] = array_unique(array_merge($this->modifiers[$this->context], $names));

        return $this;
    }

    public function modifierSeparator($separator)
    {
        $this->modifierSeparator = $separator;

        return $this;
    }

    public function classname($classes)
    {
        $classes       = is_array($classes) ? $classes : [$classes];
        $this->classes = array_unique(array_merge($this->classes, $classes));

        return $this;
    }

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

    public function value()
    {
        return implode(' ', $this->get());
    }

    protected function hasBlock()
    {
        return (count($this->blocks) > 0);
    }

    protected function getBlockModifiers()
    {
        return $this->modifiers[Contexts::BLOCK];
    }

    protected function hasBlockModifier()
    {
        return (count($this->getBlockModifiers()) > 0);
    }

    protected function hasElement()
    {
        return (count($this->elements) > 0);
    }

    protected function getElementModifiers()
    {
        return $this->modifiers[Contexts::ELEMENT];
    }

    protected function hasElementModifier()
    {
        return (count($this->getElementModifiers()) > 0);
    }

    protected function getContextualModifiers()
    {
        return $this->modifiers[Contexts::NORMAL];
    }

    protected function hasContextualModifier()
    {
        return (count($this->getContextualModifiers()) > 0);
    }

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

    protected function createBlockSet()
    {
        $prefix = ($this->prefix !== null && $this->prefix !== '') ? ($this->prefix . $this->prefixSeparator) : '';
        $set    = array_map(function ($blockName) use ($prefix) {
            return $prefix . $blockName;
        }, $this->blocks);

        return $set;
    }

    protected function createBlockModifierSet()
    {
        $set = array_map(function ($modifiers) {
            return $this->modifierSeparator . $modifiers;
        }, $this->getBlockModifiers());

        return $set;
    }

    protected function createElementSet()
    {
        $prefix = ($this->prefix !== null && $this->prefix !== '') ? ($this->prefix . $this->prefixSeparator) : '';
        $prefix = $this->hasBlock() ? $this->elementSeparator : $prefix;

        $set = array_map(function ($elementName) use ($prefix) {
            return $prefix . $elementName;
        }, $this->elements);

        return $set;
    }

    protected function createElementModifierSet()
    {
        $set = array_map(function ($modifiers) {
            return $this->modifierSeparator . $modifiers;
        }, $this->getElementModifiers());

        return $set;
    }

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
