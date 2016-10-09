# Build, control, submit and validate forms.

* Can be integrated with any framework or non-framework code.
* Includes adapters for illuminate/validation and respect/validation.

Documentation and examples coming in further realeases.

## A very simple example of a using a form with Illuminate/Validation
```php
class Model {
    public $name;
    public $type;
}

$form = new \Okneloper\Forms\Form(new Model);
$form->add('text', 'name', 'Name');
$form->add('select', 'type', 'Type')->options([
    'foo' => 'Bar',
    'baz' => 'FooBar',
]);

if ($request->isPost()) {
    $app = \Illuminate\Support\Facades\App::getFacadeApplication();
    $rules = [
        'name' => 'required',
        'type' => 'in:' . $form->el('type')->listValues(),
    ];
    $resolver = new \Okneloper\Forms\Validation\Illuminate\IlluminateValidatorResolver($app,  $rules);

    $form->setValidatorResolver($resolver);

    $form->submit($_POST);
    if ($form->isValid()) {
        var_dump($form->getModel());
        // the model now contains filtered and validated POST values
    } else {
        $errors = $form->getValidator()->getErrorMessages();
    }
}
```

## Elements

### Buttons
```php
$form->add('button', 'btn', 'ClickMe'); // <buttont type="button"...>ClickMe</button>
$form->add('submitButton', 'btn', 'Submit'); // <buttont type="submit">Submit</button>
```
Button values are ignored when submitting request data to the form.
```php
<?php


$form = new \Okneloper\Forms\Form();

$form->add('text', 'fname');
$form->add('text', 'lname');
$form->add('submitButton', 'Submit');

// this is how data might come from a submitted form
$data = [
    'fname' => 'John',
    'lname' => 'Smith',
    'Submit' => '1',
];

$form->submit($data);

print_r($form->modelToArray());

?>

Array
(
    [fname] => John
    [lname] => Smith
)

```