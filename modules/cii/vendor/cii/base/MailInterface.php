<?php

namespace cii\base;


interface MailInterface {
	/* mail content */
	public function getSubject($data);
	public function getHtml($data, $embeds);
	public function getText($data);

	/* attachements and embeds */
	public function getEmbeds();
	public function getAttachements();
}