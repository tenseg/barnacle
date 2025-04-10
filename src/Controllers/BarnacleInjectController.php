<?php

namespace Tenseg\Barnacle\Controllers;

use Composer\InstalledVersions;
use Illuminate\Routing\Controller;
use Statamic\Auth\User;
use Statamic\Entries\Entry;
use Statamic\Facades\Preference;
use Symfony\Component\HttpFoundation\Response;

class BarnacleInjectController extends Controller
{
    protected $extensions = [];

    /**
     * Ensures a leading slash is present on the given path.
     *
     * The app('request')->uri()->path() method returns the root
     * path as '/' but returns every other path without the leading
     * slash. This method remedies that inconsistency.
     *
     * @param  string  $path
     * @return string
     */
    protected function ensureLeadingSlash($path)
    {
        if (substr($path, 0, 1) !== '/') {
            $path = '/'.$path;
        }

        return $path;
    }

    public function isEnabled(): bool
    {
        $cookie = null;
        if (Preference::get('barnacle_disabled', true)) {
            if ($cname = config('barnacle.cookie', '')) {
                $cookie = request()->cookie($cname);
            }
        }

        return config('barnacle.always') ?? $cookie ?? config('app.debug', false);
    }

    /**
     * Injects the barnacle content into the given response.
     *
     * It is called by the InjectBarnacle middleware.
     */
    public function inject(Response $response): void
    {
        if ($barnacle = $this->content()) {
            $content = $response->getContent();
            if (! $pos = mb_strripos($content, '</body>')) {
                return;
            }
            $response->setContent(mb_substr($content, 0, $pos).$barnacle.mb_substr($content, $pos));
            $response->headers->remove('Content-Length');
        }
    }

    /**
     * Retrieves the pretty version of the 'tenseg/barnacle' package
     * as found in the composer.json file.
     *
     * @return string The version string if the package is installed,
     *                otherwise an empty string.
     */
    protected function getVersion(): string
    {
        if (! InstalledVersions::isInstalled('tenseg/barnacle')) {
            return '';
        }

        return InstalledVersions::getPrettyVersion('tenseg/barnacle');
    }

    public function content(): string
    {

        // prepare a list of the components this user is allowed to see
        $hidden = Preference::get('barnacle_hidden_components', []);
        $components = [];
        if ($user = User::current()) {
            foreach (config('barnacle.components') as $key => $value) {
                if ($user->can('use barnacle component '.$key) && ! in_array($key, $hidden)) {
                    $components[] = $key;
                }
            }
        } else {
            $components[] = 'login'; // show the login component to unauthenticated users
        }

        $open = 'open';
        if (isset($_COOKIE['barnacle-hider'])) {
            if ($_COOKIE['barnacle-hider'] !== 'open') {
                $open = '';
            }
        }

        // these are some variables we want to make available to our components in Antlers
        $entry = Entry::query()->where('url', $this->ensureLeadingSlash(app('request')->uri()->path()))->first();
        $barnacleData = [
            'components' => $components,
            'options' => config('barnacle.options'),
            'source_path' => $entry->path(),
            'open' => $open,
            'version' => $this->getVersion(),
        ];

        $html = '';
        $view = 'barnacle::index';
        if (view()->exists($view)) {
            $html .= (new \Statamic\View\View)
                ->template($view)
                ->with(['barnacle' => $barnacleData])
                ->cascadeContent($entry)
                ->render();
        }

        return $html;
    }
}
