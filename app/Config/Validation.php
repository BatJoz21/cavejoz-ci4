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
            'rules'         => ['required', 'regex_match[/^[a-zA-Z0-9_]{3,30}$/]'],
            'errors'        => [
                'regex_match' => 'The {field} field may only contain letters, numbers, and underscores, and must be 3-30 characters.',
            ],
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
                'max_length[128]',
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

    public $userLogin = [
        'email'         => [
            'label'         => 'Email',
            'rules'         => [
                'required',
                'valid_email',
            ],
        ],
        'password'      => [
            'label'         => 'Password',
            'rules'         => [
                'required',
            ],
        ],
    ];

    public $updateProfile = [
        'full_name'     => [
            'label'         => 'Full Name',
            'rules'         => ['required', 'max_length[200]'],
        ],
        'username'      => [
            'label'         => 'Username',
            'rules'         => ['required', 'regex_match[/^[a-zA-Z0-9_]{3,30}$/]'],
            'errors'        => [
                'regex_match' => 'The {field} field may only contain letters, numbers, and underscores, and must be 3–30 characters.',
            ],
        ],
    ];

    public $newPost = [
        'visibility'    => [
            'label'         => 'Visibility',
            'rules'         => 'required'
        ],
        'caption'       => [
            'label'         => 'Caption',
            'rules'         => 'required|max_length[500]',
        ],
    ];

    public $editPost = [
        'caption'       => [
            'label'         => 'Caption',
            'rules'         => 'required|max_length[500]',
        ],
    ];
}
