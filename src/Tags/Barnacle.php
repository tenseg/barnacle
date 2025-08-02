<?php

namespace Tenseg\Barnacle\Tags;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Log;
use Statamic\Auth\User;
use Statamic\Facades\Entry;
use Statamic\Facades\Preference;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

class Barnacle extends Tags
{
    public function __construct()
    {
        // do nothing
    }

    protected function getVersion(): string
    {
        if (! InstalledVersions::isInstalled('tenseg/barnacle')) {
            return '';
        }

        return InstalledVersions::getPrettyVersion('tenseg/barnacle');
    }

    protected function ensureLeadingSlash($path)
    {
        if (substr($path, 0, 1) !== '/') {
            $path = '/'.$path;
        }

        return $path;
    }

    protected function isEnabled(): bool
    {
        $cookie = null;
        if (Preference::get('barnacle_disabled', true)) {
            if ($cname = config('barnacle.cookie', '')) {
                $cookie = request()->cookie($cname);
            }
        }

        return config('barnacle.always') ?? $cookie ?? config('app.debug', false);
    }

    public function index(): string
    {
        Log::debug('barnacle tag called', ['options' => config('barnacle.options')]);

        if (! $this->isEnabled()) {
            return '';
        }

        if (request()->query->has('live-preview')) {
            return '';
        }

        if (app()->runningInConsole()) {
            return '';
        }

        $user = User::current();
        $components = [];
        if ($user) {
            $hidden = Preference::get('barnacle_hidden_components', []);
            foreach (config('barnacle.components') as $key => $label) {
                if ($user->can('use barnacle component '.$key) && ! in_array($key, $hidden)) {
                    $components[] = $key;
                }
            }
        } else {
            $components[] = 'login';
        }

        $open = 'open';
        if (isset($_COOKIE['barnacle-hider'])) {
            if ($_COOKIE['barnacle-hider'] !== 'open') {
                $open = '';
            }
        }

        $rendered = 'Error: Barnacle could not find the index view';
        $view = 'barnacle::index';
        if (view()->exists($view)) {
            $url = app('request')->url();
            $path = $this->ensureLeadingSlash(app('request')->uri()->path());
            $site = Site::findByUrl($url);
            $entry = Entry::findByUri($path, $site);
            $barnacleData = [
                'url' => $url,
                'path' => $path,
                'site' => $site,
                'entry' => $entry,
                'components' => $components,
                'options' => config('barnacle.options'),
                'source_path' => $entry ? $entry->path() : '',
                'open' => $open,
                'version' => $this->getVersion(),
            ];

            $rendered = (new \Statamic\View\View)
                ->template($view)
                ->with(['barnacle' => $barnacleData])
                ->cascadeContent($barnacleData['entry'])
                ->render();
        }

        return $rendered;
    }
}
