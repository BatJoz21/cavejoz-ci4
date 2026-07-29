<?php

namespace Config;

use App\Validation\CustomRules;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
        CustomRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $newUser = [
        'full_name'     => [
            'label'         => 'Full Name',
            'rules'         => ['required', 'max_length[200]'],
        ],
        'username'      => [
            'label'         => 'Username',
            'rules'         => ['required', 'max_length[49]'],
        ],
        'email'         => [
            'label'         => 'Email',
            'rules'         => [
                'required',
                'max_length[190]',
                'valid_email',
            ],
        ],
        'password'      => [
            'label'         => 'Password',
            'rules'         => [
                'required',
                'max_length[16]',
                'strong_password',
            ],
            'errors' => [
                'strong_password' => 'Password must be at least 8 characters and include an uppercase letter, lowercase letter, number, and symbol.',
            ],
        ],
        'confirm_password'  => [
            'label'         => 'Confirm Password',
            'rules'         => 'required|matches[password]',
        ],
    ];
}
