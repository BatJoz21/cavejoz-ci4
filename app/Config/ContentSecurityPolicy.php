<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Stores the default settings for the ContentSecurityPolicy, if you
 * choose to use it. The values here will be read in and set as defaults
 * for the site. If needed, they can be overridden on a page-by-page basis.
 *
 * Suggested reference for explanations:
 *
 * @see https://www.html5rocks.com/en/tutorials/security/content-security-policy/
 */
class ContentSecurityPolicy extends BaseConfig
{
    /**
     * Origin serving Bootstrap, Bootstrap Icons and their font files. Vendoring
     * these into public/ would let every directive below drop back to plain
     * 'self' and remove the third-party dependency from the critical path.
     */
    private const CDN = 'https://cdn.jsdelivr.net';

    public function __construct()
    {
        parent::__construct();

        // connect-src governs fetch/XHR *and* WebSockets. The socket endpoint
        // lives in .env, so derive the directive from it rather than repeating
        // the origin here — otherwise switching to wss:// in production would
        // silently break notifications, chat and the typing indicator.
        $socket = (string) env('api.wsBaseURL');

        if ($socket !== '') {
            $this->connectSrc = ['self', $socket];
        }
    }

    // -------------------------------------------------------------------------
    // Broadbrush CSP management
    // -------------------------------------------------------------------------

    /**
     * Default CSP report context
     */
    public bool $reportOnly = false;

    /**
     * Specifies a URL where a browser will send reports
     * when a content security policy is violated.
     */
    public ?string $reportURI = null;

    /**
     * Specifies a reporting endpoint to which violation reports ought to be sent.
     */
    public ?string $reportTo = null;

    /**
     * Instructs user agents to rewrite URL schemes, changing
     * HTTP to HTTPS. This directive is for websites with
     * large numbers of old URLs that need to be rewritten.
     */
    public bool $upgradeInsecureRequests = false;

    // -------------------------------------------------------------------------
    // CSP DIRECTIVES SETTINGS
    // NOTE: once you set a policy to 'none', it cannot be further restricted
    // -------------------------------------------------------------------------

    /**
     * Will default to `'self'` if not overridden
     *
     * @var list<string>|string|null
     */
    public $defaultSrc;

    /**
     * Lists allowed scripts' URLs.
     *
     * @var list<string>|string
     */
    public $scriptSrc = ['self', self::CDN];

    /**
     * Specifies valid sources for JavaScript <script> elements.
     *
     * @var list<string>|string
     */
    public array|string $scriptSrcElem = ['self', self::CDN];

    /**
     * Specifies valid sources for JavaScript inline event
     * handlers and JavaScript URLs.
     *
     * @var list<string>|string
     */
    public array|string $scriptSrcAttr = 'self';

    /**
     * Lists allowed stylesheets' URLs.
     *
     * @var list<string>|string
     */
    public $styleSrc = ['self', self::CDN];

    /**
     * Specifies valid sources for stylesheets <link> elements.
     *
     * @var list<string>|string
     */
    public array|string $styleSrcElem = ['self', self::CDN];

    /**
     * Specifies valid sources for stylesheets inline
     * style attributes and `<style>` elements.
     *
     * Several views toggle visibility with a `style="display: none"` attribute
     * (Posts/create.php, Posts/edit.php, Conversations/thread.php). Inline style
     * attributes cannot carry a nonce, so 'unsafe-inline' is required for them
     * to apply at all. This is far weaker than script-src 'unsafe-inline' would
     * be — it permits styling only, not execution. Replacing those attributes
     * with a CSS class would let this drop back to plain 'self'.
     *
     * @var list<string>|string
     */
    public array|string $styleSrcAttr = ['self', 'unsafe-inline'];

    /**
     * Defines the origins from which images can be loaded.
     *
     * @var list<string>|string
     */
    public $imageSrc = 'self';

    /**
     * Restricts the URLs that can appear in a page's `<base>` element.
     *
     * Will default to self if not overridden
     *
     * @var list<string>|string|null
     */
    public $baseURI;

    /**
     * Lists the URLs for workers and embedded frame contents
     *
     * @var list<string>|string
     */
    public $childSrc = 'self';

    /**
     * Limits the origins that you can connect to (via XHR,
     * WebSockets, and EventSource).
     *
     * @var list<string>|string
     */
    public $connectSrc = 'self';

    /**
     * Specifies the origins that can serve web fonts.
     *
     * Bootstrap Icons pulls its woff2 files from the same CDN as its stylesheet.
     *
     * @var list<string>|string
     */
    public $fontSrc = ['self', self::CDN];

    /**
     * Lists valid endpoints for submission from `<form>` tags.
     *
     * @var list<string>|string
     */
    public $formAction = 'self';

    /**
     * Specifies the sources that can embed the current page.
     * This directive applies to `<frame>`, `<iframe>`, `<embed>`,
     * and `<applet>` tags. This directive can't be used in
     * `<meta>` tags and applies only to non-HTML resources.
     *
     * @var list<string>|string|null
     */
    public $frameAncestors;

    /**
     * The frame-src directive restricts the URLs which may
     * be loaded into nested browsing contexts.
     *
     * @var list<string>|string|null
     */
    public $frameSrc;

    /**
     * Restricts the origins allowed to deliver video and audio.
     *
     * @var list<string>|string|null
     */
    public $mediaSrc;

    /**
     * Allows control over Flash and other plugins.
     *
     * @var list<string>|string
     */
    public $objectSrc = 'self';

    /**
     * @var list<string>|string|null
     */
    public $manifestSrc;

    /**
     * @var list<string>|string
     */
    public array|string $workerSrc = [];

    /**
     * Limits the kinds of plugins a page may invoke.
     *
     * @var list<string>|string|null
     */
    public $pluginTypes;

    /**
     * List of actions allowed.
     *
     * @var list<string>|string|null
     */
    public $sandbox;

    /**
     * Nonce placeholder for style tags.
     */
    public string $styleNonceTag = '{csp-style-nonce}';

    /**
     * Nonce placeholder for script tags.
     */
    public string $scriptNonceTag = '{csp-script-nonce}';

    /**
     * Replace nonce tag automatically?
     */
    public bool $autoNonce = true;
}
