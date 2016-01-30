<?php
/**
 * Bem spec.
 *
 * @author Whizark <contact@whizark.com>
 * @see http://whizark.com
 * @copyright Copyright (C) 2016 Whizark.
 * @license MIT
 */
namespace Specs\Ecailles\DomClassName\Bem;

use Ecailles\DomClassName\Bem\Bem;

describe('Bem', function () {
    beforeEach(function () {
        $this->bem = new Bem();
    });

    describe('->prefix()', function () {
        given('subject', function () {
            return $this->bem->prefix('prefix');
        });

        it('returns self', function () {
            expect($this->subject)->toBeAnInstanceOf($this->bem);
        });

        context('when any Blocks have been set', function () {
            it('adds the prefix to the Block(s)', function () {
                $actual = $this->subject->block('block')->get();

                expect($actual)->toBe(['prefix-block']);
            });
        });

        context('when only Element(s) has/have been set', function () {
            it('adds the prefix to the Element(s)', function () {
                $actual = $this->subject->element('element')->get();

                expect($actual)->toBe(['prefix-element']);
            });
        });
    });

    describe('->prefixSeparator()', function () {
        given('subject', function () {
            return $this->bem->prefixSeparator('_');
        });

        it('returns self', function () {
            expect($this->subject)->toBeAnInstanceOf($this->bem);
        });

        it('changes the prefix separator', function () {
            $actual = $this->subject->prefix('prefix')->block('block')->get();

            expect($actual)->toBe(['prefix_block']);
        });
    });

    describe('->block()', function () {
        it('returns self', function () {
            $subject = $this->bem->block('block');

            expect($subject)->toBeAnInstanceOf($this->bem);
        });

        context('when a Block has been set', function () {
            given('subject', function () {
                return $this->bem->block('block');
            });

            it('adds the Block', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe(['block']);
            });
        });

        context('when multiple Blocks have been set', function () {
            given('subject', function () {
                return $this->bem->block('block1')->block(['block2', 'block3']);
            });

            it('adds the Blocks', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe(['block1', 'block2', 'block3']);
            });
        });
    });

    describe('->blockModifier()', function () {
        it('returns self', function () {
            $subject = $this->bem->blockModifier('modifier');

            expect($subject)->toBeAnInstanceOf($this->bem);
        });

        context('when any Blocks have not been set', function () {
            given('subject', function () {
                return $this->bem->blockModifier('modifier');
            });

            it('does not nothing', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe([]);
            });
        });

        context('when a Modifier is given', function () {
            given('subject', function () {
                return $this->bem->blockModifier('modifier');
            });

            it('adds the Modifier to the Block(s)', function () {
                $actual = $this->subject->block('block')->get();

                expect($actual)->toBe(['block--modifier']);
            });
        });

        context('when multiple Modifiers are given', function () {
            given('subject', function () {
                return $this->bem->blockModifier('modifier1')->blockModifier(['modifier2', 'modifier3']);
            });

            it('adds the Modifiers to the Block(s)', function () {
                $actual = $this->subject->block('block')->get();

                expect($actual)->toBe(['block--modifier1', 'block--modifier2', 'block--modifier3']);
            });
        });
    });

    describe('->element()', function () {
        it('returns self', function () {
            $subject = $this->bem->element('element');

            expect($subject)->toBeAnInstanceOf($this->bem);
        });

        context('when an Element has been set', function () {
            given('subject', function () {
                return $this->bem->element('element');
            });

            it('adds the Element', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe(['element']);
            });
        });

        context('when Elements have been set', function () {
            given('subject', function () {
                return $this->bem->element('element1')->element(['element2', 'element3']);
            });

            it('adds the Elements', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe(['element1', 'element2', 'element3']);
            });
        });
    });

    describe('->elementSeparator()', function () {
        given('subject', function () {
            return $this->bem->elementSeparator('_');
        });

        it('returns self', function () {
            expect($this->subject)->toBeAnInstanceOf($this->bem);
        });

        it('changes the Element separator', function () {
            $actual = $this->subject->block('block')->element('element')->get();

            expect($actual)->toBe(['block_element']);
        });
    });

    describe('->elementModifier()', function () {
        it('returns self', function () {
            $subject = $this->bem->elementModifier('modifier');

            expect($subject)->toBeAnInstanceOf($this->bem);
        });

        context('when any Elements have not been set', function () {
            given('subject', function () {
                return $this->bem->elementModifier('modifier');
            });

            it('does not nothing', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe([]);
            });
        });

        context('when a Modifier is given', function () {
            given('subject', function () {
                return $this->bem->elementModifier('modifier');
            });

            it('adds the Modifier to Element(s)', function () {
                $actual = $this->subject->element('element')->get();

                expect($actual)->toBe(['element--modifier']);
            });
        });

        context('when multiple Modifiers are given', function () {
            given('subject', function () {
                return $this->bem->elementModifier('modifier1')->elementModifier(['modifier2', 'modifier3']);
            });

            it('adds the Modifiers to Element(s)', function () {
                $actual = $this->subject->element('element')->get();

                expect($actual)->toBe(['element--modifier1', 'element--modifier2', 'element--modifier3']);
            });
        });
    });

    describe('->modifier()', function () {
        it('accepts a Modifier', function () {
            $this->bem->modifier('modifier');
        });

        it('accepts multiple Modifiers at once', function () {
            $this->bem->modifier(['modifier1', 'modifier2']);
        });

        it('returns self', function () {
            $subject = $this->bem->modifier('modifier');

            expect($subject)->toBeAnInstanceOf($this->bem);
        });

        context('when the current context is normal context', function () {
            context('when only Block(s) has/have been set', function () {
                given('subject', function () {
                    return $this->bem->modifier('modifier')->block('block');
                });

                it('becomes Block Modifier(s)', function () {
                    $actual = $this->subject->get();

                    expect($actual)->toBe(['block--modifier']);
                });
            });

            context('when Element(s) has/have been set', function () {
                given('subject', function () {
                    return $this->bem->modifier('modifier')->block('block')->element('element');
                });

                it('becomes Element Modifier(s)', function () {
                    $actual = $this->subject->get();

                    expect($actual)->toBe(['block__element--modifier']);
                });
            });
        });

        context('when the current context is Block context', function () {
            given('subject', function () {
                return $this->bem->block('block')->modifier('modifier1')
                    ->modifier(['modifier2', 'modifier3'])
                    ->element('element');
            });

            it('becomes Block Modifier(s)', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe([
                    'block--modifier1__element',
                    'block--modifier2__element',
                    'block--modifier3__element',
                ]);
            });
        });

        context('when the current context is Element context', function () {
            given('subject', function () {
                return $this->bem->block('block')
                    ->element('element')->modifier('modifier1')
                    ->modifier(['modifier2', 'modifier3']);
            });

            it('becomes Block Modifier(s)', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe([
                    'block__element--modifier1',
                    'block__element--modifier2',
                    'block__element--modifier3',
                ]);
            });
        });

        it('merges the Modifier(s) with Block/Element Modifier(s)', function () {
            /** @var Bem $subject */
            $subject = $this->bem->block('block')->modifier('block-modifier1')
                ->element('element')->modifier('element-modifier1');

            $actual = $subject->blockModifier('block-modifier2')
                ->elementModifier('element-modifier2')
                ->get();

            expect($actual)->toBe([
                'block--block-modifier1__element--element-modifier1',
                'block--block-modifier1__element--element-modifier2',
                'block--block-modifier2__element--element-modifier1',
                'block--block-modifier2__element--element-modifier2',
            ]);
        });
    });

    describe('->modifierSeparator()', function () {
        given('subject', function () {
            return $this->bem->modifierSeparator('_');
        });

        it('returns self', function () {
            expect($this->subject)->toBeAnInstanceOf($this->bem);
        });

        it('changes the Modifier separator', function () {
            $actual = $this->subject->block('block')->blockModifier('block-modifier')
                ->element('element')->elementModifier('element-modifier')
                ->get();

            expect($actual)->toBe(['block_block-modifier__element_element-modifier']);
        });
    });

    describe('->classname()', function () {
        it('returns self', function () {
            $subject = $this->bem->classname('class');

            expect($subject)->toBeAnInstanceOf($this->bem);
        });

        context('when a class name is given', function () {
            given('subject', function () {
                return $this->bem->classname('class');
            });

            it('adds the class name', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe(['class']);
            });
        });

        context('when multiple class names are given', function () {
            given('subject', function () {
                return $this->bem->classname(['class1', 'class2']);
            });

            it('adds the class names', function () {
                $actual = $this->subject->get();

                expect($actual)->toBe(['class1', 'class2']);
            });
        });
    });

    describe('->class()', function () {
        context('when the method is invoked', function () {
            it('returns the same value as classname()', function () {
                $subject   = $this->bem->class('class');
                $classname = $this->bem->classname('class');

                expect($subject)->toBe($classname);
            });
        });

        context('when a class name is given', function () {
            it('adds the same class name as classname()', function () {
                $actual   = $this->bem->class('class')->get();
                $expected = $this->bem->classname('class')->get();

                expect($actual)->toBe($expected);
            });
        });

        context('when multiple class names are given', function () {
            it('adds the same class names as classname()', function () {
                $actual   = $this->bem->class(['class1', 'class2'])->get();
                $expected = $this->bem->classname(['class1', 'class2'])->get();

                expect($actual)->toBe($expected);
            });
        });
    });

    describe('->get()', function () {
        context('when any Blocks, Elements and classes have not been set', function () {
            it('returns an empty string', function () {
                $subject = $this->bem->value();

                expect($subject)->toBe('');
            });
        });

        context('when Block(s), Element(s), Modifier(s) or class name(s) have been set', function () {
            it('returns the string representation of the class name(s)', function () {
                $subject = $this->bem
                    ->block('block')
                    ->blockModifier('block-modifier')
                    ->element('element')
                    ->elementModifier('element-modifier')
                    ->classname('class')
                    ->value();

                expect($subject)->toBe('block--block-modifier__element--element-modifier class');
            });
        });
    });

    describe('->clone()', function () {
        beforeEach(function () {
            $this->bem = new Bem();
        });

        it('returns a clone', function () {
            $subject = $this->bem->clone();

            expect($subject)->toBeAnInstanceOf($this->bem)->not->toBe($this->bem);
        });
    });

    describe('->__toString()', function () {
        it('returns the same string as value()', function () {
            $bem = $this->bem
                ->block('block')
                ->blockModifier('block-modifier')
                ->element('element')
                ->elementModifier('element-modifier')
                ->classname('class');

            $actual   = (string) $bem;
            $expected = $bem->value();

            expect($actual)->toBe($expected);
        });
    });

    describe('->get()', function () {
        context('when any Blocks, Elements and classes have not been set', function () {
            it('returns an empty array', function () {
                $subject = $this->bem->get();

                expect($subject)->toBe([]);
            });
        });

        context('when Bem is constructed with the prefix and the separators', function () {
            beforeEach(function () {
                $this->bem = new Bem('prefix', '-', '--', '---');
            });

            it('returns the class name(s) with the prefix and the separators', function () {
                $subject = $this->bem->block('block')->blockModifier('block-modifier')
                    ->element('element')->elementModifier('element-modifier')
                    ->get();

                expect($subject)->toBe(['prefix-block---block-modifier--element---element-modifier']);
            });
        });

        context('when Block(s) and Modifier(s) have been set', function () {
            it('returns the class names of the combinations', function () {
                $subject = $this->bem->block('block1')
                    ->block(['block2', 'block3'])
                    ->blockModifier('modifier1')
                    ->blockModifier(['modifier2', 'modifier3'])
                    ->get();

                expect($subject)->toBe([
                    'block1--modifier1',
                    'block1--modifier2',
                    'block1--modifier3',
                    'block2--modifier1',
                    'block2--modifier2',
                    'block2--modifier3',
                    'block3--modifier1',
                    'block3--modifier2',
                    'block3--modifier3',
                ]);
            });
        });

        context('when Element(s) and Modifier(s) have been set', function () {
            it('returns the class names of the combinations', function () {
                $subject = $this->bem->element('element1')
                    ->element(['element2', 'element3'])
                    ->elementModifier('modifier1')
                    ->elementModifier(['modifier2', 'modifier3'])
                    ->get();

                expect($subject)->toBe([
                    'element1--modifier1',
                    'element1--modifier2',
                    'element1--modifier3',
                    'element2--modifier1',
                    'element2--modifier2',
                    'element2--modifier3',
                    'element3--modifier1',
                    'element3--modifier2',
                    'element3--modifier3',
                ]);
            });
        });

        context('when Block(s), Element(s), Modifier(s) and class name(s) have been set', function () {
            it('returns the class names of the combinations', function () {
                $subject = $this->bem
                    ->block('block1')
                    ->block(['block2', 'block3'])
                    ->blockModifier('block-modifier1')
                    ->blockModifier(['block-modifier2', 'block-modifier3'])
                    ->element('element1')
                    ->element(['element2', 'element3'])
                    ->elementModifier('element-modifier1')
                    ->elementModifier(['element-modifier2', 'element-modifier3'])
                    ->classname('class1')
                    ->classname(['class2', 'class3'])
                    ->get();

                expect($subject)->toBe([
                    'block1--block-modifier1__element1--element-modifier1',
                    'block1--block-modifier1__element1--element-modifier2',
                    'block1--block-modifier1__element1--element-modifier3',
                    'block1--block-modifier1__element2--element-modifier1',
                    'block1--block-modifier1__element2--element-modifier2',
                    'block1--block-modifier1__element2--element-modifier3',
                    'block1--block-modifier1__element3--element-modifier1',
                    'block1--block-modifier1__element3--element-modifier2',
                    'block1--block-modifier1__element3--element-modifier3',
                    'block1--block-modifier2__element1--element-modifier1',
                    'block1--block-modifier2__element1--element-modifier2',
                    'block1--block-modifier2__element1--element-modifier3',
                    'block1--block-modifier2__element2--element-modifier1',
                    'block1--block-modifier2__element2--element-modifier2',
                    'block1--block-modifier2__element2--element-modifier3',
                    'block1--block-modifier2__element3--element-modifier1',
                    'block1--block-modifier2__element3--element-modifier2',
                    'block1--block-modifier2__element3--element-modifier3',
                    'block1--block-modifier3__element1--element-modifier1',
                    'block1--block-modifier3__element1--element-modifier2',
                    'block1--block-modifier3__element1--element-modifier3',
                    'block1--block-modifier3__element2--element-modifier1',
                    'block1--block-modifier3__element2--element-modifier2',
                    'block1--block-modifier3__element2--element-modifier3',
                    'block1--block-modifier3__element3--element-modifier1',
                    'block1--block-modifier3__element3--element-modifier2',
                    'block1--block-modifier3__element3--element-modifier3',
                    'block2--block-modifier1__element1--element-modifier1',
                    'block2--block-modifier1__element1--element-modifier2',
                    'block2--block-modifier1__element1--element-modifier3',
                    'block2--block-modifier1__element2--element-modifier1',
                    'block2--block-modifier1__element2--element-modifier2',
                    'block2--block-modifier1__element2--element-modifier3',
                    'block2--block-modifier1__element3--element-modifier1',
                    'block2--block-modifier1__element3--element-modifier2',
                    'block2--block-modifier1__element3--element-modifier3',
                    'block2--block-modifier2__element1--element-modifier1',
                    'block2--block-modifier2__element1--element-modifier2',
                    'block2--block-modifier2__element1--element-modifier3',
                    'block2--block-modifier2__element2--element-modifier1',
                    'block2--block-modifier2__element2--element-modifier2',
                    'block2--block-modifier2__element2--element-modifier3',
                    'block2--block-modifier2__element3--element-modifier1',
                    'block2--block-modifier2__element3--element-modifier2',
                    'block2--block-modifier2__element3--element-modifier3',
                    'block2--block-modifier3__element1--element-modifier1',
                    'block2--block-modifier3__element1--element-modifier2',
                    'block2--block-modifier3__element1--element-modifier3',
                    'block2--block-modifier3__element2--element-modifier1',
                    'block2--block-modifier3__element2--element-modifier2',
                    'block2--block-modifier3__element2--element-modifier3',
                    'block2--block-modifier3__element3--element-modifier1',
                    'block2--block-modifier3__element3--element-modifier2',
                    'block2--block-modifier3__element3--element-modifier3',
                    'block3--block-modifier1__element1--element-modifier1',
                    'block3--block-modifier1__element1--element-modifier2',
                    'block3--block-modifier1__element1--element-modifier3',
                    'block3--block-modifier1__element2--element-modifier1',
                    'block3--block-modifier1__element2--element-modifier2',
                    'block3--block-modifier1__element2--element-modifier3',
                    'block3--block-modifier1__element3--element-modifier1',
                    'block3--block-modifier1__element3--element-modifier2',
                    'block3--block-modifier1__element3--element-modifier3',
                    'block3--block-modifier2__element1--element-modifier1',
                    'block3--block-modifier2__element1--element-modifier2',
                    'block3--block-modifier2__element1--element-modifier3',
                    'block3--block-modifier2__element2--element-modifier1',
                    'block3--block-modifier2__element2--element-modifier2',
                    'block3--block-modifier2__element2--element-modifier3',
                    'block3--block-modifier2__element3--element-modifier1',
                    'block3--block-modifier2__element3--element-modifier2',
                    'block3--block-modifier2__element3--element-modifier3',
                    'block3--block-modifier3__element1--element-modifier1',
                    'block3--block-modifier3__element1--element-modifier2',
                    'block3--block-modifier3__element1--element-modifier3',
                    'block3--block-modifier3__element2--element-modifier1',
                    'block3--block-modifier3__element2--element-modifier2',
                    'block3--block-modifier3__element2--element-modifier3',
                    'block3--block-modifier3__element3--element-modifier1',
                    'block3--block-modifier3__element3--element-modifier2',
                    'block3--block-modifier3__element3--element-modifier3',
                    'class1',
                    'class2',
                    'class3',
                ]);
            });
        });
    });
});
