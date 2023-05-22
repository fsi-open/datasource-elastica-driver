<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

return [
    'p1' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 1, 'phone' => 123456789], ['id' => 2, 'phone' => 987654321]],
        'branch'    => ['id' => 1, 'name' => 'test branch'],
        'timestamp' => '2014-06-01T23:01:16+0200',
        'active'    => true,
        'salary'    => 111111,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut volutpat magna a ligula posuere tempus. MarkA. MarkB',
    ],
    'p2' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 3, 'phone' => 123456789], ['id' => 4, 'phone' => 987654321]],
        'branch'    => ['id' => 2, 'name' => 'test branch'],
        'timestamp' => '2014-06-02T22:02:16+0200',
        'active'    => false,
        'salary'    => 222222,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus semper id leo non semper. Aenean. MarkA, MarkC',
    ],
    'p3' => [
        'name'      => 'Janusz',
        'surname'   => 'Grażyński',
        'phones'    => [['id' => 3, 'phone' => 123456789], ['id' => 4, 'phone' => 987654321]],
        'branch'    => ['id' => 2, 'name' => 'test branch'],
        'timestamp' => '2014-06-02T23:02:16+0200',
        'active'    => false,
        'salary'    => 222222,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dignissim ante. Aenean posuere condimentum. MarkB. MarkD',
    ],
    'p4' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 5, 'phone' => 123456789], ['id' => 6, 'phone' => 987654321]],
        'branch'    => ['id' => 3, 'name' => 'test branch'],
        'timestamp' => '2014-06-03T21:03:16+0200',
        'active'    => true,
        'salary'    => 333333,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi consequat ultricies molestie. Curabitur convallis pellentesque.',
    ],
    'p5' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 7, 'phone' => 123456789], ['id' => 8, 'phone' => 987654321]],
        'branch'    => ['id' => 4, 'name' => 'test branch'],
        'timestamp' => '2014-06-04T20:04:16+0200',
        'active'    => false,
        'salary'    => 345,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at urna eu nisl malesuada aliquam.',
    ],
    'p6' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 9, 'phone' => 123456789], ['id' => 10, 'phone' => 987654321]],
        'branch'    => ['id' => 5, 'name' => 'test branch'],
        'timestamp' => '2014-06-05T19:05:16+0200',
        'active'    => true,
        'salary'    => 123,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vel tellus nunc. Mauris eu cursus.',
    ],
    'p7' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 11, 'phone' => 123456789], ['id' => 12, 'phone' => 987654321]],
        'branch'    => null,
        'timestamp' => '2014-06-06T18:06:16+0200',
        'active'    => false,
        'salary'    => 500,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam pretium pharetra purus vel pharetra. Praesent.',
    ],
    'p8' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 13, 'phone' => 123456789], ['id' => 14, 'phone' => 987654321]],
        'branch'    => ['id' => 7, 'name' => 'test branch'],
        'timestamp' => '2014-06-07T17:07:16+0200',
        'active'    => false,
        'salary'    => 456,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc euismod orci ut arcu tincidunt, at.',
    ],
    'p9' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 15, 'phone' => 123456789], ['id' => 16, 'phone' => 987654321]],
        'branch'    => null,
        'timestamp' => '2014-06-08T16:08:16+0200',
        'active'    => false,
        'salary'    => 783,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus non est facilisis eros interdum luctus.',
    ],
    'p10' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 17, 'phone' => 123456789], ['id' => 18, 'phone' => 987654321]],
        'branch'    => ['id' => 9, 'name' => 'test branch'],
        'timestamp' => '2014-06-09T15:09:16+0200',
        'active'    => false,
        'salary'    => 321,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ultrices felis in sem mattis, ullamcorper.',
    ],
    'p11' => [
        'name'      => 'Jan',
        'surname'   => 'Kowalski',
        'phones'    => [['id' => 19, 'phone' => 123456789], ['id' => 20, 'phone' => 987654321]],
        'branch'    => ['id' => 10, 'name' => 'test branch'],
        'timestamp' => null,
        'active'    => false,
        'salary'    => 567,
        'about'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec egestas, leo eu suscipit mattis, lorem.',
    ],
];
