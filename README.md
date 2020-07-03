# Laravel Grid View

[![Latest Stable Version](https://poser.pugx.org/itstructure/laravel-grid-view/v/stable)](https://packagist.org/packages/itstructure/laravel-grid-view)
[![Latest Unstable Version](https://poser.pugx.org/itstructure/laravel-grid-view/v/unstable)](https://packagist.org/packages/itstructure/laravel-grid-view)
[![License](https://poser.pugx.org/itstructure/laravel-grid-view/license)](https://packagist.org/packages/itstructure/laravel-grid-view)
[![Total Downloads](https://poser.pugx.org/itstructure/laravel-grid-view/downloads)](https://packagist.org/packages/itstructure/laravel-grid-view)
[![Build Status](https://scrutinizer-ci.com/g/itstructure/laravel-grid-view/badges/build.png?b=master)](https://scrutinizer-ci.com/g/itstructure/laravel-grid-view/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/itstructure/laravel-grid-view/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/itstructure/laravel-grid-view/?branch=master)

## Introduction

This package is to displaying the model data in a Grid table.

![Grid view appearance](https://github.com/itstructure/laravel-grid-view/blob/master/laravel_grid_view_appearance_en.jpg)

## Requirements
- laravel 5.5+ | 6+ | 7+
- Bootstrap 4 for styling
- JQuery
- php >= 7.1
- composer

## Installation

### General from remote packagist repository

Run the composer command:

`composer require "itstructure/laravel-grid-view": "~1.0.0"`

### If you are testing this package from a local server directory

In application `composer.json` file set the repository, as in example:

```json
"repositories": [
    {
        "type": "path",
        "url": "../laravel-grid-view",
        "options": {
            "symlink": true
        }
    }
],
```

Here,

**../laravel-grid-view** - directory path, which has the same directory level as application and contains Grid View package.

Then run command:

`composer require itstructure/laravel-grid-view:dev-master --prefer-source`

### Registration

Register service provider in **config/app.php**

```php
Itstructure\GridView\GridViewServiceProvider::class,       
```

## Usage

Make sure you use a **Bootstrap 4** for styling and **JQuery** in your application.

### Controller part

Must use `EloquentDataProvider` to insert data in to the view template.

For `EloquentDataProvider` class constructor use a model query object.

Example:

```php
namespace App\Http\Controllers;

use Itstructure\GridView\DataProviders\EloquentDataProvider;
```

```php
class ExampleController extends Controller
{
    public function example()
    {
        $dataProvider = new EloquentDataProvider(ExampleModel::query());
        return view('example-view', [
            'dataProvider' => $dataProvider
        ]);
    }
}
```

### View template part

Use `@gridView()` directive with config array in a blade view template.

#### Simple quick usage

You can simply set columns to display as **string** format in `columnFields` array.

Note:

There search filter fields are displayed automatically. By default **text** form field filters are used.

If you don't want to use search filters, set `useFilters` = **false**.

```php
@php
$gridData = [
    'dataProvider' => $dataProvider,
    'title' => 'Panel title',
    'useFilters' => false,
    'columnFields' => [
        'id',
        'active',
        'icon',
        'created_at'
    ]
];
@endphp
```

```php
@gridView($gridData)
```

Alternative variant without a blade directive:

```php
{!! grid_view([
    'dataProvider' => $dataProvider,
    'useFilters' => false,
    'columnFields' => [
        'id',
        'active',
        'icon',
        'created_at'
    ]
]) !!}
```

#### Setting custom options

##### Special columns

Besides main columns, there can be the next special columns:

- `ActionColumn` - is for displaying Buttons to **view**, **edit** and **delete** rows. 

    Set `'class' => Itstructure\GridView\Columns\ActionColumn::class` in column option
    
    There are the next required `actionTypes`:
    - **view** - makes a link for viewing. Default url scheme: `url()->current()  . '/' . $row->id . '/delete'`.
    - **edit** - makes a link for edition. Default url scheme: `url()->current()  . '/' . $row->id . '/edit'`.
    - **delete** - makes a link for deletion. Default url scheme: `url()->current()  . '/' . $row->id . '/delete'`.
    
    They can be simple **strings**, **arrays** or **callbacks**.
    
    For array format it is necessary set `class`. And optional: `url`, `htmlAttributes`.
    
    By **callback** you can change urls to your routes.
    
    Simple example for a column config:
    
    ```php
    @gridView([
        'dataProvider' => $dataProvider,
        'columnFields' => [
            [
                'label' => 'Actions', // Optional
                'class' => Itstructure\GridView\Columns\ActionColumn::class, // Required
                'actionTypes' => [ // Required
                    'view',
                    'edit' => function ($data) {
                        return '/admin/pages/' . $data->id . '/edit';
                    },
                    [
                        'class' => Itstructure\GridView\Actions\Delete::class, // Required
                        'url' => function ($data) { // Optional
                            return '/admin/pages/' . $data->id . '/delete';
                        },
                        'htmlAttributes' => [ // Optional
                            'target' => '_blank',
                            'style' => 'color: yellow; font-size: 16px;',
                            'onclick' => 'return window.confirm("Are you sure you want to delete?");'
                        ]
                    ]
                ]
            ]
        ]
    ])
    ```

- `CheckboxColumn` - is for displaying Checkboxes to multiple choose the rows.

    Set `'class' => Itstructure\GridView\Columns\CheckboxColumn::class` in column option
    
    There are the next required options:
    - **field** - is for a `name` checkbox input attribute. It is rendered as an array `name="{{ $field }}[]"`.
    - **attribute** - is for a `value` checkbox input attribute. It is rendered as: `value="$row->{$this->attribute}"`.
    
    Simple example for a column config:
    
    ```php
    @gridView([
        'dataProvider' => $dataProvider,
        'columnFields' => [
            [
                'class' => Itstructure\GridView\Columns\CheckboxColumn::class,
                'field' => 'delete',
                'attribute' => 'id'
            ]
        ]
    ])
    ```

##### Filters

There are the next filter's variants:

- Column option to switch off the filter:

    ```php
    'filter' => false
    ```

- `TextFilter` - is a default filter, which renders a text form field to search, using column attribute.

- `DropdownFilter` - is a filter, which renders `<select>` html tag.

    Set the next as column option:
    
    ```php
    @gridView([
        'dataProvider' => $dataProvider,
        'columnFields' => [
            [
                'attribute' => 'active',
                'filter' => [
                    'class' => Itstructure\GridView\Filters\DropdownFilter::class,
                    'data' => ['key' => 'value', 'key' => 'value'] // Array keys are for html <option> tag values, array values are for titles.
                ]
            ]
        ]
    ])
    ```
    
##### Formatters

There are the next formatter keys:

- **html** - is for passing a row content with html tags.
- **image** - is for inserting a row data in to `src` attribute of `<img>` tag.
- **text** - applies `strip_tags()` for a row data.
- **url** - is for inserting a row data in to `href` attribute of `<a>` tag.

For that keys there are the next formatters:

- `HtmlFormatter`
- `ImageFormatter`
- `TextFormatter`
- `UrlFormatter`

Also you can set formatter with some addition options. See the next simple example:

```php
@gridView([
    'dataProvider' => $dataProvider,
    'columnFields' => [
        [
            'attribute' => 'url',
            'format' => [
                'class' => Itstructure\GridView\Formatters\UrlFormatter::class,
                'title' => 'Source',
                'htmlAttributes' => [
                    'target' => '_blank'
                ]
            ]
        ],
        [
            'attribute' => 'content',
            'format' => 'html'
        ]
    ]
])
```

##### Existing form areas and main buttons

![Grid view forms](https://github.com/itstructure/laravel-grid-view/blob/master/laravel_grid_view_forms_en.jpg)

There are two main form areas:

- `grid_view_filters_form`

    Two buttons affect search request submission:
    
    - **Search**. You can change a button label by option: `searchButtonLabel`.
    - **Reset**. You can change a button label by option: `resetButtonLabel`.
    
    If `useFilters` = **false**, these buttons will not be displayed.

- `grid_view_rows_form`

    You can set a specific `action` attribute value by option `rowsFormAction`.
    
    One button affect a main submit request:
    
    - **Send**. You can change a button label by option: `sendButtonLabel`.
    
        Note! This button will be displayed under one of two conditions:
        - There is a checkbox column.
        - Option `useSendButtonAnyway` = **true**.

##### Complex extended example

```php
@php
$gridData = [
    'dataProvider' => $dataProvider,
    'paginatorOptions' => [ // Here you can set some options of paginator Illuminate\Pagination\LengthAwarePaginator, used in a package.
        'pageName' => 'p'
    ],
    'rowsPerPage' => 5, // The number of rows in one page. By default 10.
    'title' => 'Panel title', // It can be empty ''
    'strictFilters' => true, // If true, then a searching by filters will be strict, using an equal '=' SQL operator instead of 'like'.
    'rowsFormAction' => '/admin/pages/deletion', // Route url to send slected checkbox items for deleting rows, for example.
    'useSendButtonAnyway' => true, // If true, even if there are no checkbox column, the main send button will be displayed.
    'searchButtonLabel' => 'Find',
    'columnFields' => [
        [
            'attribute' => 'id', // REQUIRED. Attribute name to get row column data.
            'label' => 'ID', // Column label.
            'filter' => false, // If false, then column will be without a search filter form field.,
            'htmlAttributes' => [
                'width' => '5%' // Width of table column.
            ]
        ],
        [
            'attribute' => 'active', // REQUIRED. Attribute name to get row column data.
            'label' => 'Active', // Column label.
            'value' => function ($row) { // You can set 'value' as a callback function to get a row data value dynamically.
                return '<span class="icon fas '.($row->active == 1 ? 'fa-check' : 'fa-times').'"></span>';
            },
            'filter' => [ // For dropdown it is necessary to set 'data' array. Array keys are for html <option> tag values, array values are for titles.
                'class' => Itstructure\GridView\Filters\DropdownFilter::class, // REQUIRED. For this case it is necessary to set 'class'.
                'data' => [ // REQUIRED.
                    0 => 'No active',
                    1 => 'Active',
                ]
            ],
            'format' => 'html' // To render column content without lossless of html tags, set 'html' formatter.
        ],
        [
            'attribute' => 'icon', // REQUIRED. Attribute name to get row column data.
            'label' => 'Icon', // Column label.
            'value' => function ($row) { // You can set 'value' as a callback function to get a row data value dynamically.
                return $row->icon;
            },
            'filter' => false, // If false, then column will be without a search filter form field.
            'format' => [ // Set special formatter. If $row->icon value is a url to image, it will be inserted in to 'src' attribute of <img> tag.
                'class' => Itstructure\GridView\Formatters\ImageFormatter::class, // REQUIRED. For this case it is necessary to set 'class'.
                'htmlAttributes' => [ // Html attributes for <img> tag.
                    'width' => '100'
                ]
            ]
        ],
        'created_at', // Simple column setting by string.
        [ // Set Action Buttons.
            'class' => Itstructure\GridView\Columns\ActionColumn::class, // REQUIRED.
            'actionTypes' => [ // REQUIRED.
                'view',
                'edit' => function ($data) {
                    return '/admin/pages/' . $data->id . '/edit';
                },
                [
                    'class' => Itstructure\GridView\Actions\Delete::class, // REQUIRED
                    'url' => function ($data) {
                        return '/admin/pages/' . $data->id . '/delete';
                    },
                    'htmlAttributes' => [
                        'target' => '_blank',
                        'style' => 'color: yellow; font-size: 16px;',
                        'onclick' => 'return window.confirm("Sure to delete?");'
                    ]
                ]
            ]
        ],
        [
            // For this case checkboxes will be rendered according with: <input type="checkbox" name="delete[]" value="{{ $row->id }}" />
            'class' => Itstructure\GridView\Columns\CheckboxColumn::class, // REQUIRED.
            'field' => 'delete', // REQUIRED.
            'attribute' => 'id' // REQUIRED.
        ]
    ]
];
@endphp
```

```php
@gridView($gridData)
```

## License

Copyright © 2020 Andrey Girnik girnikandrey@gmail.com.

Licensed under the [MIT license](http://opensource.org/licenses/MIT). See LICENSE.txt for details.