<?php

class PopularQuery extends DataObject
{
	private static $db = [
		'SearchTerm' => 'Varchar(255)',
		'SearchIndex' => 'Varchar(128)',
//		'ExactMatch' => 'Boolean'
	];

	private static $has_many = [
		'CuratedSearchResults' => 'CuratedSearchResult'
	];

	private static $summary_fields = [
		'SearchTerm' => 'Search Term',
		'SearchIndex' => 'Search Index'
	];

	private static $configurable_indexes = [];

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeByName(['CuratedSearchResults']);

		$indexes = $this->config()->configurable_indexes ?: ClassInfo::subclassesFor('SolrIndex');
		$keys = array_values($indexes);
		$indexes = array_combine($keys, $keys);
		$searchIndex = DropdownField::create(
			'SearchIndex',
			'Search index',
			$indexes
		);
		$fields->addFieldToTab('Root.Main',
			$searchIndex
		);

		if ($this->ID) {
			$curatedResults = GridField::create(
				'CuratedSearchResults',
				'Curated results',
				$this->CuratedSearchResults(),
				GridFieldConfig_RecordEditor::create(12)
					->addComponent(GridFieldOrderableRows::create('Sort'))
			);
			$fields->addFieldToTab('Root.Main',
				$curatedResults
			);
		}

		return $fields;
	}
}
