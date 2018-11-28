<?php

declare(strict_types=1);

namespace ITU\Application\UI;

/**
 * @method \Nette\Bridges\ApplicationLatte\Template getTemplate()
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	/**
	 * @var \ITU\Application\Templates\ITemplateLocator
	 */
	private $templateLocator;


	public function injectTemplateLocator(\ITU\Application\Templates\ITemplateLocator $templateLocator): void
	{
		$this->templateLocator = $templateLocator;
	}


	public function formatTemplateFiles(): array
	{
		$viewTemplates = $this->templateLocator->formatViewTemplate((string) $this->getName(), $this->getView());

		return $viewTemplates ?: parent::formatTemplateFiles();
	}


	public function formatLayoutTemplateFiles(): array
	{
		$layoutTemplates = $this->templateLocator->formatLayoutTemplate(
			(string) $this->getName(),
			(string) $this->getLayout()
		);

		return $layoutTemplates ?: parent::formatLayoutTemplateFiles();
	}
}
