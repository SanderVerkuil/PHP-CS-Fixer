<?php

declare(strict_types=1);

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PhpCsFixer\Tests\Fixer\Whitespace;

use PhpCsFixer\Tests\Test\AbstractFixerTestCase;

/**
 * @internal
 *
 * @covers \PhpCsFixer\Fixer\Whitespace\BlankLineBetweenImportGroupsFixer
 */
final class BlankLineBetweenImportGroupsFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider provideFixTypesOrderAndWhitespaceCases
     */
    public function testFixTypesOrderAndNewlines(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    public function provideFixTypesOrderAndWhitespaceCases(): array
    {
        return [
            [
                '<?php
use Aaa\Ccc;
use Foo\Zar\Baz;
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;
use some\b\{
    ClassF,
    ClassG
};
use Some\Cloz;
use Aaa\Bbb;

use const some\a\{ConstD};
use const some\a\{ConstA};
use const some\a\{ConstB, ConstC as CC};
use const some\b\{ConstE};

use function some\f\{fn_g, fn_h, fn_i};
use function some\c\{fn_f};
use function some\a\{fn_x};
use function some\b\{fn_c, fn_d, fn_e};
use function some\a\{fn_a, fn_b};
',
                '<?php
use Aaa\Ccc;
use Foo\Zar\Baz;
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;
use some\b\{
    ClassF,
    ClassG
};
use Some\Cloz;
use Aaa\Bbb;
use const some\a\{ConstD};
use const some\a\{ConstA};
use const some\a\{ConstB, ConstC as CC};
use const some\b\{ConstE};
use function some\f\{fn_g, fn_h, fn_i};
use function some\c\{fn_f};
use function some\a\{fn_x};
use function some\b\{fn_c, fn_d, fn_e};
use function some\a\{fn_a, fn_b};
',
            ],
            [
                '<?php
use Aaa\Ccc;
use Foo\Zar\Baz;

use function some\f\{fn_g, fn_h, fn_i};

use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;

use function some\c\{fn_f};

use some\b\{
    ClassF,
    ClassG
};

use const some\a\{ConstD};

use Some\Cloz;

use function some\a\{fn_x};

use const some\a\{ConstA};

use function some\b\{fn_c, fn_d, fn_e};

use const some\a\{ConstB, ConstC as CC};

use Aaa\Bbb;

use const some\b\{ConstE};

use function some\a\{fn_a, fn_b};
',
                '<?php
use Aaa\Ccc;
use Foo\Zar\Baz;
use function some\f\{fn_g, fn_h, fn_i};
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;
use function some\c\{fn_f};
use some\b\{
    ClassF,
    ClassG
};
use const some\a\{ConstD};
use Some\Cloz;
use function some\a\{fn_x};
use const some\a\{ConstA};
use function some\b\{fn_c, fn_d, fn_e};
use const some\a\{ConstB, ConstC as CC};
use Aaa\Bbb;
use const some\b\{ConstE};
use function some\a\{fn_a, fn_b};
',
            ],
            [
                '<?php
use Aaa\Ccc;
use Foo\Zar\Baz;
#use function some\f\{fn_g, fn_h, fn_i};
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;

use function some\c\{fn_f};

use some\b\{
    ClassF,
    ClassG
};

use const some\a\{ConstD};

use Some\Cloz;

use function some\a\{fn_x};

/** Import ConstA to do some nice magic */
use const some\a\{ConstA};

use function some\b\{fn_c, fn_d, fn_e};

use const some\a\{ConstB, ConstC as CC};

use Aaa\Bbb;

use const some\b\{ConstE};

use function some\a\{fn_a, fn_b};
',
                '<?php
use Aaa\Ccc;
use Foo\Zar\Baz;
#use function some\f\{fn_g, fn_h, fn_i};
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;
use function some\c\{fn_f};
use some\b\{
    ClassF,
    ClassG
};
use const some\a\{ConstD};
use Some\Cloz;
use function some\a\{fn_x};
/** Import ConstA to do some nice magic */
use const some\a\{ConstA};
use function some\b\{fn_c, fn_d, fn_e};
use const some\a\{ConstB, ConstC as CC};
use Aaa\Bbb;
use const some\b\{ConstE};
use function some\a\{fn_a, fn_b};
',
            ],
            [
                '<?php
/**
use Aaa\Ccc;
use Foo\Zar\Baz;
 */
use function some\f\{fn_g, fn_h, fn_i};

use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;

use function some\c\{fn_f};

use some\b\{
    ClassF,
    ClassG
};

use const some\a\{ConstD};

use Some\Cloz;

// Ignore the following content
// use function some\a\{fn_x};
// use const some\a\{ConstA};

use function some\b\{fn_c, fn_d, fn_e};

use const some\a\{ConstB, ConstC as CC};

use Aaa\Bbb;

use const some\b\{ConstE};

use function some\a\{fn_a, fn_b};
',
                '<?php
/**
use Aaa\Ccc;
use Foo\Zar\Baz;
 */
use function some\f\{fn_g, fn_h, fn_i};

use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;

use function some\c\{fn_f};

use some\b\{
    ClassF,
    ClassG
};

use const some\a\{ConstD};

use Some\Cloz;
// Ignore the following content
// use function some\a\{fn_x};
// use const some\a\{ConstA};

use function some\b\{fn_c, fn_d, fn_e};

use const some\a\{ConstB, ConstC as CC};

use Aaa\Bbb;

use const some\b\{ConstE};

use function some\a\{fn_a, fn_b};
',
            ],
            [
                '<?php
use Aaa\Ccc;

/*use Foo\Zar\Baz;
use function some\f\{fn_g, fn_h, fn_i};
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;
use function some\c\{fn_f};
use some\b\{
    ClassF,
    ClassG
};
use const some\a\{ConstD};
use Some\Cloz;
use function some\a\{fn_x};
use const some\a\{ConstA};
use function some\b\{fn_c, fn_d, fn_e};
use const some\a\{ConstB, ConstC as CC};
use Aaa\Bbb;
use const some\b\{ConstE};
*/
use function some\a\{fn_a, fn_b};
',
                '<?php
use Aaa\Ccc;
/*use Foo\Zar\Baz;
use function some\f\{fn_g, fn_h, fn_i};
use some\a\{ClassA};
use some\b\{ClassD, ClassB, ClassC as C};
use Bar\Biz\Boooz\Bum;
use function some\c\{fn_f};
use some\b\{
    ClassF,
    ClassG
};
use const some\a\{ConstD};
use Some\Cloz;
use function some\a\{fn_x};
use const some\a\{ConstA};
use function some\b\{fn_c, fn_d, fn_e};
use const some\a\{ConstB, ConstC as CC};
use Aaa\Bbb;
use const some\b\{ConstE};
*/
use function some\a\{fn_a, fn_b};
',
            ],
            [
                '<?php
use Aaa\Ccc;

use function some\a\{fn_a, fn_b};
use function some\b\{
    fn_c,
    fn_d
};
',
                '<?php
use Aaa\Ccc;



use function some\a\{fn_a, fn_b};
use function some\b\{
    fn_c,
    fn_d
};
',
            ],
            [
                '<?php
use Aaa\Ccc;

use function some\a\{fn_a, fn_b}; // Do this because of reasons
use function some\b\{
    fn_c,
    fn_d
};
',
                '<?php
use Aaa\Ccc;



use function some\a\{fn_a, fn_b}; // Do this because of reasons
use function some\b\{
    fn_c,
    fn_d
};
',
            ],
        ];
    }
}
