<?php

namespace cii\base;

use yii\base\InvalidConfigException;

abstract class ViewMail extends BaseMail {
	public $viewPath = null;
	public $viewName = null;
	
	public function init() {
		if(!$this->viewPath) {
			throw new InvalidConfigException();
		}

		if(!$this->viewName) {
			throw new InvalidConfigException();
		}

		parent::init();
	}

	public function getHtml($data, $embeds) {
        return $this->mailer->render(
            $this->viewPath . '/' . $this->viewName . '-html',
            $data + ['embeds' => $embeds],
            $this->mailer->htmlLayout
        );
    }


    public function getText($data) {
        return $this->mailer->render(
            $this->viewPath . '/' . $this->viewName . '-text',
            $data,
            $this->mailer->textLayout
        );
    }
}