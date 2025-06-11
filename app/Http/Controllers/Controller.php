<?php

namespace App\Http\Controllers;

use OpenApi\Attributes\Contact;
use OpenApi\Attributes\Info;

#[Info(
    version: '1.0.0',
    title: 'Название приложения',
    contact: new Contact(name: 'Название приложения'),
)]
abstract class Controller
{
    //
}
