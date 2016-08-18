<?php

namespace app\modules\cii\base;

interface LazyModelInterface {
	static public function hasLazyCRUD();
    static public function getLazyCRUD();
}
