<?php

namespace App\Http\Controllers\ShareAction;

use App\Enums\DesignEnum;
use App\Models\Page;
use App\Models\Types\DesignInterface;
use App\Models\Types\PageInterface;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;

trait LoadContructLayout
{
	/**
	 * @param Collection|array $components
	 * @return void
	 */
	public function loadLayout($components = []): void
	{
		if (is_array($components)) {
			foreach ($components as $component) {
				switch ($component['type']) {
					case DesignEnum::CenterCategory->value:
						Inertia::share($component['name'], inertia()->optional(fn() => $this->categoryApi->centerCategories()));
						break;
					default:
						# code...
						break;
				}
			}
			return;
		}
		/**
		 * @var \App\Models\Api\PageApi $pageApi
		 */
		$pageApi = $this->pageApi;

		/**
		 * define and load page data component layout. 
		 */
		$components->map(function ($component) use ($pageApi): void {
			switch ($component->{DesignInterface::VALUE}['type']) {
				case DesignEnum::TopPage->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->topPage()));
					break;
				case DesignEnum::Videos->value:
					Inertia::share(
						$component->{DesignInterface::NAME},
						inertia()->optional(
							fn() => Page::whereRelation('pageContents', 'type', 'video')
								->latest('created_at')
								->distinct('id')
								->limit(2)
								->with(['pageContents' => function ($query) {
									$query->where('type', 'video');
								}])
								->get()
						)
					);
					break;
				case DesignEnum::TopComment->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->topComment()));
					break;
				case DesignEnum::TimeLine->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->pageFilterTimeline('timeline')));
					break;
				case DesignEnum::CenterCategory->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $this->categoryApi->centerCategories()));
					break;
				case DesignEnum::Banner->value:
					break;
				case DesignEnum::LatestPage->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->lastestList()));
					break;
				// gridPage pageRandom
				case DesignEnum::GridPage->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->pageRandom()));
					break;
				case DesignEnum::PageRandom->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->pageRandom()));
					break;
				case DesignEnum::Suggest->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->pageRandom()));
					break;
				case DesignEnum::ListHorizon->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->defer(fn() => $pageApi->pageRandom()));
					break;
				case DesignEnum::Approved->value:
					Inertia::share($component->{DesignInterface::NAME}, inertia()->optional(fn() => $pageApi->pageRandom()));
					break;
				default:
					# code... defer: allway load when start.
					# listVertical
					break;
			}
			// dd($component->{DesignInterface::TYPE}, $component->{DesignInterface::VALUE});
		});

		return;
	}

	/**
	 * @param \App\Models\Design $topPage
	 * @return void
	 */
	protected function loadTopPage($topPage) {}
}
