<?php

class PopularQuery extends DataObject
{
	private static $db = [
		'SearchTerm' => 'Text',
		'SearchIndex' => 'Varchar(128)',
		'ExactMatch' => 'Boolean'
	];

	private static $summary_fields = [
		'SearchTerm' => 'Search Term',
		'SearchIndex' => 'Search Index'
	];

	private static $configurable_indexes = [];

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$indexes = $this->config()->configurable_indexes ?: ClassInfo::subclassesFor('SolrIndex');

		if(count($indexes) > 1) {
			$searchIndex = DropdownField::create(
				'SearchIndex',
				'Search index',
				$indexes
			);
		} else {
			$searchIndex = ReadonlyField::create(
				'SearchIndex',
				'Search index',
				$indexes[0]
			);
		}


		$fields->addFieldsToTab('Root.Main', [
			$searchIndex
		]);

		return $fields;
	}
}
