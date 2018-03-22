<?php


class CuratedSearchResult extends DataObject
{
	private static $db = [
		'Sort' => 'Int',
	];
	private static $has_one = [
		'Parent' => 'PopularQuery',
		'Result' => 'DataObject'
	];

	private static $summary_fields = [
		'Result.Title' => 'Title'
	];

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeByName(['ParentID', 'Sort']);
		$index = $this->Parent()->SearchIndex;

		if(class_exists($index)) {
			$index = $index::create();
			if($index instanceof SolrIndex) {
				$classes = $index->getClasses();

				//@todo: only support the first class
				$searchClass = key($classes);

				$tree = DropdownField::create(
					'ResultID',
					'Result',
					$searchClass::get()->where('Title IS NOT NULL')->sort()->map()->toArray()
				);

				$resultClass = HiddenField::create('ResultClass', null, $searchClass);

				$fields->addFieldToTab('Root.Main', $tree);
				$fields->addFieldToTab('Root.Main', $resultClass);
			}
		}


		return $fields;
	}

	public function onBeforeWrite() {
		$this->Sort = 99;	//@todo do this more intelligently
		parent::onBeforeWrite();
	}


}
