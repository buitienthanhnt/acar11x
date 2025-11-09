<?php

namespace App\Http\Controllers\AdminHtml;

use App\Enums\DesignEnum;
use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Page;
use App\Models\Types\DesignInterface;
use App\Models\Types\PageInterface;
use Illuminate\Http\Request;

final class DesignController extends Controller
{
	/**
	 * @var \App\Models\Design $design
	 */
	protected $design;

	function __construct(
		Design $design
	) {
		$this->design = $design;
	}

	/**
	 * page for home of design for layout.
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
	 */
	public function home()
	{
		return view("adminhtml.pages.designView.home");
	}

	/**
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
	 */
	function pageSetup()
	{
		return view('adminhtml.pages.designView.page-setup');
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function pageStore(Request $request)
	{
		$type = $request->get(DesignInterface::TYPE);
		/**
		 * delete old records
		 */
		$this->design->where(DesignInterface::TYPE, $type)->delete();
		/**
		 * insert new records.
		 */
		$this->design->insert($this->formatFormData($request));
		return redirect()->back()->with('message', "saved for $type design");
	}

	protected function formatFormData(Request $request)
	{

		$type = $request->get(DesignInterface::TYPE);
		/**
		 * remove list key from post input request and return array input key => values
		 */
		$request = $request->except([DesignInterface::TYPE]);

		$formData = [];
		foreach ($request as $key => $value) {
			if ($parseValue = json_decode($value, true)) {
				/**
				 * format for banner type:
				 * 1.add alias attr
				 * 2. format imagePath value.
				 */
				if ($parseValue['type'] === DesignEnum::Banner->value) {
					$page = Page::find($parseValue['page']);
					$value = json_encode([
						...$parseValue,
						"alias" => $page->{PageInterface::ALIAS},
						"imagePath" => urlToStoragePath($parseValue['imagePath']),
					]);
				}
			}

			$formData[] = [
				DesignInterface::NAME => $key,
				DesignInterface::TYPE => $type,
				DesignInterface::VALUE => $value,
			];
		}

		return $formData;
	}
}
