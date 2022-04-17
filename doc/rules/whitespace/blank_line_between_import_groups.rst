=========================================
Rule ``blank_line_between_import_groups``
=========================================

Putting blank lines between ``use`` statement groups.

Examples
--------

Example #1
~~~~~~~~~~

.. code-block:: diff

   --- Original
   +++ New
    <?php

    use function AAC;
   +
    use const AAB;
   +
    use AAA;

Example #2
~~~~~~~~~~

.. code-block:: diff

   --- Original
   +++ New
    <?php
    use const AAAA;
    use const BBB;
   +
    use Bar;
    use AAC;
    use Acme;
   +
    use function CCC\AA;
    use function DDD;

Example #3
~~~~~~~~~~

.. code-block:: diff

   --- Original
   +++ New
    <?php
    use const BBB;
    use const AAAA;
   +
    use Acme;
    use AAC;
    use Bar;
   +
    use function DDD;
    use function CCC\AA;

Example #4
~~~~~~~~~~

.. code-block:: diff

   --- Original
   +++ New
    <?php
    use const AAAA;
    use const BBB;
   +
    use Acme;
   +
    use function DDD;
   +
    use AAC;
   +
    use function CCC\AA;
   +
    use Bar;
