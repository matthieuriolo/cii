<?php

namespace cii\base;

use yii\base\InvalidConfigException;
/*
preparation that the mails can be saved as templates in the DB (and therefore easily editable by the user)
*/
abstract class BaseMail implements MailInterface {
	public $mailer = null;

	public function init() {
		if(!$this->mailer) {
			throw new InvalidConfigException();
		}

		parent::init();
	}

	public function getEmbeds() {
		return [];
	}

	public function getAttachements() {
		return [];
	}
}