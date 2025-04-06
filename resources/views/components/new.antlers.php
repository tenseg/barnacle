{{ if logged_in }}
<?php
    use Statamic\Facades\Collection;
    
    $user = auth()->user();

    $collections = Collection::all()
        ->filter(fn ($collection) => $user->can('view '.$collection->handle().' entries'))
        ->reduce(function ($carry, $collection) use ($site, $user) {
            $handle = $collection->handle();

            if ($carry === null) {
                $carry = [];
            }

            $blueprints = $user->can("create $handle entries")
                ? $collection->entryBlueprints()
                    ->select('title', 'handle')
                    ->map(fn ($blueprint) => [
                        'name' => $blueprint['title'],
                        'url' => cp_route('collections.entries.create', [$collection, $site, 'blueprint' => $blueprint['handle']]),
                    ])
                : collect([]);

            foreach ($blueprints as $item) {
                $carry[] = $item;
            }

            return $carry;
        });

    $choices = '';
    $maxLength = 0;
    foreach($collections as $collection) {
        $label = $collection['name'];
        $maxLength = max(strlen($label), $maxLength);
        $choices .= "<a class='barnacle-component' href='{$collection['url']}'>$label</a>";
    }
    if ($choices) {
?>
<details class="barnacle-component toggle" title="Create new entry">
  <summary>
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="18"
      height="18"
      viewBox="0 0 24 24"
    >
      <path
        fill="none"
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="1.6"
        d="M6 12h6m6 0h-6m0 0V6m0 6v6"
      />
    </svg>
    <span class="barnacle-label">new</span>
  </summary>
  <div class="barnacle-popup" style="width: <?php echo floor($maxLength * 0.8) ?>em; padding: 0;"><?php echo $choices ?></div>
</details>
<?php
    }
?>
{{ /if }}