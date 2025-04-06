<?php

namespace Tenseg\Barnacle\Tags;

use Statamic\Facades\Entry;
use Statamic\Facades\Preference;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use Statamic\Tags\Tags;

class Barnacle extends Tags
{
    public function index(): string
    {
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
                'options' => config('barnacle.options'),
                'components' => $components,
                'open_class' => $open,
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
