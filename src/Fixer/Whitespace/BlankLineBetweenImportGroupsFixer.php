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

namespace PhpCsFixer\Fixer\Whitespace;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

/**
 * @author Sander Verkuil <s.verkuil@pm.me>
 */
final class BlankLineBetweenImportGroupsFixer extends AbstractFixer implements WhitespacesAwareFixerInterface
{
    /**
     * @internal
     */
    private const IMPORT_TYPE_CLASS = 'class';

    /**
     * @internal
     */
    private const IMPORT_TYPE_CONST = 'const';

    /**
     * @internal
     */
    private const IMPORT_TYPE_FUNCTION = 'function';

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Putting blank lines between `use` statement groups.',
            [
                new CodeSample(
                    '<?php

use function AAC;
use const AAB;
use AAA;
'
                ),
                new CodeSample(
                    '<?php
use const AAAA;
use const BBB;
use Bar;
use AAC;
use Acme;
use function CCC\AA;
use function DDD;
'
                ),
                new CodeSample(
                    '<?php
use const BBB;
use const AAAA;
use Acme;
use AAC;
use Bar;
use function DDD;
use function CCC\AA;
'
                ),
                new CodeSample(
                    '<?php
use const AAAA;
use const BBB;
use Acme;
use function DDD;
use AAC;
use function CCC\AA;
use Bar;
'
                ),
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * Must run after OrderedImportsFixer.
     */
    public function getPriority(): int
    {
        return -40;
    }

    /**
     * {@inheritdoc}
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_USE);
    }

    /**
     * {@inheritdoc}
     */
    protected function applyFix(\SplFileInfo $file, Tokens $tokens): void
    {
        $tokensAnalyzer = new TokensAnalyzer($tokens);
        $namespacesImports = $tokensAnalyzer->getImportUseIndexes(true);

        if (0 === \count($namespacesImports)) {
            return;
        }

        foreach ($namespacesImports as $uses) {
            $this->fixUseBlock($uses, $tokens);
        }
    }

    private function fixUseBlock(array $uses, Tokens $tokens)
    {
        $lineEnding = $this->whitespacesConfig->getLineEnding();

        $previousType = null;

        for ($i = \count($uses) - 1; $i >= 0; --$i) {
            $index = $uses[$i];

            $startIndex = $tokens->getTokenNotOfKindsSibling($index + 1, 1, [T_WHITESPACE]);
            $endIndex = $tokens->getNextTokenOfKind($startIndex, [';', [T_CLOSE_TAG]]);

            if ($tokens[$startIndex]->isGivenKind(CT::T_CONST_IMPORT)) {
                $type = self::IMPORT_TYPE_CONST;
            } elseif ($tokens[$startIndex]->isGivenKind(CT::T_FUNCTION_IMPORT)) {
                $type = self::IMPORT_TYPE_FUNCTION;
            } else {
                $type = self::IMPORT_TYPE_CLASS;
            }

            if (null !== $previousType && $type !== $previousType) {
                $tokens->overrideRange($endIndex + 1, $endIndex + 1, [new Token([T_WHITESPACE, $lineEnding.$lineEnding])]);
            }

            $previousType = $type;
        }
    }
}
