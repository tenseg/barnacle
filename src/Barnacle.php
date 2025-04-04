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

    protected static $barnacleData = [];

    public function __construct()
    {
        if (empty(self::$barnacleData)) {
            // doing this here once as static, since this class seems to be instantiated
            // more than once (even though it is instantiated only once, from injectBarnacle)
            $url = app('request')->url();
            $path = $this->ensureLeadingSlash(app('request')->uri()->path());
            $site = Site::findByUrl($url);
            $entry = Entry::findByUri($path, $site);
            self::$barnacleData = [
                'url' => $url,
                'path' => $path,
                'site' => $site,
                'entry' => $entry,
                'options' => config('barnacle.options'),
                'version' => $this->getVersion(),
            ];
        }
    }

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

        return config('barnacle.enabled') ?? $cookie ?? config('app.debug', false);
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

        $components = '';
        foreach (config('barnacle.components') as $component => $name) {
            $view = 'barnacle::barnacle.'.$component;
            if (view()->exists($view)) {
                $components .= (new \Statamic\View\View)
                    ->template($view)
                    ->with(['barnacle' => self::$barnacleData])
                    ->cascadeContent(self::$barnacleData['entry'])
                    ->render();
            }
        }

        $open = 'open';
        if (isset($_COOKIE['barnacle-hider'])) {
            if ($_COOKIE['barnacle-hider'] !== 'open') {
                $open = '';
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
                    flex-wrap: wrap;
                    justify-content: flex-end;
                }

                #barnacle .barnacle-component {
                    display: block;
                    padding: 5px 10px;
                    background-color: #000;
                    color: #fff;
                    cursor: default;
                }
                
                #barnacle .toggle,
                #barnacle a[href].barnacle-component {
                    cursor: pointer;
                }
                
                #barnacle .toggle:hover,
                #barnacle a[href].barnacle-component:hover {
                    background-color: #888;
                }
                
                #barnacle .barnacle-component svg {
                    display: inline-block;
                }
                
                #barnacle .hider.open svg.closed {
                    display: none;
                }
                #barnacle .hider:not(.open) svg.open {
                    display: none;
                }
                
                #barnacle:has(> .hider:not(.open)) {
                    opacity: 0;
                    transition: opacity 300ms 700ms;
                }
                
                #barnacle:hover:has(> .hider:not(.open)) {
                    opacity: 1;
                    transition: opacity 50ms 0ms;
                }
                
                #barnacle details.barnacle-component {
                    position: relative;
                }
                #barnacle details.barnacle-component .barnacle-popup {
                    position: absolute;
                    top: calc(100% + 1px);
                    right: 0;
                    background-color: #000;
                    padding: 5px 10px;
                }
                
                #barnacle details.barnacle-component > summary {
                    list-style: none;
                }
                
                #barnacle details.barnacle-component > summary::-webkit-details-marker {
                    display: none;
                }
                
                @media (max-width: 500px) {
                    #barnacle .barnacle-label {
                        display: none;
                    }
                }
            </style>
            <div id="barnacle" data-version="{$this->getVersion()}">
                $components
                <a class="barnacle-component hider toggle $open" title="Toggle visibility">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" class="open"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9.5 14.5L3 21M5 9.485l9.193 9.193l1.697-1.697l-.393-3.787l5.51-4.673l-5.85-5.85l-4.674 5.51l-3.786-.393z"/></svg>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" class="closed"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9.5 14.5L3 21M7.676 7.89l-.979-.102L5 9.485l9.193 9.193l1.697-1.697l-.102-.981m-4.303-9l3.672-4.329l5.85 5.85l-4.308 3.654M3 3l18 18"/></svg>
                </a>
            </div>
            <script>
                const toggles = document.querySelectorAll('#barnacle .barnacle-component.toggle');
                toggles.forEach(toggle => {
                    toggle.addEventListener('click', () => {
                        const result = toggle.classList.toggle('open');
                        if (toggle.classList.contains('hider')) {
                            console.log('hider hit');
                            if (result) {
                                document.cookie = 'barnacle-hider=open';
                                console.log('open');
                            } else {
                                document.cookie = 'barnacle-hider=closed';
                                console.log('closed');
                            }
                        }
                    })
                })
            </script>
        HTML;

        return $html;
    }
}
