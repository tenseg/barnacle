<?php

namespace Tenseg\Barnacle;

use Composer\InstalledVersions;
use Statamic\Facades\Entry;
use Statamic\Facades\Preference;
use Statamic\Facades\Site;
use Symfony\Component\HttpFoundation\Response;

class Barnacle
{
    protected $extensions = [];

    public function isEnabled(): bool
    {
        $cookie = null;
        if (Preference::get('barnacle_disabled', true)) {
            if ($cname = config('barnacle.cookie', '')) {
                $cookie = request()->cookie($cname);
            }
        }

        return config('barnacle.enabled') ?? $cookie ?? config('app.debug', false);
    }

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

    protected function getVersion(): string
    {
        if (! InstalledVersions::isInstalled('tenseg/barnacle')) {
            return '';
        }

        return InstalledVersions::getPrettyVersion('tenseg/barnacle');
    }

    public function content(): string
    {
        $url = app('request')->url();
        $path = app('request')->uri()->path();
        $site = Site::findByUrl($url);
        $entry = Entry::findByUri($path, $site);
        $components = '';
        foreach (config('barnacle.components') as $component => $name) {
            $view = 'barnacle::barnacle.'.$component;
            if (view()->exists($view)) {
                // $components .= view($view)->render();
                $components .= (new \Statamic\View\View)
                    ->template($view)
                    ->with(['barnacle' => [
                        'entry' => $entry,
                        'initial_path' => $entry->path(),
                        'options' => config('barnacle.options'),
                    ]])
                    ->cascadeContent($entry)
                    ->render();
            }
        }

        $html = <<<HTML
			<style>
				#barnacle {
					font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
					font-size: 12px;
					position: fixed;
					top: 0;
					right: 0;
					z-index: 999999;
					display: flex;
					gap: 1px;
				}

				#barnacle .barnacle-component {
					display: block;
					padding: 5px 10px;
					background-color: #000;
					color: #fff;
				}
				
				#barnacle a.barnacle-component:hover {
					background-color: #888;
				}
			</style>
			<div id="barnacle" data-version="{$this->getVersion()}">$components</div>
		HTML;

        return $html;
    }
}
